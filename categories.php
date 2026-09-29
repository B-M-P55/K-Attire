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

    // HANDLE FORM SUBMISSION (SAVE / UPDATE CATEGORY)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'save_category') {
        $category_id   = $_POST['category_id'] ?? '';
        $category_name = trim($_POST['category_name'] ?? '');
        $description   = trim($_POST['description'] ?? '');

        if (!empty($category_id)) {
            // UPDATE EXISTING CATEGORY
            $sql = "UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$category_name, $description, $category_id]);
            $_SESSION['message'] = "Category updated successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            // INSERT NEW CATEGORY
            $sql = "INSERT INTO categories (category_name, description) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$category_name, $description]);
            $_SESSION['message'] = "New category added successfully!";
            $_SESSION['msg_type'] = "success";
        }

        header("Location: categories.php");
        exit();
    }

    // HANDLE DELETE CATEGORY VIA GET
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM categories WHERE category_id = ?");
        $stmt->execute([$delete_id]);
        $_SESSION['message'] = "Category deleted successfully!";
        $_SESSION['msg_type'] = "success";
        
        header("Location: categories.php");
        exit();
    }

    // Fetch Total Product Count for Sidebar
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    
    // Fetch Total Category Count for Sidebar
    $totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();

    // Fetch all categories from the database
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY category_id ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Management - K ATTIRE</title>
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

                <a href="categories.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-primary-600 text-white font-medium shadow-sm"><i class="fa-solid fa-list w-5"></i> Categories (<?= $totalCategories; ?>)</a>

                <a href="orders.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-shopping-cart w-5"></i> Orders</a>
                
                <a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-users w-5"></i> Users</a>
            </nav>
            <div class="p-4 border-t border-slate-800 text-xs text-slate-400">
                Logged in as <?= htmlspecialchars($_SESSION['first_name'] ?? 'Admin'); ?>
            </div>
        </aside>

        <!-- MAIN CONTENT AREA -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            
            <!-- TOP NAVBAR -->
            <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-750 flex items-center justify-between px-6 shadow-sm">
                <div class="flex items-center gap-4">
                    <button class="md:hidden text-gray-500 text-xl"><i class="fa-solid fa-bars"></i></button>
                    <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Category Management</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <!-- THEME TOGGLE -->
                    <button id="themeToggle" onclick="toggleTheme()" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-600 dark:text-yellow-400 transition-transform active:scale-95">
                        <i id="themeIcon" class="fa-solid fa-moon text-lg"></i>
                    </button>
                    
                    <!-- PROFILE LINK -->
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

            <!-- CATEGORIES BODY CONTENT -->
            <main class="p-6 space-y-6">
                
                <!-- SESSION MESSAGE ALERT -->
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="p-4 rounded-lg text-sm font-medium flex items-center justify-between shadow-sm <?= (isset($_SESSION['msg_type']) && $_SESSION['msg_type'] === 'success') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800' : 'bg-red-50 text-red-800 border border-red-200 dark:bg-red-950/50 dark:text-red-300 dark:border-red-800'; ?>">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid <?= (isset($_SESSION['msg_type']) && $_SESSION['msg_type'] === 'success') ? 'fa-circle-check text-emerald-600' : 'fa-triangle-exclamation text-red-600'; ?>"></i>
                            <span><?= htmlspecialchars($_SESSION['message']); ?></span>
                        </div>
                        <button onclick="this.parentElement.remove();" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['msg_type']); ?>
                <?php endif; ?>

                <!-- PAGE HEADER & ACTION BUTTONS -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">All Categories</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Manage item classifications and product groups stored in your database.</p>
                    </div>
                    <!-- BUTTON TO OPEN ADD MODAL -->
                    <button onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-accent-500 hover:bg-accent-600 text-white font-medium text-sm shadow-sm transition-colors cursor-pointer">
                        <i class="fa-solid fa-plus"></i> Add New Category
                    </button>
                </div>

                <!-- CATEGORIES TABLE -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-750 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-750/50 text-gray-400 uppercase text-xs tracking-wider border-b border-gray-100 dark:border-gray-750">
                                    <th class="p-4">ID</th>
                                    <th class="p-4">Category Name</th>
                                    <th class="p-4">Description</th>
                                    <th class="p-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-750">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $row): ?>
                                    <tr class="hover:bg-blue-50/30 dark:hover:bg-gray-750/30 transition-colors">
                                        <td class="p-4 font-mono text-xs text-gray-500">#<?= $row['category_id']; ?></td>
                                        <td class="p-4 font-semibold text-slate-900 dark:text-white">
                                            <?= htmlspecialchars($row['category_name']); ?>
                                        </td>
                                        <td class="p-4 text-gray-600 dark:text-gray-300">
                                            <?= htmlspecialchars($row['description']); ?>
                                        </td>
                                        <td class="p-4 text-center space-x-2">
                                            <!-- EDIT BUTTON OPENS MODAL -->
                                            <button onclick='openEditModal(<?= json_encode($row); ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium text-xs bg-blue-50 dark:bg-blue-950/50 px-2.5 py-1 rounded transition-colors cursor-pointer">Edit</button>
                                            <a href="categories.php?delete_id=<?= $row['category_id']; ?>" onclick="return confirm('Are you sure you want to delete this category?');" class="text-red-600 hover:text-red-800 dark:text-red-400 font-medium text-xs bg-red-50 dark:bg-red-950/50 px-2.5 py-1 rounded transition-colors">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="p-8 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <i class="fa-solid fa-folder-open text-3xl"></i>
                                                <p>No categories found in the database table.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- CATEGORY MODAL (ADD & EDIT) -->
    <div id="categoryModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-lg shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden transform transition-all">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-750">
                <h3 id="modalTitle" class="font-bold text-lg text-slate-900 dark:text-white">Add New Category</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="categories.php" method="POST" class="p-6 space-y-4">
                <input type="hidden" name="action_type" value="save_category">
                <input type="hidden" id="category_id" name="category_id">
                
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Category Name</label>
                    <input type="text" id="category_name" name="category_name" required placeholder="e.g., Women Dress" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Description</label>
                    <textarea id="description" name="description" rows="3" placeholder="Category summary or specifications..." class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-lg text-sm font-medium bg-accent-500 hover:bg-accent-600 text-white shadow-sm cursor-pointer">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL & THEME SCRIPTS -->
    <script>
        const modal = document.getElementById('categoryModal');
        const modalTitle = document.getElementById('modalTitle');
        const categoryIdInput = document.getElementById('category_id');
        const categoryNameInput = document.getElementById('category_name');
        const descriptionInput = document.getElementById('description');

        function openAddModal() {
            modalTitle.innerText = "Add New Category";
            categoryIdInput.value = "";
            categoryNameInput.value = "";
            descriptionInput.value = "";
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function openEditModal(category) {
            modalTitle.innerText = "Edit Category #" + category.category_id;
            categoryIdInput.value = category.category_id;
            categoryNameInput.value = category.category_name;
            descriptionInput.value = category.description;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

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