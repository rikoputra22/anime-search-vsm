<?php
session_start();
include "../functions.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Menghindari XSS dengan htmlspecialchars -->
    <title><?= htmlspecialchars($judul); ?></title>
    <link rel="icon" href="../img/logo.png" type="image/x-icon">
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/fontawesome-free/css/fontawesome.css">
    <script src="../vendor/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="../vendor/sweetalert2/dist/sweetalert2.min.css">
</head>

<body>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];

        // Gunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                // Simpan data user ke session
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $row['role'];
                $_SESSION['user_id'] = $row['id'];

                // Redirect berdasarkan role
                $redirectUrl = ($row['role'] == 'admin') ? '../admin' : '../user';

                // Tampilkan alert dan redirect
                echo "
                <script src='../vendor/sweetalert2/dist/sweetalert2.all.min.js'></script>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Login Berhasil!',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        toast: true
                    }).then(() => {
                        window.location.href = '$redirectUrl';
                    });
                </script>
            ";
                exit;
            } else {
                // Password salah
                echo "
                <script src='../vendor/sweetalert2/dist/sweetalert2.all.min.js'></script>
                <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Password Salah!',
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        toast: true
                    });
                </script>
            ";
            }
        } else {
            // Username tidak ditemukan
            echo "
            <script src='../vendor/sweetalert2/dist/sweetalert2.all.min.js'></script>
            <script>
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Username Tidak Ditemukan!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    toast: true
                });
            </script>
        ";
        }
        $stmt->close();
    }
    ?>

    <section class=" py-3 py-md-5 mt-5">
        <div class="container">
            <center>
                <h3 class="text-dark"><?= $judul; ?></h3>
            </center>
            <br>
            <div class="row justify-content-center">

                <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
                    <div class="card border border-light-subtle rounded-3 shadow-sm">
                        <div class="card-body p-3 p-md-4 p-xl-5">

                            <form action="" method="post">
                                <div class="row gy-2 overflow-hidden">
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <label for="username" class="form-label"
                                                style="color: black;">Username</label>
                                            <input type="text" class="form-control" name="username" id="username"
                                                placeholder="Username" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <label for="password" class="form-label"
                                                style="color: black;">Password</label>
                                            <input type="password" class="form-control" name="password" id="password"
                                                placeholder="Password" required>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid my-3">
                                            <button class="btn btn-primary btn-sm w-100" type="submit"
                                                name="login">Masuk</button>
                                        </div>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <?php include "footer.php"; ?>
    <?php include "plugin.php"; ?>

</body>

</html>