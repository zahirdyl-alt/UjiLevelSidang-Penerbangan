<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
    exit;
}

$file = '../data/PKS.json';
$upload_dir = '../uploads/PKS/';
$success_message = "";

// Ensure upload directory exists
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Function to handle image upload
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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = [];
    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true) ?? [];
    }
    
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'add') {
            $nama = $_POST['nama'] ?? '';
            $jabatan = $_POST['jabatan'] ?? '';
            $visi = $_POST['visi'] ?? '';
            $misi = $_POST['misi'] ?? '';
            $foto = handleUpload('foto');
            
            if (!empty($nama) && !empty($jabatan)) {
                $data[] = [
                    'id' => uniqid(),
                    'nama' => $nama,
                    'jabatan' => $jabatan,
                    'visi' => $visi,
                    'misi' => $misi,
                    'foto' => $foto
                ];
                file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
                $success_message = "Anggota PKS berhasil ditambahkan!";
            }
            
        } elseif ($_POST['action'] == 'edit') {
            $id = $_POST['id'] ?? '';
            $nama = $_POST['nama'] ?? '';
            $jabatan = $_POST['jabatan'] ?? '';
            $visi = $_POST['visi'] ?? '';
            $misi = $_POST['misi'] ?? '';
            
            if (!empty($id) && !empty($nama) && !empty($jabatan)) {
                foreach ($data as $key => $anggota) {
                    if ($anggota['id'] == $id) {
                        $data[$key]['nama'] = $nama;
                        $data[$key]['jabatan'] = $jabatan;
                        $data[$key]['visi'] = $visi;
                        $data[$key]['misi'] = $misi;
                        
                        // Handle new photo if uploaded
                        $new_foto = handleUpload('foto');
                        if ($new_foto) {
                            // Delete old photo
                            if (!empty($data[$key]['foto']) && file_exists($upload_dir . $data[$key]['foto'])) {
                                unlink($upload_dir . $data[$key]['foto']);
                            }
                            $data[$key]['foto'] = $new_foto;
                        }
                        
                        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
                        $success_message = "Data anggota berhasil diperbarui!";
                        break;
                    }
                }
            }
        } elseif ($_POST['action'] == 'delete') {
            $id = $_POST['id'] ?? '';
            if (!empty($id)) {
                foreach ($data as $key => $anggota) {
                    if ($anggota['id'] == $id) {
                        // Delete photo
                        if (!empty($anggota['foto']) && file_exists($upload_dir . $anggota['foto'])) {
                            unlink($upload_dir . $anggota['foto']);
                        }
                        array_splice($data, $key, 1);
                        file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
                        $success_message = "Anggota berhasil dihapus!";
                        break;
                    }
                }
            }
        }
    }
}

// Handle Update Visi Misi
$visimisi_file = '../data/visimisi.json';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_visimisi') {
    $vm_data = [];
    if (file_exists($visimisi_file)) {
        $vm_data = json_decode(file_get_contents($visimisi_file), true) ?? [];
    }
    $org = $_POST['org'] ?? 'pks';
    $vm_data[$org]['visi'] = $_POST['visi'] ?? '';
    $vm_data[$org]['misi'] = $_POST['misi'] ?? '';
    file_put_contents($visimisi_file, json_encode($vm_data, JSON_PRETTY_PRINT));
    $success_message = "Visi & Misi berhasil diperbarui!";
}

// Read Visi Misi
$vm_data = [];
if (file_exists($visimisi_file)) {
    $vm_data = json_decode(file_get_contents($visimisi_file), true) ?? [];
}
$current_visi = $vm_data['pks']['visi'] ?? '';
$current_misi = $vm_data['pks']['misi'] ?? '';

// Read data
$PKS_data = [];
if (file_exists($file)) {
    $PKS_data = json_decode(file_get_contents($file), true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota PKS - Dashboard Admin</title>
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
        .btn-delete { background: #ef4444; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; }
        .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
        
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6); display: none; justify-content: center; align-items: center;
            z-index: 1000; backdrop-filter: blur(3px);
        }
        .modal-content {
            background: white; padding: 30px; border-radius: 12px; width: 500px; max-width: 90%;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3); animation: slideIn 0.3s ease;
        }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { margin: 0; color: #333; }
        .btn-close { background: none; border: none; font-size: 1.8rem; cursor: pointer; color: #666; line-height: 1; }
        .btn-close:hover { color: #d32f2f; }
        @keyframes slideIn { from { transform: translateY(-30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        
        .avatar-thumb { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 15px; vertical-align: middle; border: 1px solid #ddd; }
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
                <li><a href="bidang.php">Edit Sekbid</a></li>
                <li><a href="osis.php">Edit Anggota [Osis]</a></li>
                <li><a href="mpk.php">Edit Anggota [Mpk]</a></li>
                <li><a href="pks.php" class="active">Edit Anggota [Pks]</a></li>
            </ul>
            <div class="logout-btn-container">
                    <a href="logout.php" class="btn-logout">LOGOUT</a>
                </div>
            </div>
        </aside>

        <main class="main-content">
            <div class="welcome-banner" style="background-color: #dc2626;">
                <h2>KELOLA ANGGOTA PKS</h2>
            </div>
            
            <?php if (!empty($success_message)): ?>
                <div class="alert-success">
                    <?= $success_message ?>
                </div>
            <?php endif; ?>



            <div class="table-container">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 class="table-title" style="margin-bottom: 0;">Daftar Anggota PKS</h3>
                    <div>
                        <button id="btnEditVisiMisi" class="btn-submit" style="background-color: #f59e0b; margin-right: 10px;"><i class="ph ph-pencil-simple"></i> Edit Visi & Misi</button>
                        <button id="btnTambah" class="btn-submit"><i class="ph ph-plus-circle"></i> Tambah Anggota</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Nama Lengkap</th>
                                <th width="30%">Jabatan</th>
                                <th width="30%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($PKS_data)): ?>
                                <tr>
                                    <td colspan="4" style="text-align: center; padding: 20px;">Belum ada anggota yang ditambahkan.</td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach($PKS_data as $anggota): 
                                    $img_src = !empty($anggota['foto']) ? '../uploads/PKS/' . htmlspecialchars($anggota['foto']) : 'https://ui-avatars.com/api/?name=' . urlencode($anggota['nama']) . '&background=dc2626&color=fff';
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <div class="flex-name">
                                            <img src="<?= $img_src ?>" alt="Avatar" class="avatar-thumb">
                                            <strong><?= htmlspecialchars($anggota['nama']) ?></strong>
                                        </div>
                                    </td>
                                    <td><span style="background: #fee2e2; color: #dc2626; padding: 4px 10px; border-radius: 20px; font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($anggota['jabatan']) ?></span></td>
                                    <td>
                                        <button class="btn-edit" onclick="openEditModal('<?= $anggota['id'] ?>', '<?= htmlspecialchars(addslashes($anggota['nama'])) ?>', '<?= htmlspecialchars(addslashes($anggota['jabatan'])) ?>', '<?= htmlspecialchars(addslashes($anggota['visi'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($anggota['misi'] ?? '')) ?>')"><i class="ph ph-pencil-simple"></i> Edit</button>
                                        <form action="PKS.php" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $anggota['id'] ?>">
                                            <button type="submit" class="btn-delete"><i class="ph ph-trash"></i> Hapus</button>
                                        </form>
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

    <!-- Modal Tambah -->
    <div id="modalTambah" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Anggota Baru</h3>
                <button id="closeModal" class="btn-close">&times;</button>
            </div>
            <form action="PKS.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" class="form-control" required placeholder="Contoh: Budi Santoso">
                </div>
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" class="form-control" required placeholder="Contoh: Ketua PKS">
                </div>
                <div class="form-group">
                    <label for="foto">Upload Foto <small>(Opsional, maks 2MB)</small></label>
                    <input type="file" id="foto" name="foto" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="visi">Visi</label>
                    <textarea id="visi" name="visi" class="form-control" rows="2" placeholder="Tuliskan Visi..."></textarea>
                </div>
                <div class="form-group">
                    <label for="misi">Misi</label>
                    <textarea id="misi" name="misi" class="form-control" rows="3" placeholder="Tuliskan Misi..."></textarea>
                </div>
                <div style="text-align: right; margin-top: 10px;">
                    <button type="submit" class="btn-submit"><i class="ph ph-check-circle"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div id="modalEdit" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Anggota</h3>
                <button id="closeModalEdit" class="btn-close">&times;</button>
            </div>
            <form action="PKS.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_id" name="id" value="">
                <div class="form-group">
                    <label for="edit_nama">Nama Lengkap</label>
                    <input type="text" id="edit_nama" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_jabatan">Jabatan</label>
                    <input type="text" id="edit_jabatan" name="jabatan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_foto">Upload Foto Baru <small>(Biarkan kosong jika tidak ingin ganti)</small></label>
                    <input type="file" id="edit_foto" name="foto" class="form-control" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="edit_visi">Visi</label>
                    <textarea id="edit_visi" name="visi" class="form-control" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_misi">Misi</label>
                    <textarea id="edit_misi" name="misi" class="form-control" rows="3"></textarea>
                </div>
                <div style="text-align: right; margin-top: 10px;">
                    <button type="submit" class="btn-submit"><i class="ph ph-check-circle"></i> Update Data</button>
                </div>
            </form>
            </form>
        </div>
    </div>

    <!-- Modal Edit Visi Misi -->
    <div id="modalVisiMisi" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Kelola Visi & Misi PKS</h3>
                <button id="closeModalVisiMisi" class="btn-close">&times;</button>
            </div>
            <form method="POST" action="">
                <input type="hidden" name="action" value="update_visimisi">
                <input type="hidden" name="org" value="pks">
                <div class="form-group">
                    <label>Visi PKS</label>
                    <textarea name="visi" class="form-control" rows="3" required><?= htmlspecialchars($current_visi) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Misi PKS (Gunakan Enter/baris baru untuk setiap poin misi)</label>
                    <textarea name="misi" class="form-control" rows="5" required><?= htmlspecialchars($current_misi) ?></textarea>
                </div>
                <div style="text-align: right; margin-top: 10px;">
                    <button type="submit" class="btn-submit"><i class="ph ph-floppy-disk" style="vertical-align: middle; margin-right: 5px;"></i> Simpan Visi & Misi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Tambah
        const modalTambah = document.getElementById('modalTambah');
        const btnOpen = document.getElementById('btnTambah');
        const btnClose = document.getElementById('closeModal');
        
        btnOpen.onclick = function() { modalTambah.style.display = 'flex'; }
        btnClose.onclick = function() { modalTambah.style.display = 'none'; }
        
        // Modal Edit
        const modalEdit = document.getElementById('modalEdit');
        const btnCloseEdit = document.getElementById('closeModalEdit');
        
        btnCloseEdit.onclick = function() { modalEdit.style.display = 'none'; }
        
        // Modal Visi Misi
        const modalVisiMisi = document.getElementById('modalVisiMisi');
        const btnOpenVisiMisi = document.getElementById('btnEditVisiMisi');
        const btnCloseVisiMisi = document.getElementById('closeModalVisiMisi');
        
        btnOpenVisiMisi.onclick = function() { modalVisiMisi.style.display = 'flex'; }
        btnCloseVisiMisi.onclick = function() { modalVisiMisi.style.display = 'none'; }
        
        window.onclick = function(event) {
            if (event.target == modalTambah) modalTambah.style.display = 'none';
            if (event.target == modalEdit) modalEdit.style.display = 'none';
            if (event.target == modalVisiMisi) modalVisiMisi.style.display = 'none';
        }

        function openEditModal(id, nama, jabatan, visi, misi) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_jabatan').value = jabatan;
            document.getElementById('edit_visi').value = visi;
            document.getElementById('edit_misi').value = misi;
            modalEdit.style.display = 'flex';
        }
    </script>
</body>
</html>





