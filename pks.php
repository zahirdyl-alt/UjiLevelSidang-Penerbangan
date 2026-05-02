<?php
// Baca data JSON
$file = 'data/PKS.json';
$PKS_data = [];
if (file_exists($file)) {
    $json = file_get_contents($file);
    $PKS_data = json_decode($json, true) ?? [];
}

// Read Visi Misi
$visimisi_file = 'data/visimisi.json';
$vm_data = [];
if (file_exists($visimisi_file)) {
    $vm_data = json_decode(file_get_contents($visimisi_file), true) ?? [];
}
$visi = $vm_data['pks']['visi'] ?? "Menjadikan Patroli Keamanan Sekolah (PKS) sebagai pelopor kedisiplinan, ketertiban, dan keamanan yang tangguh di lingkungan SMK Penerbangan Bogor.";
$misi = $vm_data['pks']['misi'] ?? "Menegakkan setiap peraturan dan tata tertib sekolah dengan sikap yang tegas, disiplin, dan tetap humanis.\nMenjaga keamanan, ketertiban, dan kelancaran setiap kegiatan yang diselenggarakan oleh sekolah maupun organisasi.\nMenjadi contoh dan teladan yang baik dalam sikap serta perilaku disiplin bagi seluruh siswa lainnya.";
$misi_array = explode("\n", $misi);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anggota PKS - Radar Organisasi dan Aspirasi</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .PKS-section { padding: 100px 5%; background-color: #f8fafc; min-height: 70vh; }
        .PKS-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; margin-top: 40px; }
        .PKS-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s; }
        .PKS-card:hover { transform: translateY(-5px); }
        .PKS-avatar { width: 100px; height: 100px; border-radius: 50%; margin: 30px auto 15px; object-fit: cover; border: 3px solid #fee2e2; }
        .PKS-info { padding: 0 20px 30px; }
        .PKS-name { font-size: 1.2rem; font-weight: 700; color: #1e293b; margin-bottom: 5px; }
        .PKS-role { font-size: 0.9rem; font-weight: 600; color: #dc2626; background: #fee2e2; display: inline-block; padding: 5px 12px; border-radius: 20px; margin-bottom: 15px; }
        .PKS-vm { text-align: left; padding-top: 15px; border-top: 1px solid #f1f5f9; font-size: 0.85rem; color: #64748b; }
        .PKS-vm strong { color: #334155; }
        .PKS-vm p { margin: 5px 0 10px; line-height: 1.4; }
    </style>
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
                <a href="bidang.php" class="nav-item">Bidang</a>
                <a href="aspirasi.php" class="nav-item">Aspirasi</a>
                
                <div class="dropdown">
                    <button class="hamburger-btn active" id="hamburger-btn">
                        <i class="ph ph-list"></i>
                    </button>
                    <div class="dropdown-content" id="dropdown-menu">
                        <a href="osis.php">OSIS</a>
                        <a href="mpk.php">MPK</a>
                        <a href="pks.php" class="active">PKS</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- PKS Section -->
    <section class="PKS-section">
        <div class="section-title">
            <h2>Struktur Organisasi PKS</h2>
            <div class="title-underline"></div>
            <p style="text-align:center; color: var(--text-muted); margin-top: 1rem;">
                Daftar pengurus dan anggota PKS SMK Penerbangan Bogor.
            </p>
        </div>

        <?php if(empty($PKS_data)): ?>
            <div style="text-align: center; margin-top: 50px; color: #64748b; font-size: 1.1rem;">
                <i class="ph ph-users-three" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 10px;"></i><br>
                Belum ada anggota PKS yang ditambahkan.
            </div>
        <?php else: ?>
            <div class="PKS-grid">
                <?php foreach($PKS_data as $anggota): ?>
                <div class="PKS-card">
                    <?php 
                    $img_src = !empty($anggota['foto']) ? 'uploads/PKS/' . htmlspecialchars($anggota['foto']) : 'https://ui-avatars.com/api/?name=' . urlencode($anggota['nama']) . '&background=dc2626&color=fff&size=150';
                    ?>
                    <img src="<?= $img_src ?>" alt="Avatar" class="PKS-avatar">
                    <div class="PKS-info">
                        <div class="PKS-name"><?= htmlspecialchars($anggota['nama']) ?></div>
                        <div class="PKS-role"><?= htmlspecialchars($anggota['jabatan']) ?></div>
                        
                        <?php if (!empty($anggota['visi']) || !empty($anggota['misi'])): ?>
                        <div class="PKS-vm">
                            <?php if (!empty($anggota['visi'])): ?>
                                <strong>Visi:</strong>
                                <p><?= nl2br(htmlspecialchars($anggota['visi'])) ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($anggota['misi'])): ?>
                                <strong>Misi:</strong>
                                <p><?= nl2br(htmlspecialchars($anggota['misi'])) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Visi Misi PKS Section -->
    <section class="visi-misi-section" style="background-color: var(--white); margin-top: 0; padding-top: 2rem;">
        <div class="section-title">
            <h2>Visi & Misi PKS</h2>
            <div class="title-underline"></div>
        </div>
        
        <div class="vm-container">
            <!-- Visi Card -->
            <div class="vm-card visi-card">
                <div class="vm-icon">
                    <i class="ph ph-eye"></i>
                </div>
                <h3>Visi</h3>
                <p><?= nl2br(htmlspecialchars($visi)) ?></p>
            </div>

            <!-- Misi Card -->
            <div class="vm-card misi-card">
                <div class="vm-icon">
                    <i class="ph ph-target"></i>
                </div>
                <h3>Misi</h3>
                <ul>
                    <?php foreach ($misi_array as $m): ?>
                        <?php if (trim($m) !== ''): ?>
                            <li><?= htmlspecialchars(trim($m)) ?></li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
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
                    <li><a href="pks.php" class="active">PKS</a></li>
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
            <p>&copy; 2026 PKS SMK Penerbangan Bogor. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>





