<?php
session_start();
require_once '../config/Connection.php';

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id, username, password FROM Admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            // Verify password using password_hash
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password yang Anda masukkan salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } else {
        $error = "Silakan isi username dan password.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--bg-darker);
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            border-top: 4px solid var(--primary);
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
            border-color: var(--primary);
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
            color: var(--primary);
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
            margin-bottom: 25px;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            font-size: 1.1rem;
            margin-top: 10px;
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
            color: var(--primary);
        }

        .floating-toggle {
            position: absolute;
            top: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 1px solid var(--glass-border);
            background: var(--bg-card);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: var(--shadow-sm);
        }

        .floating-toggle:hover {
            transform: scale(1.1);
            color: var(--primary);
            border-color: var(--primary);
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <button class="floating-toggle" id="themeToggle" title="Toggle Light/Dark Mode">
        <i class="fa-solid fa-moon" id="themeIcon"></i>
    </button>

    <div class="login-card glass">
        <a href="../index.php" class="logo" style="justify-content: center; margin-bottom: 30px;">
            <img src="../Public/img/Logo_Yayasan-new.png" alt="Logo Yayasan"
                style="height: 40px; transform: scale(1.8); margin-right: 25px;">
            <span style="color: var(--primary);">Yayasan</span> Kaya Tene
        </a>
        <h2 style="margin-bottom: 30px;">Login Admin</h2>

        <?php if (!empty($error)): ?>
            <div class="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control"
                    placeholder="Masukkan username admin" required>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" class="form-control"
                        placeholder="Masukkan password" required>
                    <i class="fa-solid fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                </div>
            </div>

            <button type="submit" class="btn btn-primary login-btn">Masuk ke Dashboard</button>
        </form>

        <a href="daftar.php" class="register-link">Belum punya akun admin? Daftar di sini</a>
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

        // Theme Toggle Logic
        const themeBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'light') {
            body.classList.add('light-mode');
            if (themeIcon) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                body.classList.toggle('light-mode');
                if (body.classList.contains('light-mode')) {
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
            });
        }
    </script>
</body>

</html>