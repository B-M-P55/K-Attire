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

    // HANDLE ORDER UPDATE FORM SUBMISSION
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_type']) && $_POST['action_type'] === 'update_order') {
        $order_id         = $_POST['order_id'] ?? '';
        $first_name       = trim($_POST['first_name'] ?? '');
        $last_name        = trim($_POST['last_name'] ?? '');
        $email            = trim($_POST['email'] ?? '');
        $phone            = trim($_POST['phone'] ?? '');
        $city             = trim($_POST['city'] ?? '');
        $delivery_address = trim($_POST['delivery_address'] ?? '');
        $payment_method   = trim($_POST['payment_method'] ?? '');
        $order_status     = trim($_POST['order_status'] ?? 'pending');
        $total_amount     = $_POST['total_amount'] ?? 0;

        if (!empty($order_id)) {
            $sql = "UPDATE `orders` SET first_name = ?, last_name = ?, email = ?, phone = ?, city = ?, delivery_address = ?, payment_method = ?, order_status = ?, total_amount = ? WHERE order_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$first_name, $last_name, $email, $phone, $city, $delivery_address, $payment_method, $order_status, $total_amount, $order_id]);
            
            $_SESSION['message'] = "Order #$order_id updated successfully!";
            $_SESSION['msg_type'] = "success";
        }

        header("Location: orders.php");
        exit();
    }

    // HANDLE DELETE ORDER VIA GET
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        $stmt = $pdo->prepare("DELETE FROM `orders` WHERE order_id = ?");
        $stmt->execute([$delete_id]);

        $_SESSION['message'] = "Order deleted successfully!";
        $_SESSION['msg_type'] = "success";

        header("Location: orders.php");
        exit();
    }

    // Fetch Counts for Sidebar Badges
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $totalOrders = $pdo->query("SELECT COUNT(*) FROM `orders`")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    
    // Fetch all orders from the database using order_date
    $stmt = $pdo->query("SELECT * FROM `orders` ORDER BY order_date DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - K ATTIRE</title>
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
                
                <a href="orders.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg bg-primary-600 text-white font-medium shadow-sm"><i class="fa-solid fa-shopping-cart w-5"></i> Orders (<?= $totalOrders; ?>)</a>
                <a href="users.php" class="flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-slate-800 text-slate-300 hover:text-white transition-colors"><i class="fa-solid fa-users w-5"></i> Users (<?= $totalUsers ?? 0; ?>)</a>
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
                    <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Order Management</h1>
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
                            <?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['first_name'] ?? 'Admin User'); ?>
                        </span>
                    </a>
                </div>
            </header>

            <!-- ORDERS BODY CONTENT -->
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

                <!-- PAGE HEADER -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Customer Orders</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Review incoming customer purchases, shipping info, and payment records.</p>
                    </div>
                </div>

                <!-- ORDERS TABLE -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-750 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse text-sm">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-750/50 text-gray-400 uppercase text-xs tracking-wider border-b border-gray-100 dark:border-gray-750">
                                    <th class="p-4">Order ID</th>
                                    <th class="p-4">Customer</th>
                                    <th class="p-4">Phone / Address</th>
                                    <th class="p-4">Payment</th>
                                    <th class="p-4">Total Amount</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4">Date</th>
                                    <th class="p-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-750">
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $row): 
                                        $orderId = $row['id'] ?? $row['order_id'] ?? '';
                                        $fullName = $row['full_name'] ?? trim(($row['first_name'] ?? '') . ' ' . ($row['last_name'] ?? '')) ?: 'N/A';
                                        $email = $row['email'] ?? '';
                                        $phone = $row['phone'] ?? '';
                                        $address = $row['address'] ?? $row['shipping_address'] ?? $row['delivery_address'] ?? 'N/A';
                                        $paymentMethod = $row['payment_method'] ?? 'N/A';
                                        $paymentScreenshot = $row['payment_screenshot'] ?? $row['screenshot_path'] ?? '';
                                        $totalAmount = $row['total_amount'] ?? 0;
                                        $orderStatus = $row['order_status'] ?? 'pending';
                                        $orderDate = $row['order_date'] ?? $row['created_at'] ?? '';
                                    ?>
                                    <tr class="hover:bg-blue-50/30 dark:hover:bg-gray-750/30 transition-colors">
                                        <td class="p-4 font-mono text-xs text-gray-500">#<?= htmlspecialchars($orderId); ?></td>
                                        <td class="p-4">
                                            <div class="font-semibold text-slate-900 dark:text-white"><?= htmlspecialchars($fullName); ?></div>
                                            <div class="text-xs text-gray-400"><?= htmlspecialchars($email); ?></div>
                                        </td>
                                        <td class="p-4">
                                            <div class="text-xs font-medium text-gray-700 dark:text-gray-300"><?= htmlspecialchars($phone); ?></div>
                                            <div class="text-xs text-gray-500 truncate max-w-xs" title="<?= htmlspecialchars($address); ?>"><?= htmlspecialchars($address); ?></div>
                                        </td>
                                        <td class="p-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                <?= htmlspecialchars($paymentMethod); ?>
                                            </span>
                                            <?php if (!empty($paymentScreenshot)): ?>
                                                <div class="mt-1">
                                                    <a href="uploads/<?= htmlspecialchars($paymentScreenshot); ?>" target="_blank" class="text-xs text-blue-600 dark:text-blue-400 underline hover:text-blue-800">View Receipt</a>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-4 font-semibold text-emerald-600 dark:text-emerald-400">
                                            <?= number_format($totalAmount, 0); ?> MMK
                                        </td>
                                        <td class="p-4">
                                            <span class="px-2 py-1 rounded text-xs font-semibold 
                                                <?= ($orderStatus === 'delivered') ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/50 dark:text-emerald-300' : 
                                                   (($orderStatus === 'cancelled') ? 'bg-red-100 text-red-800 dark:bg-red-950/50 dark:text-red-300' : 'bg-amber-100 text-amber-800 dark:bg-amber-950/50 dark:text-amber-300'); ?>">
                                                <?= ucfirst($orderStatus); ?>
                                            </span>
                                        </td>
                                        <td class="p-4 text-xs text-gray-500">
                                            <?= htmlspecialchars($orderDate); ?>
                                        </td>
                                        <td class="p-4 text-center space-x-2 whitespace-nowrap">
                                            <!-- EDIT BUTTON OPENS MODAL -->
                                            <button onclick='openEditModal(<?= json_encode($row); ?>)' class="text-blue-600 hover:text-blue-800 dark:text-blue-400 font-medium text-xs bg-blue-50 dark:bg-blue-950/50 px-2.5 py-1 rounded transition-colors cursor-pointer">Edit</button>
                                            
                                            <!-- DELETE ACTION LINK -->
                                            <a href="orders.php?delete_id=<?= htmlspecialchars($orderId); ?>" onclick="return confirm('Are you sure you want to delete order #<?= htmlspecialchars($orderId); ?>?');" class="text-red-600 hover:text-red-800 dark:text-red-400 font-medium text-xs bg-red-50 dark:bg-red-950/50 px-2.5 py-1 rounded transition-colors">Delete</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="p-8 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center gap-2">
                                                <i class="fa-solid fa-box-open text-3xl"></i>
                                                <p>No orders found in the database table.</p>
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

    <!-- EDIT ORDER MODAL -->
    <div id="orderModal" class="fixed inset-0 bg-black/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-xl shadow-2xl border border-gray-100 dark:border-gray-700 overflow-hidden transform transition-all max-h-[90vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-750">
                <h3 id="modalTitle" class="font-bold text-lg text-slate-900 dark:text-white">Edit Order</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg cursor-pointer"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form action="orders.php" method="POST" class="p-6 space-y-4 overflow-y-auto flex-1">
                <input type="hidden" name="action_type" value="update_order">
                <input type="hidden" id="order_id" name="order_id">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" required class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Phone</label>
                        <input type="text" id="phone" name="phone" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">City</label>
                        <input type="text" id="city" name="city" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Total Amount (MMK)</label>
                        <input type="number" step="0.01" id="total_amount" name="total_amount" required class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Payment Method</label>
                        <input type="text" id="payment_method" name="payment_method" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Order Status</label>
                        <select id="order_status" name="order_status" class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600">
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase mb-1">Delivery Address</label>
                    <textarea id="delivery_address" name="delivery_address" rows="3" required class="w-full px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-transparent text-sm focus:outline-none focus:border-primary-600"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 rounded-lg text-sm font-medium border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-lg text-sm font-medium bg-accent-500 hover:bg-accent-600 text-white shadow-sm cursor-pointer">Save Changes</button>
                </div>

                <div id="receipt_container" class="hidden mb-4 p-3 bg-gray-50 dark:bg-gray-750/50 rounded-lg border border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase">Payment Receipt</span>
                    <a id="receipt_link" href="#" target="_blank" class="text-xs font-medium text-blue-600 dark:text-blue-400 underline hover:text-blue-800 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Uploaded Receipt
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS -->
    <script>
        const modal = document.getElementById('orderModal');
        const modalTitle = document.getElementById('modalTitle');
        const orderIdInput = document.getElementById('order_id');
        const firstNameInput = document.getElementById('first_name');
        const lastNameInput = document.getElementById('last_name');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');
        const cityInput = document.getElementById('city');
        const totalAmountInput = document.getElementById('total_amount');
        const paymentMethodInput = document.getElementById('payment_method');
        const orderStatusInput = document.getElementById('order_status');
        const deliveryAddressInput = document.getElementById('delivery_address');
        const receiptContainer = document.getElementById('receipt_container');
        const receiptLink = document.getElementById('receipt_link');

        function openEditModal(order) {
            const idVal = order.order_id || order.id || '';
            modalTitle.innerText = "Edit Order #" + idVal;
            orderIdInput.value = idVal;
            firstNameInput.value = order.first_name || '';
            lastNameInput.value = order.last_name || '';
            emailInput.value = order.email || '';
            phoneInput.value = order.phone || '';
            cityInput.value = order.city || '';
            totalAmountInput.value = order.total_amount || '';
            paymentMethodInput.value = order.payment_method || '';
            orderStatusInput.value = order.order_status || 'pending';
            deliveryAddressInput.value = order.delivery_address || order.address || order.shipping_address || '';
            
            // Handle Payment Screenshot / Receipt Link
            const screenshot = order.payment_screenshot || order.screenshot_path || '';
            if (screenshot.trim() !== '') {
                receiptLink.href = "uploads/" + screenshot;
                receiptContainer.classList.remove('hidden');
                receiptContainer.classList.add('flex');
            } else {
                receiptContainer.classList.remove('flex');
                receiptContainer.classList.add('hidden');
            }

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