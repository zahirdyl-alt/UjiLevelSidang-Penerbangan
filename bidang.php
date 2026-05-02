<?php
session_start();
$file = 'data/bidang.json';
$bidang_data = [];
if (file_exists($file)) {
    $bidang_data = json_decode(file_get_contents($file), true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bidang - Radar Organisasi dan Aspirasi</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar solid-navbar">
        <div class="nav-container">
            <div class="logo-container">
                <img src="assets/img/logo.png?v=2" alt="Logo SMK Penerbangan" class="nav-logo">
            </div>
            
            <div class="nav-links">
                <a href="index.php" class="nav-item">Home</a>
                <a href="bidang.php" class="nav-item active">Bidang</a>
                <a href="aspirasi.php" class="nav-item">Aspirasi</a>
                
                <div class="dropdown">
                    <button class="hamburger-btn" id="hamburger-btn">
                        <i class="ph ph-list"></i>
                    </button>
                    <div class="dropdown-content" id="dropdown-menu">
                        <a href="osis.php">OSIS</a>
                        <a href="mpk.php">MPK</a>
                        <a href="pks.php">PKS</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Bidang Grid Section -->
    <section class="bidang-page-section">
        <div class="section-title" style="margin-top: 80px;">
            <h2>Menu Bidang Organisasi</h2>
            <div class="title-underline"></div>
            <p style="text-align:center; color: var(--text-muted); margin-top: 1rem;">
                Silakan pilih bidang di bawah ini untuk melihat detail lebih lanjut.
            </p>
        </div>

        <div class="bidang-grid">
            <?php 
            // Fallback if data is missing, we create 10 generic ones
            if (empty($bidang_data)) {
                for($i = 1; $i <= 10; $i++) {
                    $bidang_data[] = [
                        'id' => $i,
                        'nama' => "SEKBID $i",
                        'foto' => ''
                    ];
                }
            }
            
            foreach($bidang_data as $bidang): 
                $i = $bidang['id'];
                
                // Fallback image logic
                $imgSrc = "https://via.placeholder.com/400x300/1e3a8a/ffffff?text=Foto+Bidang+$i";
                if (!empty($bidang['foto'])) {
                    $imgSrc = "uploads/bidang/" . htmlspecialchars($bidang['foto']);
                } elseif (file_exists("assets/img/bidang_$i.png")) {
                    $imgSrc = "assets/img/bidang_$i.png";
                } elseif (file_exists("assets/img/bidang_$i.jpg")) {
                    $imgSrc = "assets/img/bidang_$i.jpg";
                } elseif (file_exists("assets/img/bidang_$i.jpeg")) {
                    $imgSrc = "assets/img/bidang_$i.jpeg";
                }
            ?>
            <a href="bidang-detail.php?id=<?= $i ?>" class="bidang-card" style="display:block;">
                <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($bidang['nama']) ?>" class="bidang-img">
                <div class="bidang-overlay">
                    <h3 style="font-size: 1.1rem; padding: 0 10px;"><?= htmlspecialchars($bidang['nama']) ?></h3>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-left">
                <div class="brand">
                    <img src="assets/img/logo.png?v=2" alt="Logo">
                    <h3>SMK PENERBANGAN</h3>
                </div>
                <p class="footer-desc">
                    Membentuk tenaga ahli dirgantara yang disiplin, tangguh, dan profesional. 
                    Wadah aspirasi dan kolaborasi siswa.
                </p>
            </div>
            
            <div class="footer-center">
                <h4>Menu Navigasi</h4>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="bidang.php">Bidang</a></li>
                    <li><a href="aspirasi.php">Aspirasi</a></li>
                    <li><a href="osis.php">OSIS</a></li>
                    <li><a href="mpk.php">MPK</a></li>
                    <li><a href="pks.php">PKS</a></li>
                </ul>
            </div>
            
            <div class="footer-right">
                <h4>Hubungi Kami</h4>
                <div class="contact-item">
                    <i class="ph ph-map-pin"></i>
                    <p>Jl. Raya Semplak Km. 9, Atang Sendjaja, Kec. Kemang, Kabupaten Bogor, Jawa Barat 16310</p>
                </div>
                <div class="contact-item">
                    <i class="ph ph-phone"></i>
                    <p>(0251) 753 6649</p>
                </div>
                
                <div style="margin-top: 2rem;">
                    <a href="admin/" class="admin-login-btn"><i class="ph ph-shield"></i> Login Admin</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 OSIS SMK Penerbangan Bogor. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>

