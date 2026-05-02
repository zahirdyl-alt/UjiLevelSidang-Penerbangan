<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

// Baca data JSON
$file = '../data/aspirasi.json';
$aspirasi_data = [];
if (file_exists($file)) {
    $json = file_get_contents($file);
    $aspirasi_data = json_decode($json, true) ?? [];
}
// Handle Delete
$success_message = "";
if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    if (isset($aspirasi_data[$index])) {
        unset($aspirasi_data[$index]);
        $aspirasi_data = array_values($aspirasi_data); // re-index array
        file_put_contents($file, json_encode($aspirasi_data, JSON_PRETTY_PRINT));
        $success_message = "Pesan aspirasi berhasil dihapus.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aspirasi Siswa - Dashboard Admin</title>
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
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="aspirasi.php" class="active">Aspirasi Masuk</a></li>
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
            
            <?php if (!empty($success_message)): ?>
                <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>

            <!-- Table Section -->
            <div class="table-container">
                <h3 class="table-title">Daftar Pesan Masuk</h3>
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="20%">Nama Pengirim</th>
                                <th width="10%">Tujuan</th>
                                <th width="35%">Isi Aspirasi</th>
                                <th width="20%">Waktu</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($aspirasi_data)): ?>
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 20px;">Belum ada pesan masuk.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($aspirasi_data as $index => $pesan): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><strong><?= htmlspecialchars($pesan['nama']) ?></strong></td>
                                    <td><span class="badge-tujuan badge-<?= strtolower(htmlspecialchars($pesan['tujuan'])) ?>"><?= htmlspecialchars($pesan['tujuan']) ?></span></td>
                                    <td><?= nl2br(htmlspecialchars($pesan['isi'])) ?></td>
                                    <td class="time-col"><?= htmlspecialchars($pesan['waktu'] ?? $pesan['tanggal'] ?? '') ?></td>
                                    <td>
                                        <a href="?delete=<?= $index ?>" onclick="return confirm('Yakin ingin menghapus aspirasi ini?');" style="color: #dc3545; font-size: 1.2rem;" title="Hapus"><i class="ph ph-trash"></i></a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </main>
    </div>

</body>
</html>





