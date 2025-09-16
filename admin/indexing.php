<?php
include "../init.php";
include "../functions.php";
include "../preprocessing.php";
check_login('admin');

/**
 * Fungsi untuk menampilkan notifikasi SweetAlert2.
 * @param string $icon Tipe ikon (success, error, warning, info)
 * @param string $title Judul notifikasi
 * @param string $text Teks tambahan (opsional)
 * @return string Kode JavaScript untuk dieksekusi
 */
function showAlert($icon, $title, $text = '')
{
    return "
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$text',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        });
    ";
}

// --- LOGIKA UTAMA (PROSES INDEXING VSM) ---
$script = '';
$logProses = ''; // Variabel untuk menyimpan log proses indexing

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mulai_indexing']) && isset($conn)) {

    // Tingkatkan batas waktu eksekusi untuk proses yang lama
    set_time_limit(600); // 10 menit
    $startTime = microtime(true);
    $logProses .= "Memulai proses indexing...\n";

    // --- TAHAP 1: KOSONGKAN TABEL LAMA ---
    $logProses .= "[TAHAP 1] Mengosongkan tabel VSM lama...\n";
    $conn->query("SET FOREIGN_KEY_CHECKS = 0;");
    $conn->query("TRUNCATE TABLE panjang_vektor");
    $conn->query("TRUNCATE TABLE bobot_tf_idf");
    $conn->query("TRUNCATE TABLE inverse_document_frequency");
    $conn->query("TRUNCATE TABLE document_frequency");
    $conn->query("TRUNCATE TABLE term_frequency");
    $conn->query("TRUNCATE TABLE kamus_kata");
    $conn->query("SET FOREIGN_KEY_CHECKS = 1;");
    $logProses .= "✅ Tabel lama berhasil dikosongkan.\n\n";

    // --- TAHAP 2: PREPROCESSING DAN PERHITUNGAN TF ---
    $logProses .= "[TAHAP 2] Membaca data anime dan melakukan preprocessing...\n";

    // Ambil semua aturan preprocessing dari DB sekali saja
    $stopwords = array_column($conn->query("SELECT kata FROM stopwords")->fetch_all(), 0);
    $stemming_rules_result = $conn->query("SELECT tipe, rule FROM stemming_rules");
    $prefixes = [];
    $suffixes = [];
    while ($row = $stemming_rules_result->fetch_assoc()) {
        if ($row['tipe'] == 'prefix') $prefixes[] = $row['rule'];
        if ($row['tipe'] == 'suffix') $suffixes[] = $row['rule'];
    }

    // Fungsi preprocessing yang KONSISTEN
    function prosesTeksIndexing($teks, $stopwords, $prefixes, $suffixes)
    {
        // 1. Case Folding
        $teks = strtolower($teks);
        // 2. Punctuation Removal
        $teks = preg_replace('/[^\w\s]/', ' ', $teks);
        // 3. Tokenization
        $tokens = preg_split('/\s+/', $teks, -1, PREG_SPLIT_NO_EMPTY);
        // 4. Stopword Removal
        $tokens = array_diff($tokens, $stopwords);

        // 5. Stemming (Sederhana)
        $stemmed_tokens = [];
        foreach ($tokens as $token) {
            $original_token = $token;
            // Proses Suffixes (akhiran)
            foreach ($suffixes as $suffix) {
                if (substr($token, -strlen($suffix)) == $suffix) {
                    $token = substr($token, 0, -strlen($suffix));
                    break; // Hanya satu suffix yang dihapus
                }
            }
            // Proses Prefixes (awalan)
            foreach ($prefixes as $prefix) {
                if (strpos($token, $prefix) === 0) {
                    $token = substr($token, strlen($prefix));
                    break; // Hanya satu prefix yang dihapus
                }
            }
            // Jika kata menjadi kosong setelah stemming, kembalikan versi asli sebelum stemming
            $stemmed_tokens[] = !empty($token) ? $token : $original_token;
        }
        return $stemmed_tokens;
    }

    $dokumen = $conn->query("SELECT id_anime, sinopsis FROM anime");
    $totalDokumen = $dokumen->num_rows;
    $kamusKata = [];
    $frekuensiTerm = [];

    foreach ($dokumen as $doc) {
        $id_anime = $doc['id_anime'];
        $tokens = prosesTeksIndexing($doc['sinopsis'], $stopwords, $prefixes, $suffixes);

        foreach ($tokens as $token) {
            if (empty($token)) continue;
            // Masukkan kata ke kamus jika belum ada
            if (!isset($kamusKata[$token])) {
                $stmt = $conn->prepare("INSERT INTO kamus_kata (kata) VALUES (?)");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                $kamusKata[$token] = $stmt->insert_id;
                $stmt->close();
            }
            $id_kata = $kamusKata[$token];
            // Hitung frekuensi kemunculan term di dokumen
            $frekuensiTerm[$id_anime][$id_kata] = ($frekuensiTerm[$id_anime][$id_kata] ?? 0) + 1;
        }
    }
    $logProses .= "✅ Preprocessing (termasuk stemming) dan TF untuk $totalDokumen dokumen selesai.\n";

    // Masukkan data TF ke database
    $stmt_tf = $conn->prepare("INSERT INTO term_frequency (id_anime, id_kata, frekuensi) VALUES (?, ?, ?)");
    foreach ($frekuensiTerm as $id_anime => $kataArr) {
        foreach ($kataArr as $id_kata => $frek) {
            $stmt_tf->bind_param("iii", $id_anime, $id_kata, $frek);
            $stmt_tf->execute();
        }
    }
    $stmt_tf->close();
    $logProses .= "✅ Data Term Frequency (TF) berhasil disimpan.\n\n";

    // --- TAHAP 3: DF, IDF, DAN BOBOT TF-IDF ---
    $logProses .= "[TAHAP 3] Menghitung DF, IDF, dan Bobot TF-IDF...\n";
    if ($totalDokumen > 0) {
        $conn->query("INSERT INTO document_frequency (id_kata, jumlah_dokumen) SELECT id_kata, COUNT(DISTINCT id_anime) FROM term_frequency GROUP BY id_kata");
        $logProses .= "✅ Document Frequency (DF) berhasil dihitung.\n";

        $conn->query("INSERT INTO inverse_document_frequency (id_kata, nilai_idf) SELECT id_kata, LOG10($totalDokumen / jumlah_dokumen) FROM document_frequency");
        $logProses .= "✅ Inverse Document Frequency (IDF) berhasil dihitung.\n";

        $conn->query("INSERT INTO bobot_tf_idf (id_anime, id_kata, bobot) SELECT tf.id_anime, tf.id_kata, (tf.frekuensi * idf.nilai_idf) FROM term_frequency tf JOIN inverse_document_frequency idf ON tf.id_kata = idf.id_kata");
        $logProses .= "✅ Bobot TF-IDF berhasil dihitung dan disimpan.\n\n";
    } else {
        $logProses .= "⚠️ Tidak ada dokumen untuk dihitung (DF, IDF, Bobot).\n\n";
    }

    // --- TAHAP 4: HITUNG DAN SIMPAN PANJANG VEKTOR ---
    $logProses .= "[TAHAP 4] Menghitung dan menyimpan panjang vektor untuk optimasi...\n";
    if ($totalDokumen > 0) {
        $conn->query("INSERT INTO panjang_vektor (id_anime, panjang) SELECT id_anime, SQRT(SUM(POW(bobot, 2))) FROM bobot_tf_idf GROUP BY id_anime");
        $logProses .= "✅ Panjang vektor berhasil disimpan.\n\n";
    } else {
        $logProses .= "⚠️ Tidak ada vektor untuk dihitung panjangnya.\n\n";
    }

    $endTime = microtime(true);
    $executionTime = round($endTime - $startTime, 2);
    $logProses .= "[SELESAI] Proses indexing selesai dalam $executionTime detik.\n";
    $script = showAlert('success', 'Indexing Selesai!', "Proses memakan waktu $executionTime detik.");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "link.php"; ?>
    <title>Proses Indexing VSM</title>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Proses Indexing VSM</h1>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Panel Kontrol Indexing</h6>
                                </div>
                                <div class="card-body">
                                    <p>Proses indexing akan membangun atau memperbarui "otak" dari mesin pencari
                                        berdasarkan data sinopsis anime yang ada. Proses ini akan melakukan:</p>
                                    <ol>
                                        <li>Mengosongkan data VSM yang lama.</li>
                                        <li>Melakukan *preprocessing* (termasuk **stemming**) pada semua sinopsis.</li>
                                        <li>Menghitung nilai Term Frequency (TF), IDF, dan Bobot TF-IDF.</li>
                                        <li>Menghitung dan menyimpan **panjang vektor** untuk mempercepat pencarian.
                                        </li>
                                    </ol>
                                    <p class="text-warning"><i class="fas fa-exclamation-triangle"></i>
                                        <strong>Perhatian:</strong> Proses ini bisa memakan waktu beberapa saat
                                        tergantung jumlah data. Jangan menutup halaman ini saat proses berjalan.
                                    </p>

                                    <form action="" method="POST"
                                        onsubmit="document.getElementById('loading-overlay').style.display = 'flex';">
                                        <button type="submit" name="mulai_indexing"
                                            class="btn btn-primary btn-lg btn-block">
                                            <i class="fas fa-cogs"></i> Mulai Proses Indexing Ulang
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">Log Proses</h6>
                                </div>
                                <div class="card-body">
                                    <textarea class="form-control" rows="15" readonly
                                        style="background-color: #2d333b; color: #adbac7; font-family: monospace;"><?= htmlspecialchars($logProses); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php include "footer.php"; ?>
        </div>
    </div>

    <div id="loading-overlay"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center; color: white; flex-direction: column;">
        <div class="spinner-border mb-3" role="status" style="width: 3rem; height: 3rem;">
            <span class="sr-only">Loading...</span>
        </div>
        <h5>Proses indexing sedang berjalan, mohon tunggu...</h5>
    </div>

    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <?php include "plugin.php"; ?>
    <script>
        <?php if (!empty($script)) {
            echo $script;
        } ?>
    </script>
</body>

</html>