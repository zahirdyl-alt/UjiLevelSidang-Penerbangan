<?php
session_start();
$aspirasi_file = 'data/aspirasi.json';
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pengirim = $_POST['pengirim'] ?? '';
    $email = $_POST['email'] ?? '';
    $telepon = $_POST['telepon'] ?? '';
    $tujuan = $_POST['kategori_pengirim'] ?? ''; // OSIS, MPK, PKS
    $kategori = $_POST['kategori'] ?? ''; // Kritik, Saran, Lainnya
    $judul = $_POST['judul'] ?? '';
    $isi = $_POST['isi'] ?? '';
    
    // Server side verification for the fake recaptcha (just checking if it's sent)
    $robot = $_POST['robot'] ?? '';

    if (!empty($pengirim) && !empty($tujuan) && !empty($isi) && $robot === "on") {
        $aspirasi_data = [];
        if (file_exists($aspirasi_file)) {
            $aspirasi_data = json_decode(file_get_contents($aspirasi_file), true) ?? [];
        }

        $ids = array_column($aspirasi_data, 'id');
        $new_id = empty($ids) ? count($aspirasi_data) + 1 : max($ids) + 1;
        
        $new_aspirasi = [
            'id' => $new_id,
            'nama' => $pengirim,
            'email' => $email,
            'telepon' => $telepon,
            'tujuan' => $tujuan,
            'kategori' => $kategori,
            'judul' => $judul,
            'isi' => $isi,
            'tanggal' => date('Y-m-d H:i:s'),
            'status' => 'pending' // pending, dibaca, diproses, selesai
        ];

        $aspirasi_data[] = $new_aspirasi;
        file_put_contents($aspirasi_file, json_encode($aspirasi_data, JSON_PRETTY_PRINT));
        
        $success_message = "Terima kasih! Aspirasi Anda telah berhasil dikirim ke $tujuan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aspirasi - Radar Organisasi dan Aspirasi</title>
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
                <a href="bidang.php" class="nav-item">Bidang</a>
                <a href="aspirasi.php" class="nav-item active">Aspirasi</a>
                
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

    <!-- Form Aspirasi Section -->
    <section class="aspirasi-form-section">
        <div class="aspirasi-form-container">
            
            <div class="aspirasi-banner">
                <h2>FORMULIR ASPIRASI SISWA</h2>
            </div>
            
            <!-- Form Card -->
            <div class="aspirasi-box">
                <h3 class="form-title">Kirim Aspirasi Mu Disini</h3>
                
                <?php if (!empty($success_message)): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center; border: 1px solid #c3e6cb; font-weight: 600;">
                        <?= $success_message ?>
                    </div>
                <?php endif; ?>
                
                <form action="aspirasi.php" method="POST" class="form-content">
                    <div class="input-group">
                        <label for="pengirim">Pengirim</label>
                        <input type="text" id="pengirim" name="pengirim" placeholder="Isi Nama Anda" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Email (misal: mail@google.com)" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="telepon">Telepon</label>
                        <input type="tel" id="telepon" name="telepon" placeholder="No Telepon / Hp yang aktif" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="kategori_pengirim">Kategori Pengirim (Tujuan)</label>
                        <div class="select-wrapper">
                            <select id="kategori_pengirim" name="kategori_pengirim" required>
                                <option value="" disabled selected>Pilih Tujuan Organisasi</option>
                                <option value="OSIS">OSIS</option>
                                <option value="MPK">MPK</option>
                                <option value="PKS">PKS</option>
                            </select>
                            <i class="ph ph-caret-down select-icon"></i>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="kategori">Kategori</label>
                        <div class="select-wrapper">
                            <select id="kategori" name="kategori" required>
                                <option value="" disabled selected>Pilih Kategori Aspirasi</option>
                                <option value="Kritik">Kritik</option>
                                <option value="Saran">Saran</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                            <i class="ph ph-caret-down select-icon"></i>
                        </div>
                    </div>
                    
                    <div class="input-group">
                        <label for="judul">Judul</label>
                        <input type="text" id="judul" name="judul" placeholder="Judul Keluhan / Aspirasi Anda" required>
                    </div>
                    
                    <div class="input-group">
                        <label for="isi">Isi</label>
                        <textarea id="isi" name="isi" rows="6" placeholder="Ketik isi aspirasi Anda secara mendetail..." required></textarea>
                    </div>
                    
                    <!-- Fake ReCaptcha -->
                    <div class="recaptcha-box" id="recaptcha-widget">
                        <div class="checkbox-wrapper">
                            <div class="rc-checkbox" id="rc-checkbox">
                                <div class="rc-spinner" id="rc-spinner"></div>
                                <div class="rc-checkmark" id="rc-checkmark">✔</div>
                            </div>
                            <span class="rc-label">Saya bukan robot</span>
                        </div>
                        <div class="rc-logo-col">
                            <img src="https://www.gstatic.com/recaptcha/api2/logo_48.png" alt="reCAPTCHA logo" class="recaptcha-logo">
                            <span class="rc-text">reCAPTCHA</span>
                            <span class="rc-privacy">Privacy - Terms</span>
                        </div>
                        <!-- Hidden input for form validation -->
                        <input type="checkbox" id="robot" name="robot" required style="opacity: 0; position: absolute; bottom: 0; left: 0; width: 1px; height: 1px; pointer-events: none;">
                    </div>
                    
                    <button type="submit" class="submit-btn">KIRIM</button>
                </form>
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

