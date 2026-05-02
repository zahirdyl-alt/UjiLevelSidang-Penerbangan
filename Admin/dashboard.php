<?php
session_start();

// Simple check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$file = '../data/aspirasi.json';
$total_aspirasi = 0;
if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
    if(is_array($data)) {
        $total_aspirasi = count($data);
    }
}

$file_osis = '../data/osis.json';
$total_osis = 0;
if (file_exists($file_osis)) { $data_osis = json_decode(file_get_contents($file_osis), true); if(is_array($data_osis)) { $total_osis = count($data_osis); } }

$file_mpk = '../data/mpk.json';
$total_mpk = 0;
if (file_exists($file_mpk)) { $data_mpk = json_decode(file_get_contents($file_mpk), true); if(is_array($data_mpk)) { $total_mpk = count($data_mpk); } }

$file_pks = '../data/pks.json';
$total_pks = 0;
if (file_exists($file_pks)) { $data_pks = json_decode(file_get_contents($file_pks), true); if(is_array($data_pks)) { $total_pks = count($data_pks); } }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SMK Penerbangan</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Admin CSS -->
    <link rel="stylesheet" href="css/admin.css?v=2">
</head>
<body>

    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-title">
                    <img src="../assets/img/logo.png" alt="Logo">
                    <h3>SMK PENERBANGAN</h3>
                </div>
                <button class="mobile-menu-btn" onclick="document.getElementById('mobileNav').classList.toggle('show')">
                    <i class="ph ph-list"></i>
                </button>
            </div>
            <div id="mobileNav" class="sidebar-menu-wrapper">
            
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active">Dashboard</a></li>
                <li><a href="aspirasi.php">Aspirasi Masuk</a></li>
                <li><a href="bidang.php">Edit Sekbid</a></li>
                <li><a href="osis.php">Edit Anggota [Osis]</a></li>
                <li><a href="mpk.php">Edit Anggota [Mpk]</a></li>
                <li><a href="pks.php">Edit Anggota [Pks]</a></li>
            </ul>
            
            <div class="logout-btn-container">
                    <a href="logout.php" class="btn-logout">LOGOUT</a>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="welcome-banner">
                <h2>SELAMAT DATANG, ADMIN</h2>
            </div>
            
            <!-- Summary Stats for Aspirasi, OSIS, MPK, PKS -->
            <div class="stats-grid">
                <!-- Aspirasi Card -->
                <a href="aspirasi.php" style="text-decoration: none; color: inherit; display: block;">
                    <div class="stat-card card-aspirasi">
                        <div class="stat-icon">
                            <i class="ph ph-chat-centered-text"></i>
                        </div>
                        <div class="stat-number"><?= $total_aspirasi ?></div>
                        <div class="stat-label">Aspirasi Masuk</div>
                    </div>
                </a>

                <!-- OSIS Card -->
                <a href="osis.php" style="text-decoration: none; color: inherit; display: block;">
                    <div class="stat-card card-osis">
                        <div class="stat-icon">
                            <i class="ph ph-users-three"></i>
                        </div>
                        <div class="stat-number"><?= $total_osis ?></div>
                        <div class="stat-label">Anggota OSIS</div>
                    </div>
                </a>

                <!-- MPK Card -->
                <a href="mpk.php" style="text-decoration: none; color: inherit; display: block;">
                    <div class="stat-card card-mpk">
                        <div class="stat-icon">
                            <i class="ph ph-user-list"></i>
                        </div>
                        <div class="stat-number"><?= $total_mpk ?></div>
                        <div class="stat-label">Anggota MPK</div>
                    </div>
                </a>

                <!-- PKS Card -->
                <a href="pks.php" style="text-decoration: none; color: inherit; display: block;">
                    <div class="stat-card card-pks">
                        <div class="stat-icon">
                            <i class="ph ph-shield-check"></i>
                        </div>
                        <div class="stat-number"><?= $total_pks ?></div>
                        <div class="stat-label">Anggota PKS</div>
                    </div>
                </a>
            </div>
            
            <!-- Tambahan konten untuk mempercantik -->
            <div style="margin-top: 40px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 15px; color: #333;">Aktivitas Terbaru</h3>
                <p style="color: #666;">Belum ada aktivitas terbaru hari ini. Pantau terus aspirasi dari siswa-siswi SMK Penerbangan.</p>
            </div>
            
        </main>
    </div>

</body>
</html>




