<?php
include "../init.php";
include "../functions.php";
include "../preprocessing.php";
check_login('admin');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "link.php"; ?>
    <title>Dashboard Admin</title>
</head>

<?php
// Pastikan koneksi $conn sudah ada
if (isset($conn)) {
    // Ambil role pengguna dari sesi untuk ditampilkan
    $username = $_SESSION['username'] ?? 'Admin';
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $displayName = isset($user['role']) ? ucfirst($user['role']) : 'Admin';

    // --- PENGAMBILAN DATA UNTUK KARTU STATISTIK ---

    // 1. Menghitung total data anime
    $totalAnimeResult = $conn->query("SELECT COUNT(id_anime) AS total FROM anime");
    $totalAnime = $totalAnimeResult->fetch_assoc()['total'];

    // 2. Menghitung total kata unik di kamus
    $totalKataResult = $conn->query("SELECT COUNT(id_kata) AS total FROM kamus_kata");
    $totalKata = $totalKataResult->fetch_assoc()['total'];

    // 3. Query untuk mendapatkan tanggal data anime terbaru (DIKEMBALIKAN)
    $terbaruResult = $conn->query("SELECT MAX(created_at) AS data_terbaru FROM anime");
    $dataTerbaru = $terbaruResult->fetch_assoc()['data_terbaru'];
    // Format tanggal agar lebih mudah dibaca, dengan fallback jika belum ada data
    $tanggalTerbaru = !empty($dataTerbaru) ? date('d M Y, H:i', strtotime($dataTerbaru)) : 'Belum ada data';


    // --- PENGOLAHAN DATA UNTUK GRAFIK GENRE ---
    $genreQuery = $conn->query("SELECT genre FROM anime WHERE genre IS NOT NULL AND genre != ''");
    $genreCounts = [];
    if ($genreQuery->num_rows > 0) {
        while ($row = $genreQuery->fetch_assoc()) {
            $genres = explode(',', $row['genre']);
            foreach ($genres as $g) {
                $trimmedGenre = trim($g);
                if (!empty($trimmedGenre)) {
                    $genreCounts[$trimmedGenre] = ($genreCounts[$trimmedGenre] ?? 0) + 1;
                }
            }
        }
    }
    arsort($genreCounts);
    $topGenres = array_slice($genreCounts, 0, 10);
    $chartGenreLabels = json_encode(array_keys($topGenres));
    $chartGenreData = json_encode(array_values($topGenres));
} else {
    // Fallback jika koneksi gagal
    $totalAnime = $totalKata = $totalPengguna = 0;
    $tanggalTerbaru = 'Koneksi Gagal';
    $displayName = 'Admin';
    $chartGenreLabels = json_encode([]);
    $chartGenreData = json_encode([]);
}
?>

<body id="page-top">

    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Selamat Datang, <span
                                style="text-transform: capitalize;"><?= htmlspecialchars($displayName); ?></span></h1>
                    </div>

                    <div class="row">
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total
                                                Data Anime</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalAnime; ?>
                                                Dokumen</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-film fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Kata
                                                Unik di Kamus</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $totalKata; ?> Kata
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-book fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Pembaruan Terakhir</div>
                                            <div class="h6 mb-0 font-weight-bold text-gray-800"><?= $tanggalTerbaru; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-sync-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Distribusi Genre Anime</h6>
                                </div>
                                <div class="card-body">
                                    <div id="genreChart" style="height: 400px;"></div>
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

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const genreLabels = <?= $chartGenreLabels; ?>;
            const genreData = <?= $chartGenreData; ?>;
            const chartDataFormatted = genreLabels.map((label, i) => ({
                name: label,
                y: genreData[i]
            }));

            Highcharts.chart('genreChart', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    }
                },
                title: {
                    text: 'Persentase Anime Berdasarkan 10 Genre Teratas'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b> ({point.y} anime)'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Jumlah',
                    colorByPoint: true,
                    data: chartDataFormatted
                }]
            });
        });
    </script>
</body>

</html>