<?php
session_start();

// Security Check: Ensure user is logged in as admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Database Configuration
$host = 'localhost';
$dbname = 'kattire'; 
$username = 'root';               
$password = '';                    

$success_message = '';
$error_message = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $admin_id = $_SESSION['user_id'];

    // Handle Form Submission for Profile Update
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_profile'])) {
            $first_name = trim($_POST['first_name']);
            $last_name = trim($_POST['last_name']);
            $email = trim($_POST['email']);

            if (!empty($first_name) && !empty($email)) {
                // Check if email is already taken by another user
                $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
                $stmt->execute([$email, $admin_id]);
                if ($stmt->rowCount() > 0) {
                    $error_message = "This email address is already registered by another account.";
                } else {
                    $update_stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE user_id = ?");
                    $update_stmt->execute([$first_name, $last_name, $email, $admin_id]);
                    
                    // Update session variables
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['user_name'] = $first_name . ' ' . $last_name;
                    $_SESSION['email'] = $email;
                    
                    $success_message = "Profile updated successfully!";
                }
            } else {
                $error_message = "First name and email cannot be empty.";
            }
        }

        // Handle Password Change
        if (isset($_POST['change_password'])) {
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];
            $confirm_password = $_POST['confirm_password'];

            if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                if ($new_password === $confirm_password) {
                    // Fetch current user password hash
                    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = ?");
                    $stmt->execute([$admin_id]);
                    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user_data && password_verify($current_password, $user_data['password'])) {
                        $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                        $pass_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                        $pass_stmt->execute([$new_hash, $admin_id]);
                        $success_message = "Password changed successfully!";
                    } else {
                        $error_message = "Incorrect current password.";
                    }
                } else {
                    $error_message = "New passwords do not match.";
                }
            } else {
                $error_message = "All password fields are required.";
            }
        }
    }

    // Fetch fresh admin details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$admin_id]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch Sidebar Counts
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $totalOrders = $pdo->query("SELECT COUNT(*) FROM `orders`")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile - K ATTIRE</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { 
                            50: '#eff6ff', 
                            600: '#2563eb', // Royal Blue
                            700: '#1d4ed8' 
                        },
                        accent: {
                            500: '#dc2626', // Vibrant Red
                            600: '#b91c1c'
                        }
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 font-sans transition-colors duration-300">

    <div class="flex h-screen overflow-hidden">
        
        <!-- SIDEBAR -->
        <aside class="w-64 bg-slate-900 text-white border-r border-slate-800 flex flex-col hidden md:flex">
            <div class="p-6 text-xl font-bold border-b border-slate-800 flex items-center gap-3">
                <div class="w-10 h-10 flex items-center justify-center overflow-hidden rounded-lg bg-white/5 p-1">
                    <img src="uploads/logo.png" alt="Logo" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full bg-accent-500 text-white items-center justify-center rounded-lg text-sm hidden">
                        <i class="fa-solid fa-shirt"></i>
                    </div>
                </div>
                <span class="tracking-wide text-base">K ATTIRE</span>
            </div>
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-chart-pie w-5"></i> Dashboard</a>
                <a href="products.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-shirt w-5"></i> Products (<?= $totalProducts; ?>)</a>
                <a href="categories.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-list w-5"></i> Categories (<?= $totalCategories; ?>)</a>
                <a href="orders.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-shopping-cart w-5"></i> Orders (<?= $totalOrders; ?>)</a>
                <a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-users w-5"></i> Users (<?= $totalUsers; ?>)</a>
            </nav>
            <div class="p-4 border-t border-slate-800 text-xs text-slate-400">
                Logged in as <?= htmlspecialchars($admin['first_name']); ?>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- TOP NAVBAR -->
            <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-750 flex items-center justify-between px-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <button class="md:hidden text-gray-500 text-xl"><i class="fa-solid fa-bars"></i></button>
                    <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Admin Profile</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- THEME TOGGLE -->
                    <button id="themeToggle" onclick="toggleTheme()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-yellow-400 transition-transform active:scale-95">
                        <i id="themeIcon" class="fa-solid fa-moon text-lg"></i>
                    </button>
                    
                    <!-- PROFILE LINK -->
                    <a href="profile.php" class="flex items-center gap-2 border-l pl-4 border-gray-200 dark:border-gray-700 hover:opacity-80 transition-opacity cursor-pointer group" title="View Profile">
                        <div class="w-9 h-9 rounded-full bg-accent-500 text-white flex items-center justify-center font-bold shadow-sm">
                            <?= strtoupper(substr($admin['first_name'], 0, 1)); ?>
                        </div>
                        <span class="text-sm font-medium hidden sm:inline group-hover:text-primary-600 dark:group-hover:text-blue-400 transition-colors">
                            <?= htmlspecialchars($admin['first_name'] . ' ' . ($admin['last_name'] ?? '')); ?>
                        </span>
                    </a>
                </div>
            </header>

            <!-- PROFILE BODY CONTENT -->
            <main class="p-6 space-y-6 max-w-4xl mx-auto w-full">
                
                <!-- ALERTS -->
                <?php if (!empty($success_message)): ?>
                    <div class="p-4 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm flex items-center gap-3">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                        <span><?= $success_message; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error_message)): ?>
                    <div class="p-4 rounded-lg bg-red-50 dark:bg-red-950/50 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-300 text-sm flex items-center gap-3">
                        <i class="fa-solid fa-circle-exclamation text-lg"></i>
                        <span><?= $error_message; ?></span>
                    </div>
                <?php endif; ?>

                <!-- PROFILE INFO HEADER CARD -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-750 p-6 shadow-sm flex flex-col sm:flex-row items-center gap-6">
                    <div class="w-20 h-20 rounded-full bg-accent-500 text-white flex items-center justify-center text-3xl font-bold shadow-md">
                        <?= strtoupper(substr($admin['first_name'], 0, 1)); ?>
                    </div>
                    <div class="text-center sm:text-left space-y-1">
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white"><?= htmlspecialchars($admin['first_name'] . ' ' . ($admin['last_name'] ?? '')); ?></h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><?= htmlspecialchars($admin['email']); ?></p>
                        <div class="pt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700 dark:bg-red-950/50 dark:text-red-400">
                                <?= ucfirst(htmlspecialchars($admin['role'])); ?> Access
                            </span>
                            <span class="text-xs text-gray-400 ml-2">Member since: <?= htmlspecialchars($admin['created_at']); ?></span>
                        </div>
                    </div>
                </div>

                <!-- UPDATE PROFILE DETAILS FORM -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-750 p-6 shadow-sm space-y-6">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-gray-100 dark:border-gray-750 pb-3">Personal Information</h3>
                    
                    <form action="profile.php" method="POST" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">First Name</label>
                                <input type="text" name="first_name" value="<?= htmlspecialchars($admin['first_name']); ?>" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:outline-none focus:border-primary-600">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Last Name</label>
                                <input type="text" name="last_name" value="<?= htmlspecialchars($admin['last_name'] ?? ''); ?>" class="w-full px-3.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:outline-none focus:border-primary-600">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Email Address</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($admin['email']); ?>" required class="w-full px-3.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:outline-none focus:border-primary-600">
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" name="update_profile" class="px-4 py-2.5 rounded-lg bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm shadow-sm transition-colors">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- CHANGE PASSWORD FORM -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-750 p-6 shadow-sm space-y-6">
                    <h3 class="text-base font-bold text-slate-900 dark:text-white border-b border-gray-100 dark:border-gray-750 pb-3">Security & Password</h3>
                    
                    <form action="profile.php" method="POST" class="space-y-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Current Password</label>
                            <input type="password" name="current_password" required placeholder="••••••••" class="w-full px-3.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:outline-none focus:border-primary-600">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">New Password</label>
                                <input type="password" name="new_password" required placeholder="••••••••" class="w-full px-3.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:outline-none focus:border-primary-600">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Confirm New Password</label>
                                <input type="password" name="confirm_password" required placeholder="••••••••" class="w-full px-3.5 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-slate-900 dark:text-white focus:outline-none focus:border-primary-600">
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" name="change_password" class="px-4 py-2.5 rounded-lg bg-accent-500 hover:bg-accent-600 text-white font-medium text-sm shadow-sm transition-colors">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </main>
        </div>
    </div>

    <!-- LIGHT/DARK THEME SCRIPT -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('themeIcon');
            
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
                icon.className = 'fa-solid fa-moon text-lg';
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
                icon.className = 'fa-solid fa-sun text-lg';
            }
        }

        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.getElementById('themeIcon').className = 'fa-solid fa-sun text-lg';
        } else {
            document.documentElement.classList.add('light');
        }
    </script>
</body>
</html>