<?php
include "../init.php";
include "../functions.php";
include "../preprocessing.php";
check_login('admin');

function showAlert($icon, $title, $text = '', $timer = 3000)
{
    return "
        Swal.fire({
            icon: '$icon',
            title: '$title',
            text: '$text',
            timer: $timer,
            timerProgressBar: true,
            showConfirmButton: false
        });
    ";
}

// --- LOGIKA UTAMA (CRUD ATURAN TEKS) ---
$script = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($conn)) {

    // -- PROSES STOPWORDS --
    if (isset($_POST['tambah_stopword'])) {
        $kata = strtolower(trim($_POST['kata_stopword']));
        if (!empty($kata)) {
            $stmt = $conn->prepare("INSERT INTO stopwords (kata) VALUES (?)");
            $stmt->bind_param("s", $kata);
            if ($stmt->execute()) {
                $script = showAlert('success', 'Stopword Ditambahkan!');
            } else {
                $script = showAlert('error', 'Gagal', 'Stopword mungkin sudah ada.');
            }
            $stmt->close();
        }
    }
    if (isset($_POST['hapus_stopword'])) {
        $id = (int)$_POST['id_stopword'];
        $stmt = $conn->prepare("DELETE FROM stopwords WHERE id_stopword = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $script = showAlert('success', 'Stopword Dihapus!');
        }
        $stmt->close();
    }

    // -- PROSES STEMMING RULES --
    if (isset($_POST['tambah_stemming'])) {
        $tipe = $_POST['tipe'];
        $rule = strtolower(trim($_POST['rule']));
        if (!empty($rule) && in_array($tipe, ['prefix', 'suffix'])) {
            $stmt = $conn->prepare("INSERT INTO stemming_rules (tipe, rule) VALUES (?, ?)");
            $stmt->bind_param("ss", $tipe, $rule);
            if ($stmt->execute()) {
                $script = showAlert('success', 'Aturan Stemming Ditambahkan!');
            } else {
                $script = showAlert('error', 'Gagal', 'Aturan mungkin sudah ada.');
            }
            $stmt->close();
        }
    }
    if (isset($_POST['hapus_stemming'])) {
        $id = (int)$_POST['id_rule'];
        $stmt = $conn->prepare("DELETE FROM stemming_rules WHERE id_rule = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $script = showAlert('success', 'Aturan Stemming Dihapus!');
        }
        $stmt->close();
    }

    // -- PROSES TANDA BACA --
    if (isset($_POST['tambah_tanda_baca'])) {
        $karakter = trim($_POST['karakter']);
        if (!empty($karakter)) {
            $stmt = $conn->prepare("INSERT INTO tanda_baca (karakter) VALUES (?)");
            $stmt->bind_param("s", $karakter);
            if ($stmt->execute()) {
                $script = showAlert('success', 'Tanda Baca Ditambahkan!');
            } else {
                $script = showAlert('error', 'Gagal', 'Karakter mungkin sudah ada.');
            }
            $stmt->close();
        }
    }
    if (isset($_POST['hapus_tanda_baca'])) {
        $id = (int)$_POST['id_tanda_baca'];
        $stmt = $conn->prepare("DELETE FROM tanda_baca WHERE id_tanda_baca = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $script = showAlert('success', 'Tanda Baca Dihapus!');
        }
        $stmt->close();
    }
}

// --- Mengambil semua data untuk ditampilkan di tabel ---
$stopwords_data = $conn->query("SELECT * FROM stopwords ORDER BY kata ASC");
$stemming_rules_data = $conn->query("SELECT * FROM stemming_rules ORDER BY tipe, rule ASC");
$tanda_baca_data = $conn->query("SELECT * FROM tanda_baca ORDER BY karakter ASC");

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "link.php"; ?>
    <title>Manajemen Aturan Teks (Preprocessing)</title>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Manajemen Aturan Teks (Preprocessing)</h1>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <ul class="nav nav-tabs card-header-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" id="stopwords-tab" data-toggle="tab" href="#stopwords"
                                        role="tab">Stopwords</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="stemming-tab" data-toggle="tab" href="#stemming"
                                        role="tab">Aturan Stemming</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="tanda-baca-tab" data-toggle="tab" href="#tanda-baca"
                                        role="tab">Tanda Baca</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade show active" id="stopwords" role="tabpanel">
                                    <form action="" method="POST" class="form-inline mb-3">
                                        <input type="text" name="kata_stopword" class="form-control mr-2"
                                            placeholder="Masukkan stopword baru..." required>
                                        <button type="submit" name="tambah_stopword" class="btn btn-primary"><i
                                                class="fas fa-plus"></i> Tambah</button>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="stopwordsTable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Kata</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1;
                                                while ($row = $stopwords_data->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= htmlspecialchars($row['kata']); ?></td>
                                                        <td>
                                                            <form action="" method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus?');">
                                                                <input type="hidden" name="id_stopword"
                                                                    value="<?= $row['id_stopword']; ?>">
                                                                <button type="submit" name="hapus_stopword"
                                                                    class="btn btn-sm btn-danger"><i
                                                                        class="fas fa-trash"></i> Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="stemming" role="tabpanel">
                                    <form action="" method="POST" class="form-inline mb-3">
                                        <select name="tipe" class="form-control mr-2">
                                            <option value="prefix">Prefix (Awalan)</option>
                                            <option value="suffix">Suffix (Akhiran)</option>
                                        </select>
                                        <input type="text" name="rule" class="form-control mr-2"
                                            placeholder="Masukkan aturan (misal: me-, -kan)" required>
                                        <button type="submit" name="tambah_stemming" class="btn btn-primary"><i
                                                class="fas fa-plus"></i> Tambah</button>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="stemmingTable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Tipe</th>
                                                    <th>Aturan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1;
                                                while ($row = $stemming_rules_data->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= ucfirst($row['tipe']); ?></td>
                                                        <td><?= htmlspecialchars($row['rule']); ?></td>
                                                        <td>
                                                            <form action="" method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus?');">
                                                                <input type="hidden" name="id_rule"
                                                                    value="<?= $row['id_rule']; ?>">
                                                                <button type="submit" name="hapus_stemming"
                                                                    class="btn btn-sm btn-danger"><i
                                                                        class="fas fa-trash"></i> Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="tanda-baca" role="tabpanel">
                                    <form action="" method="POST" class="form-inline mb-3">
                                        <input type="text" name="karakter" class="form-control mr-2" maxlength="1"
                                            placeholder="Karakter..." required>
                                        <button type="submit" name="tambah_tanda_baca" class="btn btn-primary"><i
                                                class="fas fa-plus"></i> Tambah</button>
                                    </form>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tandaBacaTable" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Karakter</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1;
                                                while ($row = $tanda_baca_data->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $i++; ?></td>
                                                        <td><?= htmlspecialchars($row['karakter']); ?></td>
                                                        <td>
                                                            <form action="" method="POST"
                                                                onsubmit="return confirm('Yakin ingin menghapus?');">
                                                                <input type="hidden" name="id_tanda_baca"
                                                                    value="<?= $row['id_tanda_baca']; ?>">
                                                                <button type="submit" name="hapus_tanda_baca"
                                                                    class="btn btn-sm btn-danger"><i
                                                                        class="fas fa-trash"></i> Hapus</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php include "footer.php"; ?>
        </div>
    </div>
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
    <?php include "plugin.php"; ?>
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTables untuk setiap tabel
            $('#stopwordsTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
                }
            });
            $('#stemmingTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
                }
            });
            $('#tandaBacaTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Indonesian.json"
                }
            });
        });
        <?php if (!empty($script)) {
            echo $script;
        } ?>
    </script>
</body>

</html>