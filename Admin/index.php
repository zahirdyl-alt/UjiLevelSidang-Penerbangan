<?php
session_start();

// Simple dummy login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'adminpenerbangan' && $password === 'admin12345') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SMK Penerbangan</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Admin CSS -->
    <link rel="stylesheet" href="css/admin.css">
</head>
<body class="login-body">

    <div class="login-card">
        <!-- Left Side: Branding -->
        <div class="login-left">
            <img src="../assets/img/logo.png" alt="Logo SMK Penerbangan">
            <h2>SMK PENERBANGAN</h2>
        </div>

        <!-- Right Side: Login Form -->
        <div class="login-right">
            <h3>Selamat Datang Admin</h3>
            
            <?php if (isset($error)): ?>
                <div style="color: red; text-align: center; margin-bottom: 15px; font-size: 0.9rem;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <button type="submit" class="btn-login">Login</button>
            </form>
            
            <a href="../index.php" class="back-link">Kembali Ke Layar Utama</a>
        </div>
    </div>

</body>
</html>
