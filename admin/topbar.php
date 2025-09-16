<?php
// Ambil username dari sesi
$username = $_SESSION['username'];

// Cek apakah kolom nama ada di tabel
$checkColumns = $conn->query("SHOW COLUMNS FROM users LIKE 'nama'");
$hasNamaColumn = $checkColumns->num_rows > 0;

if ($hasNamaColumn) {
    // Jika kolom nama ada, ambil nama dan role
    $stmt = $conn->prepare("SELECT nama, role FROM users WHERE username = ?");
} else {
    // Jika kolom nama tidak ada, hanya ambil role
    $stmt = $conn->prepare("SELECT role FROM users WHERE username = ?");
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Tentukan nama atau role yang ditampilkan
if ($hasNamaColumn && !empty($user['nama'])) {
    $displayName = $user['nama'];
} else {
    $displayName = ucfirst($user['role']); // Tampilkan role jika nama tidak ada
}
?>

<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        <!-- User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><strong><?= htmlspecialchars($displayName); ?></strong></span>
                <img class="img-profile rounded-circle" src="../img/undraw_profile.svg">
            </a>

            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li>

    </ul>

</nav>