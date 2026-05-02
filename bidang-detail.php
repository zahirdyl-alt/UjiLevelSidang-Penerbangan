<?php
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 1; 

$file = 'data/bidang.json';
$bidang_data = [];
if (file_exists($file)) {
    $bidang_data = json_decode(file_get_contents($file), true) ?? [];
}

// Find specific bidang
$current_bidang = null;
foreach($bidang_data as $b) {
    if ($b['id'] == $id) {
        $current_bidang = $b;
        break;
    }
}

// Fallback if not found in JSON
if (!$current_bidang) {
    $current_bidang = [
        'id' => $id,
        'nama' => "SEKBID $id",
        'deskripsi1' => "Bidang $id berkomitmen menjadi garda terdepan dalam memupuk rasa nasionalisme dan menjaga marwah kedisiplinan Taruna SMK Penerbangan Bogor.",
        'deskripsi2' => "Kami berfokus pada pembentukan karakter yang tangguh, setia kawan, dan memiliki wawasan kebangsaan yang luas demi masa depan dirgantara Indonesia.",
        'foto' => ''
    ];
}

// Determine Hero Image
$heroImgSrc = "https://via.placeholder.com/800x450/1e3a8a/ffffff?text=Banner+Bidang+$id";
if (!empty($current_bidang['foto'])) {
    $heroImgSrc = "uploads/bidang/" . htmlspecialchars($current_bidang['foto']);
} elseif (file_exists("assets/img/bidang_detail_$id.png")) {
    $heroImgSrc = "assets/img/bidang_detail_$id.png";
} elseif (file_exists("assets/img/bidang_detail_$id.jpg")) {
    $heroImgSrc = "assets/img/bidang_detail_$id.jpg";
} elseif (file_exists("assets/img/bidang_$id.png")) {
    $heroImgSrc = "assets/img/bidang_$id.png";
} elseif (file_exists("assets/img/bidang_$id.jpg")) {
    $heroImgSrc = "assets/img/bidang_$id.jpg";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($current_bidang['nama']) ?> - Radar Organisasi dan Aspirasi</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="light-blue-bg">

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

    <!-- Detail Section -->
    <section class="detail-page-section">
        <div class="detail-container">
            <div class="detail-hero-img">
                <?php
                    $objPosition = ""; // Default follows style.css (center 65%)
                    if ($id == 7 || $id == 10) {
                        $objPosition = "object-position: center 90%;";
                    }
                ?>
                <img src="<?= $heroImgSrc ?>" alt="Detail Bidang" style="<?= $objPosition ?>">
                <div class="detail-badge-title"><?= htmlspecialchars(strtoupper($current_bidang['nama'])) ?></div>
            </div>

            <div class="detail-content-wrap">
                <div class="pill-title">
                    <span>Tentang <?= htmlspecialchars($current_bidang['nama']) ?></span>
                </div>

                <!-- Quotes format matching the design -->
                <?php if(!empty($current_bidang['deskripsi1'])): ?>
                <div class="quote-block">
                    <p>"<?= nl2br(htmlspecialchars($current_bidang['deskripsi1'])) ?>"</p>
                </div>
                <?php endif; ?>

                <?php if(!empty($current_bidang['deskripsi2'])): ?>
                <div class="quote-block">
                    <p>"<?= nl2br(htmlspecialchars($current_bidang['deskripsi2'])) ?>"</p>
                </div>
                <?php endif; ?>
            </div>
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

