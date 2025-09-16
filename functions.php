<?php
date_default_timezone_set('Asia/Makassar');

$conn = mysqli_connect("localhost", "root", "", "db_vsm_anime");

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

function query($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error dalam query: " . mysqli_error($conn));
    }

    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

$judul = "Anime Finder System";
