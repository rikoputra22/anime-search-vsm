<?php
// Mulai sesi hanya jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Hapus semua variabel sesi
session_unset();
$_SESSION = array();

// Hapus cookie sesi jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hancurkan sesi
session_destroy();

// Hindari cache pada halaman setelah logout
header("Cache-Control: no-cache, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0

// Redirect ke halaman login
header("Location: auth/");
exit;
