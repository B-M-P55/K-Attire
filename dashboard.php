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

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch Real Dynamic Statistics from Database
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    
    // Check if categories table exists, otherwise count distinct or default to 0
    try {
        $totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    } catch (Exception $e) {
        $totalCategories = 0;
    }
    
    // Fetch actual order count if orders table exists, fallback safely
    try {
        $totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    } catch (Exception $e) {
        $totalOrders = 0;
    }

    // Fetch actual sum of revenue if columns exist, otherwise calculate or set placeholder
    try {
        $revenueStmt = $pdo->query("SELECT SUM(total_amount) FROM orders");
        $totalRevenueRaw = $revenueStmt->fetchColumn();
        $totalRevenue = $totalRevenueRaw ? number_format($totalRevenueRaw, 0) . " MMK" : "0 MMK";
    } catch (Exception $e) {
        $totalRevenue = "0 MMK";
    }

    // Fetch Recent Products for Inventory Table from Database
    $stmt = $pdo->query("SELECT product_name, price, stock_quantity, color, image FROM products ORDER BY product_id DESC LIMIT 5");
    $recentProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Store Admin Dashboard - Blessed Beans</title>
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
                <!-- Logo Image Integration with fallback -->
                <div class="w-10 h-10 flex items-center justify-center overflow-hidden rounded-lg bg-white/5 p-1">
                    <img src="uploads/logo.png" alt="logo" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="w-full h-full bg-accent-500 text-white items-center justify-center rounded-lg text-sm hidden">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                </div>
                <span class="tracking-wide text-base">K ATTIRE</span>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="dashboard.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-primary-600 text-white font-medium shadow-sm"><i class="fa-solid fa-chart-pie w-5"></i> Dashboard</a>

                <a href="products.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-shirt"></i></i> Products (<?= $totalProducts; ?>)</a>

                <a href="categories.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-list w-5"></i> Categories (<?= $totalCategories; ?>)</a>

                <a href="orders.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-shopping-cart w-5"></i> Orders (<?= $totalOrders; ?>)</a>
                
                <a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-users w-5"></i> Users</a>
            </nav>

            <!-- SIDEBAR FOOTER & LOGOUT -->
            <div class="p-4 border-t border-slate-800 space-y-3">
                <div class="text-xs text-slate-400 truncate">
                    Logged in as <span class="text-slate-200 font-medium"><?= htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?></span>
                </div>
                <a href="logout.php" onclick="return confirm('Are you sure you want to log out?');" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-lg bg-accent-500 hover:bg-accent-600 text-white font-medium text-xs transition-colors shadow-sm">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- TOP NAVBAR -->
            <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-750 flex items-center justify-between px-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <button class="md:hidden text-gray-500 text-xl"><i class="fa-solid fa-bars"></i></button>
                    <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Store Overview</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- THEME TOGGLE BUTTON -->
                    <button id="themeToggle" onclick="toggleTheme()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-yellow-400 transition-transform active:scale-95">
                        <i id="themeIcon" class="fa-solid fa-moon text-lg"></i>
                    </button>
                    
                    <!-- CLICKABLE PROFILE LINK -->
                    <a href="profile.php" class="flex items-center gap-2 border-l pl-4 border-gray-200 dark:border-gray-700 hover:opacity-80 transition-opacity cursor-pointer group" title="View Profile">
                        <div class="w-9 h-9 rounded-full bg-accent-500 text-white flex items-center justify-center font-bold shadow-sm">
                            <?= strtoupper(substr($_SESSION['first_name'] ?? 'A', 0, 1)); ?>
                        </div>
                        <span class="text-sm font-medium hidden sm:inline group-hover:text-primary-600 dark:group-hover:text-blue-400 transition-colors">
                            <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin User'); ?>
                        </span>
                    </a>
                </div>
            </header>

            <!-- DASHBOARD BODY CONTENT -->
            <main class="p-6 space-y-6">
                
                <!-- METRICS CARDS (Real Database Data) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-750 shadow-sm flex items-center justify-between border-l-4 border-l-primary-600">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Products</p>
                            <h3 class="text-2xl font-bold mt-1"><?= $totalProducts; ?></h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-primary-600 dark:text-blue-400 flex items-center justify-center text-xl"><i class="fa-solid fa-shirt"></i></div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-750 shadow-sm flex items-center justify-between border-l-4 border-l-accent-500">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Active Categories</p>
                            <h3 class="text-2xl font-bold mt-1"><?= $totalCategories; ?></h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-red-50 dark:bg-red-950/50 text-accent-500 flex items-center justify-center text-xl"><i class="fa-solid fa-tags"></i></div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-750 shadow-sm flex items-center justify-between border-l-4 border-l-primary-600">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Orders</p>
                            <h3 class="text-2xl font-bold mt-1"><?= $totalOrders; ?></h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-blue-50 dark:bg-blue-950/50 text-primary-600 dark:text-blue-400 flex items-center justify-center text-xl"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-5 rounded-xl border border-gray-100 dark:border-gray-750 shadow-sm flex items-center justify-between border-l-4 border-l-accent-500">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Total Revenue</p>
                            <h3 class="text-2xl font-bold mt-1"><?= $totalRevenue; ?></h3>
                        </div>
                        <div class="w-12 h-12 rounded-lg bg-red-50 dark:bg-red-950/50 text-accent-500 flex items-center justify-center text-xl"><i class="fa-solid fa-wallet"></i></div>
                    </div>
                </div>

                <!-- RECENT INVENTORY TABLE (Real Database Feed) -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-750 shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-750 flex justify-between items-center">
                        <h3 class="font-bold text-base">Recently Added Inventory</h3>
                        <span class="text-xs px-2.5 py-1 bg-red-50 dark:bg-red-950/50 text-accent-500 rounded-full font-medium">Live Database Feed</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-750/50 text-gray-400 uppercase text-xs tracking-wider border-b border-gray-100 dark:border-gray-750">
                                    <th class="p-4">Image File</th>
                                    <th class="p-4">Product Name</th>
                                    <th class="p-4">Price</th>
                                    <th class="p-4">Stock</th>
                                    <th class="p-4">Color</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-750">
                                <?php if (!empty($recentProducts)): ?>
                                    <?php foreach ($recentProducts as $row): ?>
                                    <tr class="hover:bg-blue-50/30 dark:hover:bg-gray-750/30 transition-colors">
                                        <td class="p-4"><span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 text-primary-600 dark:text-blue-400 px-2 py-1 rounded font-medium"><?= htmlspecialchars($row['image']); ?></span></td>
                                        <td class="p-4 font-medium"><?= htmlspecialchars($row['product_name']); ?></td>
                                        <td class="p-4 font-semibold text-slate-900 dark:text-white"><?= number_format($row['price'], 0); ?> MMK</td>
                                        <td class="p-4"><span class="text-emerald-600 dark:text-emerald-400 font-medium"><?= $row['stock_quantity']; ?> in stock</span></td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                <?= htmlspecialchars($row['color']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="p-4 text-center text-gray-400">No products found in the database.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
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