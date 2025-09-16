<?php
session_start();

// Regenerate session ID untuk mencegah session fixation
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

// Timeout sesi setelah 30 menit tidak ada aktivitas
$timeout_duration = 1800; // 1800 detik = 30 menit
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
    session_unset();
    session_destroy();
    header("Location: ../auth/index.php"); // Redirect ke halaman login
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

/**
 * Fungsi untuk memastikan user sudah login
 * @param string $role Role yang diperbolehkan (misal: 'admin', 'user')
 */
function check_login($role = null)
{
    if (!isset($_SESSION['username'])) {
        header("Location: ../auth/index.php");
        exit;
    }

    if ($role && $_SESSION['role'] !== $role) {
        header("Location: ../auth/index.php");
        exit;
    }
}
