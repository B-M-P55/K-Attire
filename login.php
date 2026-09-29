<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$error = '';

require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        try {
            // Fetch user along with their role
            $stmt = $pdo->prepare("SELECT user_id, first_name, last_name, email, password, role FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify user exists and check password (supports hashed passwords, falls back securely)
            if ($user && (password_verify($password, $user['password']) || $password === $user['password'])) {
                
                // Set session variables matching your checkout expectations
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role column value ('admin' vs 'customer')
                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $error = "Invalid email address or password.";
            }
        } catch (PDOException $e) {
            $error = "Database connection error. Please try again later.";
        }
    } else {
        $error = "Please fill in all required fields.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | KAttire</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        :root {
            --bg-color: #f4f6fb; --text-color: #0f172a; --card-bg: #ffffff; --nav-bg: #ffffff; --border-color: #3b82f6; --accent-primary: #180673; --accent-secondary: #dc2626;   
        }
        [data-theme="dark"] {
            --bg-color: #000000; --text-color: #ffffff; --card-bg: #000000; --nav-bg: #000000; --border-color: #990000; --accent-primary: #b30000; --accent-secondary: #ff3333;
        }
        body { background-color: var(--bg-color); color: var(--text-color); font-family: 'DM Sans', sans-serif; transition: background-color 0.3s ease, color 0.3s ease; margin: 0; display: flex; flex-direction: column; min-height: 100vh; }
        .auth-container { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px 20px; }
        .auth-card { background-color: var(--card-bg); border: 1px solid var(--border-color); padding: 40px; border-radius: 12px; width: 100%; max-width: 420px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        [data-theme="dark"] .auth-card { border: 1px solid #990000 !important; background-color: #000000 !important; }
        .auth-header { text-align: center; margin-bottom: 30px; }
        .auth-header h2 { font-family: 'Playfair Display', serif; font-size: 2rem; margin-bottom: 8px; }
        .auth-header p { color: #64748b; font-size: 0.95rem; }
        [data-theme="dark"] .auth-header p { color: #a1a1aa; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 500; font-size: 0.9rem; }
        .form-group input { width: 100%; padding: 12px 15px; border: 1px solid #cbd5e1; border-radius: 8px; background-color: var(--card-bg); color: var(--text-color); font-size: 1rem; box-sizing: border-box; transition: border-color 0.2s; }
        [data-theme="dark"] .form-group input { border: 1px solid #990000 !important; background-color: #000000 !important; color: #ffffff !important; }
        .form-group input:focus { outline: none; border-color: var(--accent-secondary); }
        .auth-btn { width: 100%; padding: 12px; background-color: var(--accent-primary); color: #ffffff; border: none; border-radius: 8px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: opacity 0.2s; }
        .auth-btn:hover { opacity: 0.9; }
        [data-theme="dark"] .auth-btn { background-color: #990000 !important; border: 1px solid #ff3333 !important; color: #ffffff !important; }
        .error-msg { background-color: #fee2e2; color: #991b1b; padding: 10px 15px; border-radius: 6px; font-size: 0.85rem; margin-bottom: 20px; text-align: center; }
        [data-theme="dark"] .error-msg { background-color: #450a0a !important; color: #fca5a5 !important; border: 1px solid #990000; }
        .auth-footer { text-align: center; margin-top: 20px; font-size: 0.9rem; }
        .auth-footer a { color: var(--accent-secondary); text-decoration: none; font-weight: 500; }
        .auth-footer a:hover { text-decoration: underline; }
        .back-home { display: inline-flex; align-items: center; gap: 6px; margin-bottom: 20px; color: var(--text-color); text-decoration: none; font-size: 0.9rem; }
        .back-home:hover { color: var(--accent-secondary); }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <a href="index.php" class="back-home"><i class="fa-solid fa-arrow-left"></i> Back to Store</a>
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your KAttire account</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-msg"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required placeholder="Enter your email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
                <button type="submit" class="auth-btn">Sign In</button>
            </form>

            <div class="auth-footer">
                <p>Don't have an account? <a href="register.php">Create one</a></p>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</body>
</html>