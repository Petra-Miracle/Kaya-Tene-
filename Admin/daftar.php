<?php
session_start();
require_once '../config/Connection.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (!empty($username) && !empty($password) && !empty($confirm_password)) {
        if ($password !== $confirm_password) {
            $error = "Konfirmasi password tidak cocok.";
        } else {
            // Check if username already exists
            $stmt = $conn->prepare("SELECT id FROM Admin WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            if ($stmt->get_result()->num_rows > 0) {
                $error = "Username sudah terdaftar. Silakan gunakan username lain.";
            } else {
                // Register using password_hash
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO Admin (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hashed_password);

                if ($stmt->execute()) {
                    $success = "Pendaftaran admin berhasil! Silakan login.";
                } else {
                    $error = "Terjadi kesalahan saat menyimpan data.";
                }
            }
        }
    } else {
        $error = "Silakan isi semua kolom pendaftaran.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Admin - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--bg-darker);
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            border-top: 4px solid var(--secondary);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--secondary);
        }

        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            cursor: pointer;
            color: var(--text-muted);
            transition: color 0.3s;
        }

        .password-toggle:hover {
            color: var(--secondary);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            margin-top: 10px;
            background: linear-gradient(45deg, var(--secondary), #cc8400);
            color: #000;
        }

        .register-link {
            display: block;
            margin-top: 25px;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.3s;
        }

        .register-link:hover {
            color: var(--secondary);
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <div class="login-card glass">
        <a href="../index.php" class="logo" style="justify-content: center; margin-bottom: 30px;">
            <img src="../Public/img/Logo_Yayasan-new.png" alt="Logo Yayasan"
                style="height: 40px; transform: scale(1.8); margin-right: 25px;">
            <span style="color: var(--primary);">Yayasan</span> Kaya Tene
        </a>
        <h2 style="margin-bottom: 30px;">Daftar Admin Baru</h2>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Buat username"
                    required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Buat password" required>
                    <i class="fa-solid fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <div class="password-wrapper">
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                        placeholder="Ulangi password" required>
                    <i class="fa-solid fa-eye password-toggle" onclick="togglePassword('confirm_password', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-primary login-btn">Daftar Sekarang</button>
        </form>

        <a href="login.php" class="register-link">Sudah punya akun? Kembali ke Login</a>
    </div>

    <script>
        function togglePassword(inputId, iconElement) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                iconElement.classList.remove('fa-eye');
                iconElement.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                iconElement.classList.remove('fa-eye-slash');
                iconElement.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>