<?php
// ==================================================================
// BAGIAN BACKEND (MESIN PENCARI VSM)
// ==================================================================
if (isset($_GET['action']) && $_GET['action'] == 'search') {
    header('Content-Type: application/json');
    include "../functions.php";

    // Fungsi Preprocessing Terpusat
    function prosesTeks($teks, $conn)
    {
        static $stopwords, $prefixes, $suffixes;
        if (is_null($stopwords)) {
            $stopwords = array_column($conn->query("SELECT kata FROM stopwords")->fetch_all(), 0);
            $stemming_rules_result = $conn->query("SELECT tipe, rule FROM stemming_rules");
            $prefixes = [];
            $suffixes = [];
            while ($row = $stemming_rules_result->fetch_assoc()) {
                if ($row['tipe'] == 'prefix') $prefixes[] = $row['rule'];
                if ($row['tipe'] == 'suffix') $suffixes[] = $row['rule'];
            }
        }

        $teks = strtolower($teks);
        $teks = preg_replace('/[^\w\s]/', ' ', $teks);
        $tokens = preg_split('/\s+/', $teks, -1, PREG_SPLIT_NO_EMPTY);
        $tokens = array_diff($tokens, $stopwords);

        $stemmed_tokens = [];
        foreach ($tokens as $token) {
            $original_token = $token;
            foreach ($suffixes as $suffix) {
                if (substr($token, -strlen($suffix)) == $suffix) {
                    $token = substr($token, 0, -strlen($suffix));
                    break;
                }
            }
            foreach ($prefixes as $prefix) {
                if (strpos($token, $prefix) === 0) {
                    $token = substr($token, strlen($prefix));
                    break;
                }
            }
            $stemmed_tokens[] = !empty($token) ? $token : $original_token;
        }
        return $stemmed_tokens;
    }

    $response = ['results' => [], 'debug' => [], 'query_tokens' => []];
    try {
        if (!$conn) {
            throw new Exception("Koneksi database gagal.");
        }

        if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
            $query_text = $_GET['query'];
            $query_tokens_array = prosesTeks($query_text, $conn);
            $query_tokens_tf = array_count_values($query_tokens_array);

            // Simpan token query untuk highlighting
            $response['query_tokens'] = array_keys($query_tokens_tf);

            if (empty($query_tokens_tf)) {
                echo json_encode($response);
                exit();
            }

            $query_vector = [];
            $sum_of_squares_query = 0;
            $placeholders = implode(',', array_fill(0, count($query_tokens_tf), '?'));
            $types = str_repeat('s', count(array_keys($query_tokens_tf)));
            $words = array_keys($query_tokens_tf);

            $sql = "SELECT id_kata, kata, nilai_idf FROM kamus_kata JOIN inverse_document_frequency USING(id_kata) WHERE kata IN ($placeholders)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param($types, ...$words);
            $stmt->execute();
            $kamus_result = $stmt->get_result();

            while ($row = $kamus_result->fetch_assoc()) {
                $tf = $query_tokens_tf[$row['kata']];
                $bobot = $tf * $row['nilai_idf'];
                $query_vector[$row['id_kata']] = $bobot;
                $sum_of_squares_query += pow($bobot, 2);
            }
            $stmt->close();

            $query_length = ($sum_of_squares_query > 0) ? sqrt($sum_of_squares_query) : 0;

            if (!empty($query_vector) && $query_length > 0) {
                $doc_lengths_result = $conn->query("SELECT id_anime, panjang FROM panjang_vektor");
                $doc_lengths = array_column($doc_lengths_result->fetch_all(MYSQLI_ASSOC), 'panjang', 'id_anime');

                $dot_products = [];
                $bobot_placeholders = implode(',', array_keys($query_vector));
                $bobot_sql = "SELECT id_anime, id_kata, bobot FROM bobot_tf_idf WHERE id_kata IN ($bobot_placeholders)";
                $bobot_result = $conn->query($bobot_sql);
                while ($row = $bobot_result->fetch_assoc()) {
                    $dot_products[$row['id_anime']] = ($dot_products[$row['id_anime']] ?? 0) + ($query_vector[$row['id_kata']] * $row['bobot']);
                }

                $similarity_scores = [];
                foreach ($dot_products as $id_anime => $dot) {
                    if (isset($doc_lengths[$id_anime]) && $doc_lengths[$id_anime] > 0) {
                        $denominator = $query_length * $doc_lengths[$id_anime];
                        $similarity_scores[$id_anime] = ($denominator > 0) ? ($dot / $denominator) : 0;
                    }
                }
                arsort($similarity_scores);

                if (!empty($similarity_scores)) {
                    $anime_ids = array_keys(array_slice($similarity_scores, 0, 10, true));
                    $id_list = implode(',', $anime_ids);
                    if (!empty($id_list)) {
                        // PERBAIKAN: Ambil juga kolom sinopsis
                        $sql_anime = "SELECT id_anime, judul, genre, gambar_url, sinopsis FROM anime WHERE id_anime IN ($id_list) ORDER BY FIELD(id_anime, $id_list)";
                        $anime_result = $conn->query($sql_anime);
                        while ($row = $anime_result->fetch_assoc()) {
                            $row['score'] = $similarity_scores[$row['id_anime']];
                            $response['results'][] = $row;
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        http_response_code(500);
        $response['debug'][] = "FATAL ERROR: " . $e->getMessage();
    }
    echo json_encode($response);
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Anime VSM</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --dark-bg: #121212;
            --card-bg: #1e1e1e;
            --text-color: #e0e0e0;
            --primary-color: #0d6efd;
            --highlight-color: #ffc107;
        }

        body {
            background-color: var(--dark-bg);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        .hero-section {
            padding: 4rem 2rem;
            background: linear-gradient(to top, rgba(18, 18, 18, 1) 0%, rgba(18, 18, 18, 0.5) 50%, rgba(18, 18, 18, 1) 100%), url('https://wallpapercave.com/wp/wp8816999.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            border-radius: 1rem;
        }

        #search-query {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
            resize: none;
        }

        #search-query::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        #search-query:focus {
            background-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, .25);
        }

        .result-card {
            display: flex;
            margin-bottom: 1.5rem;
            background-color: var(--card-bg);
            border-radius: 0.5rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, .3);
            border: 1px solid rgba(255, 255, 255, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .result-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .5);
        }

        .result-card img {
            width: 150px;
            height: 220px;
            object-fit: cover;
        }

        .result-card .card-body {
            padding: 1.25rem;
        }

        .result-card .genre-tag {
            display: inline-block;
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--primary-color);
            padding: 0.25rem 0.6rem;
            border-radius: 50px;
            font-size: 0.8rem;
            margin: 0 5px 5px 0;
            border: 1px solid var(--primary-color);
        }

        .result-card .sinopsis {
            font-size: 0.9rem;
            color: #b0b0b0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .result-card .sinopsis .highlight {
            background-color: var(--highlight-color);
            color: var(--dark-bg);
            font-weight: bold;
        }

        .log-container {
            position: sticky;
            top: 1rem;
        }

        #debug-log {
            background-color: #212529;
            color: #a3b0be;
            border-top: 3px solid var(--highlight-color);
            height: 400px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 0.85rem;
        }
    </style>
</head>

<body>
    <div class="container-fluid mt-4 mb-5">
        <div class="hero-section text-center mb-4">
            <h1 class="display-4 font-weight-bold">Anime Finder</h1>
            <p class="lead">Temukan anime yang relevan berdasarkan deskripsi cerita kamu.</p>
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <textarea id="search-query" class="form-control form-control-lg" rows="3"
                        placeholder="Ketik deskripsi atau sinopsis di sini..."></textarea>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div id="hasil-pencarian" class="mt-4">
                    <div id="placeholder-text" class="text-center text-muted">
                        <h4><i class="fas fa-search"></i> Hasil pencarian akan muncul di sini</h4>
                        <p>Mulai ketik di kotak pencarian di atas.</p>
                    </div>
                    <div id="loading-spinner" class="text-center" style="display: none;">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
                        <p class="mt-2">Mencari...</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="log-container mt-4">
                    <div class="card" style="background-color: var(--card-bg);">
                        <div class="card-header font-weight-bold text-warning">
                            <i class="fas fa-clipboard-list"></i> Log Proses Diagnosa
                        </div>
                        <div id="debug-log" class="card-body">
                            <pre id="debug-output" class="mb-0 text-white"></pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            let typingTimer;
            const doneTypingInterval = 500;
            const minLength = 3;

            $('#search-query').on('keyup', function() {
                clearTimeout(typingTimer);
                if ($('#search-query').val().trim().length >= minLength) {
                    typingTimer = setTimeout(performSearch, doneTypingInterval);
                }
            });

            function performSearch() {
                const query = $('#search-query').val();
                const resultContainer = $('#hasil-pencarian');
                const spinner = $('#loading-spinner');
                const placeholder = $('#placeholder-text');
                const debugOutput = $('#debug-output');

                placeholder.hide();
                resultContainer.html('');
                spinner.show();
                debugOutput.html('');

                $.ajax({
                    url: 'index.php?action=search',
                    type: 'GET',
                    data: {
                        query: query
                    },
                    dataType: 'json',
                    success: function(response) {
                        spinner.hide();

                        // Tampilkan log diagnosa
                        debugOutput.append(`[INFO] Pencarian untuk: "${query}"\n`);
                        if (response.query_tokens && response.query_tokens.length > 0) {
                            debugOutput.append(
                                `[INFO] Token Ditemukan: ${response.query_tokens.join(', ')}\n\n`);
                        }

                        if (response.results && response.results.length > 0) {
                            $.each(response.results, function(index, anime) {
                                const score = (anime.score * 100).toFixed(2);
                                let genreHtml = '';
                                if (anime.genre) {
                                    anime.genre.split(',').forEach(g => {
                                        genreHtml +=
                                            `<span class="genre-tag">${g.trim()}</span>`;
                                    });
                                }

                                // Highlight sinopsis
                                let sinopsisHtml = anime.sinopsis;
                                if (response.query_tokens && sinopsisHtml) {
                                    response.query_tokens.forEach(token => {
                                        // Regex untuk mencari kata utuh (whole word) dan case-insensitive
                                        const regex = new RegExp(`\\b(${token})\\b`,
                                            'gi');
                                        sinopsisHtml = sinopsisHtml.replace(regex,
                                            '<span class="highlight">$1</span>');
                                    });
                                }

                                const resultHtml = `
                                <div class="result-card" style="animation: fadeIn 0.5s ease ${index * 0.1}s both;">
                                    <img src="../uploads/${anime.gambar_url || 'default.jpg'}" alt="Poster ${anime.judul}">
                                    <div class="card-body">
                                        <h5 class="card-title font-weight-bold">${anime.judul}</h5>
                                        <div class="mb-2">${genreHtml}</div>
                                        <p class="sinopsis">${sinopsisHtml || 'Sinopsis tidak tersedia.'}</p>
                                        <div class="mt-auto">
                                            <span class="badge badge-success" style="font-size: 0.9rem;">
                                                <i class="fas fa-check-circle"></i> Relevansi: ${score}%
                                            </span>
                                        </div>
                                    </div>
                                </div>`;
                                resultContainer.append(resultHtml);
                            });
                        } else {
                            resultContainer.html(
                                '<div class="alert alert-secondary text-center">Tidak ada hasil yang cocok ditemukan. Coba gunakan deskripsi lain.</div>'
                            );
                        }
                    },
                    error: function(jqXHR) {
                        spinner.hide();
                        debugOutput.html(
                            `[ERROR] Terjadi kesalahan AJAX.\nStatus: ${jqXHR.status}\nRespon:\n${jqXHR.responseText}`
                        );
                        resultContainer.html(
                            '<div class="alert alert-danger text-center">Terjadi kesalahan saat memproses pencarian.</div>'
                        );
                    }
                });
            }
        });

        // CSS untuk animasi fadeIn
        const style = document.createElement('style');
        style.innerHTML =
            `@keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }`;
        document.head.appendChild(style);
    </script>
</body>

</html>