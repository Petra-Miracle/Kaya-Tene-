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
            if (password_verify($password, $admin['password'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Password salah.";
            }
        } else {
            $error = "Username tidak ditemukan.";
        }
    } else {
        $error = "Lengkapi data login.";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --login-bg: #0f1115;
            --card-bg: #1a1d23;
            --input-border: rgba(255, 255, 255, 0.1);
        }

        body.light-mode {
            --login-bg: #f5f7fa;
            --card-bg: #ffffff;
            --input-border: #e2e8f0;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: var(--login-bg);
            margin: 0;
            font-family: 'Inter', sans-serif;
            transition: background 0.3s ease;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: var(--card-bg);
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--input-border);
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-img {
            height: 40px;
            margin-bottom: 12px;
        }

        .login-card h2 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 8px;
            color: var(--text-main);
        }

        .login-card p.subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-main);
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border-radius: 8px;
            background: transparent;
            border: 1px solid var(--input-border);
            color: var(--text-main);
            font-size: 0.95rem;
            transition: all 0.2s;
            outline: none;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 90, 0, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--text-muted);
            font-size: 0.9rem;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }

        .login-btn:hover {
            background: var(--primary-dark);
        }

        .alert-error {
            background: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: 1px solid #fed7d7;
            text-align: center;
        }

        body:not(.light-mode) .alert-error {
            background: rgba(254, 178, 178, 0.1);
            border-color: rgba(254, 178, 178, 0.2);
            color: #feb2b2;
        }

        .footer-action {
            margin-top: 25px;
            text-align: center;
        }

        .back-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .back-link:hover {
            color: var(--primary);
        }

        /* Top Theme Toggle - Discrete */
        .theme-switch {
            position: fixed;
            top: 20px;
            right: 20px;
        }

        .theme-btn {
            background: var(--card-bg);
            border: 1px solid var(--input-border);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <div class="theme-switch">
        <button class="theme-btn" id="toggleTheme">
            <i class="fa-solid fa-moon" id="themeIcon"></i>
        </button>
    </div>

    <div class="login-card">
        <div class="logo-section">
            <img src="../Public/img/Logo_Yayasan-new.png" alt="Logo" class="logo-img">
            <h2>Masuk Admin</h2>
            <p class="subtitle">Kelola konten Yayasan Kaya Tene</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" 
                    placeholder="Username admin" required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="form-control" 
                        placeholder="••••••••" required autocomplete="current-password">
                    <i class="fa-solid fa-eye password-toggle" id="togglePass"></i>
                </div>
            </div>

            <button type="submit" class="login-btn">Login Sekarang</button>
        </form>

        <div class="footer-action">
            <a href="../index.php" class="back-link">← Kembali ke Website</a>
        </div>
    </div>

    <script>
        // Password Visibility Toggle
        const togglePass = document.getElementById('togglePass');
        const password = document.getElementById('password');

        togglePass.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePass.classList.toggle('fa-eye-slash');
        });

        // Simple Theme Toggle
        const btn = document.getElementById('toggleTheme');
        const icon = document.getElementById('themeIcon');
        const body = document.body;

        if (localStorage.getItem('theme') === 'light') {
            body.classList.add('light-mode');
            icon.classList.replace('fa-moon', 'fa-sun');
        }

        btn.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            const isLight = body.classList.contains('light-mode');
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
            icon.classList.replace(isLight ? 'fa-moon' : 'fa-sun', isLight ? 'fa-sun' : 'fa-moon');
        });
    </script>
</body>

</html>