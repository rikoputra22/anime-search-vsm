<?php
include "../init.php";
include "../functions.php";
include "../preprocessing.php";

check_login('admin');

// --- FUNGSI BANTUAN ---
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

function handleImageUpload($fileData, $oldImageName = '')
{
    if (!isset($fileData) || $fileData['error'] === UPLOAD_ERR_NO_FILE) {
        return $oldImageName;
    }

    $targetDir = "../uploads/";
    $fileName = uniqid() . '_' . basename($fileData["name"]);
    $targetFile = $targetDir . $fileName;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (!getimagesize($fileData["tmp_name"])) return false;
    if ($fileData["size"] > 2097152) return false;
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) return false;

    if (move_uploaded_file($fileData["tmp_name"], $targetFile)) {
        if (!empty($oldImageName) && file_exists($targetDir . $oldImageName)) {
            unlink($targetDir . $oldImageName);
        }
        return $fileName;
    }
    return false;
}

// --- LOGIKA UTAMA (CRUD) ---
$script = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($conn)) {

    // --- PROSES TAMBAH (CREATE) ---
    if (isset($_POST['tambah_anime'])) {
        $judul = trim($_POST['judul']);
        $sinopsis = trim($_POST['sinopsis']);
        $genre = trim($_POST['genre']);

        if (empty($judul) || empty($sinopsis) || empty($genre)) {
            $script = showAlert('error', 'Form Tidak Lengkap', 'Judul, Sinopsis, dan Genre wajib diisi.');
        } else {
            $stmt = $conn->prepare("SELECT id_anime FROM anime WHERE judul = ?");
            $stmt->bind_param("s", $judul);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $script = showAlert('error', 'Gagal Menambah', 'Judul anime sudah ada di database.');
            } else {
                // PERBAIKAN: Menggunakan $_FILES['gambar_url']
                $imageName = handleImageUpload($_FILES['gambar_url']);
                if ($imageName === false) {
                    $script = showAlert('error', 'Upload Gagal', 'File harus berupa gambar (JPG, PNG) & ukuran < 2MB.');
                } else {
                    $stmt_insert = $conn->prepare("INSERT INTO anime (judul, sinopsis, genre, gambar_url) VALUES (?, ?, ?, ?)");
                    $stmt_insert->bind_param("ssss", $judul, $sinopsis, $genre, $imageName);
                    if ($stmt_insert->execute()) {
                        $script = showAlert('success', 'Berhasil!', 'Data anime baru telah ditambahkan.');
                    } else {
                        $script = showAlert('error', 'Database Error', $conn->error);
                    }
                    $stmt_insert->close();
                }
            }
            $stmt->close();
        }
    }

    // --- PROSES EDIT (UPDATE) ---
    if (isset($_POST['edit_anime'])) {
        $id_anime = (int)$_POST['id_anime'];
        $judul = trim($_POST['judul']);
        $sinopsis = trim($_POST['sinopsis']);
        $genre = trim($_POST['genre']);
        $gambar_lama = $_POST['gambar_lama'];

        if (empty($judul) || empty($sinopsis) || empty($genre) || $id_anime <= 0) {
            $script = showAlert('error', 'Form Tidak Lengkap', 'Semua kolom wajib diisi.');
        } else {
            // PERBAIKAN: Menggunakan $_FILES['gambar_url']
            $imageName = handleImageUpload($_FILES['gambar_url'], $gambar_lama);
            if ($imageName === false) {
                $script = showAlert('error', 'Upload Gagal', 'File harus berupa gambar (JPG, PNG) & ukuran < 2MB.');
            } else {
                $stmt = $conn->prepare("UPDATE anime SET judul = ?, sinopsis = ?, genre = ?, gambar_url = ? WHERE id_anime = ?");
                $stmt->bind_param("ssssi", $judul, $sinopsis, $genre, $imageName, $id_anime);
                if ($stmt->execute()) {
                    $script = showAlert('success', 'Berhasil!', 'Data anime telah diperbarui.');
                } else {
                    $script = showAlert('error', 'Database Error', $conn->error);
                }
                $stmt->close();
            }
        }
    }

    // --- PROSES HAPUS (DELETE) ---
    if (isset($_POST['hapus_anime'])) {
        $id_anime = (int)$_POST['id_anime'];
        if ($id_anime > 0) {
            $stmt = $conn->prepare("SELECT gambar_url FROM anime WHERE id_anime = ?");
            $stmt->bind_param("i", $id_anime);
            $stmt->execute();
            $result = $stmt->get_result();
            $gambar_url = ($result->num_rows > 0) ? $result->fetch_assoc()['gambar_url'] : '';
            $stmt->close();

            $stmt_delete = $conn->prepare("DELETE FROM anime WHERE id_anime = ?");
            $stmt_delete->bind_param("i", $id_anime);
            if ($stmt_delete->execute()) {
                if (!empty($gambar_url) && file_exists('../uploads/' . $gambar_url)) {
                    unlink('../uploads/' . $gambar_url);
                }
                $script = showAlert('success', 'Berhasil Dihapus!', 'Data anime telah dihapus.');
            } else {
                $script = showAlert('error', 'Gagal Menghapus', $conn->error);
            }
            $stmt_delete->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <?php include "link.php"; ?>
    <title>Manajemen Data Anime</title>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include "sidebar.php"; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include "topbar.php"; ?>
                <div class="container-fluid">

                    <div class="mb-3">
                        <a class="btn btn-primary" data-toggle="collapse" href="#collapseTambah" role="button"
                            aria-expanded="false" aria-controls="collapseTambah">
                            <i class="fas fa-plus-square"></i> Tambah Data Anime
                        </a>
                        <div class="collapse" id="collapseTambah">
                            <div class="card card-body mt-3">
                                <form method="POST" action="" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label for="judul">Judul Anime:</label>
                                        <input type="text" class="form-control" id="judul" name="judul" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="genre">Genre:</label>
                                        <input type="text" class="form-control" id="genre" name="genre"
                                            placeholder="Contoh: Action, Fantasy, Drama" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sinopsis">Sinopsis:</label>
                                        <textarea class="form-control" id="sinopsis" name="sinopsis" rows="4"
                                            required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="gambar_url">Poster Anime (JPG, PNG, maks 2MB):</label>
                                        <input type="file" class="form-control-file" id="gambar_url" name="gambar_url"
                                            accept="image/*" required>
                                    </div>
                                    <button type="submit" name="tambah_anime" class="btn btn-primary w-100">Tambah
                                        Anime</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Manajemen Data Anime</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataX" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Poster</th>
                                            <th>Judul</th>
                                            <th>Genre</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (isset($conn)) {
                                            $result = $conn->query("SELECT * FROM anime ORDER BY id_anime DESC");
                                            $i = 1;
                                            while ($row = $result->fetch_assoc()) :
                                        ?>
                                                <tr>
                                                    <td><?= $i++; ?></td>
                                                    <td>
                                                        <img src="../uploads/<?= htmlspecialchars($row['gambar_url']); ?>"
                                                            alt="Poster" width="60" style="object-fit: cover; height: 80px;">
                                                    </td>
                                                    <td><?= htmlspecialchars($row['judul']); ?></td>
                                                    <td><?= htmlspecialchars($row['genre']); ?></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                            data-target="#editModal<?= $row['id_anime'] ?>">Edit</button>
                                                        <button class="btn btn-sm btn-danger" data-toggle="modal"
                                                            data-target="#hapusModal<?= $row['id_anime'] ?>">Hapus</button>
                                                    </td>
                                                </tr>

                                                <div class="modal fade" id="editModal<?= $row['id_anime']; ?>" tabindex="-1">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Anime:
                                                                    <?= htmlspecialchars($row['judul']); ?></h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal"><span>&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="" method="POST" enctype="multipart/form-data">
                                                                    <input type="hidden" name="id_anime"
                                                                        value="<?= $row['id_anime']; ?>">
                                                                    <input type="hidden" name="gambar_lama"
                                                                        value="<?= htmlspecialchars($row['gambar_url']); ?>">
                                                                    <div class="form-group">
                                                                        <label>Judul Anime:</label>
                                                                        <input type="text" class="form-control" name="judul"
                                                                            value="<?= htmlspecialchars($row['judul']); ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Genre:</label>
                                                                        <input type="text" class="form-control" name="genre"
                                                                            value="<?= htmlspecialchars($row['genre']); ?>"
                                                                            required>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Sinopsis:</label>
                                                                        <textarea class="form-control" name="sinopsis" rows="4"
                                                                            required><?= htmlspecialchars($row['sinopsis']); ?></textarea>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label>Poster Saat Ini:</label><br>
                                                                        <img src="../uploads/<?= htmlspecialchars($row['gambar_url']); ?>"
                                                                            width="100" class="mb-2">
                                                                        <br><label>Ganti Poster (opsional):</label>
                                                                        <input type="file" class="form-control-file"
                                                                            name="gambar_url" accept="image/*">
                                                                    </div>
                                                                    <button type="submit" name="edit_anime"
                                                                        class="btn btn-primary w-100">Simpan Perubahan</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal fade" id="hapusModal<?= $row['id_anime'] ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Konfirmasi Hapus</h5>
                                                                <button class="close" type="button"
                                                                    data-dismiss="modal"><span>&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda yakin ingin menghapus anime
                                                                <b><?= htmlspecialchars($row['judul']) ?></b>? Tindakan ini
                                                                tidak dapat dibatalkan.
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" type="button"
                                                                    data-dismiss="modal">Batal</button>
                                                                <form action="" method="POST" style="display: inline;">
                                                                    <input type="hidden" name="id_anime"
                                                                        value="<?= $row['id_anime'] ?>">
                                                                    <button type="submit" name="hapus_anime"
                                                                        class="btn btn-danger">Ya, Hapus</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                            endwhile;
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
            $('#dataX').DataTable({
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