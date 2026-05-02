<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$file = '../data/bidang.json';
$upload_dir = '../uploads/bidang/';
$success_message = "";

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

function handleUpload($file_input_name) {
    global $upload_dir;
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES[$file_input_name]['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES[$file_input_name]['tmp_name'], $upload_dir . $new_filename)) {
                return $new_filename;
            }
        }
    }
    return null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true) ?? [];
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'edit') {
        $id = (int)($_POST['id'] ?? 0);
        $nama = $_POST['nama'] ?? '';
        $deskripsi1 = $_POST['deskripsi1'] ?? '';
        $deskripsi2 = $_POST['deskripsi2'] ?? '';
        
        if ($id > 0 && $id <= 10 && !empty($nama)) {
            foreach ($data as $key => $bidang) {
                if ($bidang['id'] == $id) {
                    $data[$key]['nama'] = $nama;
                    $data[$key]['deskripsi1'] = $deskripsi1;
                    $data[$key]['deskripsi2'] = $deskripsi2;
                    
                    $new_foto = handleUpload('foto');
                    if ($new_foto) {
                        if (!empty($data[$key]['foto']) && file_exists($upload_dir . $data[$key]['foto'])) {
                            unlink($upload_dir . $data[$key]['foto']);
                        }
                        $data[$key]['foto'] = $new_foto;
                    }
                    
                    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
                    $success_message = "Data Bidang $id berhasil diperbarui!";
                    break;
                }
            }
        }
    }
}

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
    <title>Edit Bidang - Dashboard Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="stylesheet" href="css/admin.css?v=2">
    <style>
        .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; }
        .form-control { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; font-family: inherit; margin-bottom: 15px; }
        .btn-submit { background: #004085; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .btn-submit:hover { background: #003366; }
        .btn-edit { background: #f59e0b; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; margin-right: 5px; }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); display: none; justify-content: center; align-items: center;
            z-index: 1000; backdrop-filter: blur(3px);
        }
        .modal-content {
            background: white; padding: 30px; border-radius: 12px; width: 600px; max-width: 90%;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); animation: slideIn 0.3s ease;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; color: #333; }
        .btn-close { background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #666; line-height: 1; }
        .btn-close:hover { color: #d32f2f; }
        @keyframes slideIn { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .banner-thumb { width: 80px; height: 50px; border-radius: 6px; object-fit: cover; margin-right: 15px; vertical-align: middle; border: 1px solid #ddd; }
        .flex-name { display: flex; align-items: center; }
    </style>
</head>
<body>

    <div class="dashboard-wrapper">
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
                <li><a href="aspirasi.php">Aspirasi Masuk</a></li>
                <li><a href="bidang.php" class="active">Edit Sekbid</a></li>
                <li><a href="osis.php">Edit Anggota [Osis]</a></li>
                <li><a href="mpk.php">Edit Anggota [Mpk]</a></li>
                <li><a href="pks.php">Edit Anggota [Pks]</a></li>
            </ul>
            <div class="logout-btn-container">
                    <a href="logout.php" class="btn-logout">LOGOUT</a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="welcome-banner" style="background-color: #475569;">
                <h2>KELOLA KONTEN SEKBID</h2>
            </div>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert-success">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>

            <div class="table-container">
                <h3 class="table-title">Daftar Sekretariat Bidang (Sekbid)</h3>
                <p style="margin-bottom: 20px; color: #666;">Pilih Sekbid yang ingin Anda ubah isi keterangan dan foto bannernya.</p>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th width="10%">ID</th>
                                <th width="70%">Nama Sekbid & Banner</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($bidang_data as $bidang): 
                                $img_src = !empty($bidang['foto']) ? '../uploads/bidang/' . htmlspecialchars($bidang['foto']) : 'https://via.placeholder.com/80x50/475569/ffffff?text=No+Img';
                            ?>
                            <tr>
                                <td><strong><?= $bidang['id'] ?></strong></td>
                                <td>
                                    <div class="flex-name">
                                        <img src="<?= $img_src ?>" alt="Banner" class="banner-thumb">
                                        <strong><?= htmlspecialchars($bidang['nama']) ?></strong>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn-edit" onclick="openEditModal('<?= $bidang['id'] ?>', '<?= htmlspecialchars(addslashes($bidang['nama'])) ?>', '<?= htmlspecialchars(addslashes($bidang['deskripsi1'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($bidang['deskripsi2'] ?? '')) ?>')"><i class="ph ph-pencil-simple"></i> Edit Detail</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </main>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Konten Sekbid <span id="modal_id_title"></span></h3>
                <button id="closeModalEdit" class="btn-close">&times;</button>
            </div>
            <form action="bidang.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_id" name="id" value="">
                
                <div class="form-group">
                    <label for="edit_nama">Nama / Judul Sekbid</label>
                    <input type="text" id="edit_nama" name="nama" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_foto">Upload Foto Banner Baru <small>(Biarkan kosong jika tidak ingin ganti. Rekomendasi rasio 16:9)</small></label>
                    <input type="file" id="edit_foto" name="foto" class="form-control" accept="image/*">
                </div>
                
                <div class="form-group">
                    <label for="edit_deskripsi1">Paragraf Deskripsi 1</label>
                    <textarea id="edit_deskripsi1" name="deskripsi1" class="form-control" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="edit_deskripsi2">Paragraf Deskripsi 2</label>
                    <textarea id="edit_deskripsi2" name="deskripsi2" class="form-control" rows="3"></textarea>
                </div>
                
                <div style="text-align: right; margin-top: 10px;">
                    <button type="submit" class="btn-submit"><i class="ph ph-check-circle"></i> Update Konten</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modalEdit = document.getElementById('modalEdit');
        const btnCloseEdit = document.getElementById('closeModalEdit');
        
        btnCloseEdit.onclick = function() { modalEdit.style.display = 'none'; }
        
        window.onclick = function(event) {
            if (event.target == modalEdit) modalEdit.style.display = 'none';
        }

        function openEditModal(id, nama, desc1, desc2) {
            document.getElementById('modal_id_title').innerText = id;
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_deskripsi1').value = desc1;
            document.getElementById('edit_deskripsi2').value = desc2;
            modalEdit.style.display = 'flex';
        }
    </script>
</body>
</html>


