<?php
// checkout.php
session_start();
require_once 'db.php'; 
$orderSuccess = false;
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $paymentMethod = trim($_POST['payment_method'] ?? 'card');
    
    // Handle file upload for KPay screenshot if applicable
    $screenshotPath = null;
    if ($paymentMethod === 'kpay' && isset($_FILES['payment_screenshot']) && $_FILES['payment_screenshot']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileExtension = pathinfo($_FILES['payment_screenshot']['name'], PATHINFO_EXTENSION);
        $fileName = 'kpay_' . time() . '_' . mt_rand(1000, 9999) . '.' . $fileExtension;
        $screenshotPath = $uploadDir . $fileName;
        move_uploaded_file($_FILES['payment_screenshot']['tmp_name'], $screenshotPath);
    }

    // Capture calculated total and cart items from hidden inputs
    $totalAmount = floatval($_POST['total_amount'] ?? 0);
    $cartDataJson = $_POST['cart_data'] ?? '[]';
    $cartItems = json_decode($cartDataJson, true);
    
    $userId = $_SESSION['user_id'] ?? null;

    // Parse items into a clean readable string
    $itemsText = "Items: ";
    if (is_array($cartItems) && !empty($cartItems)) {
        foreach ($cartItems as $item) {
            $itemName = $item['name'] ?? $item['title'] ?? 'Traditional Item';
            $itemQty = $item['quantity'] ?? $item['qty'] ?? 1;
            $itemVariant = $item['variant'] ?? $item['color'] ?? $item['size'] ?? '';
            $variantText = $itemVariant ? " ($itemVariant)" : "";
            $itemsText .= "[$itemName$variantText x $itemQty] ";
        }
    } else {
        $itemsText .= "[No items found]";
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO orders (
                user_id, total_amount, order_status, 
                first_name, last_name, email, phone, 
                city, payment_method, items_text, screenshot_path
            ) VALUES (?, ?, 'pending', ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $userId,
            $totalAmount,
            $firstName,
            $lastName,
            $email,
            $phone,
            $city,
            $paymentMethod,
            $itemsText,          
            $screenshotPath     
        ]);
        
        $orderSuccess = true;
    } catch (PDOException $e) {
        $errorMessage = "Database Error: " . $e->getMessage();
        echo "<script>alert(" . json_encode($errorMessage) . ");</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Traditional Store</title>
    <!-- Google Fonts for Luxury Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --flag-blue: #002db3;
            --flag-red: #e60000;
            --pure-white: #ffffff;
            --bg-ivory: #fafafa;
            --text-dark: #111827;
            --text-muted: #4b5563;
            --card-bg: #ffffff;
            --border-color: rgba(0, 45, 179, 0.08);
            --input-bg: #f9fafb;
            --input-border: #d1d5db;
            --table-border: #f1f3f5;
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        [data-theme="dark"] {
            --flag-blue: #20058f;
            --flag-red: #a41515;
            --pure-white: #000000;
            --bg-ivory: #111827;
            --text-dark: #f9fafb;
            --text-muted: #9ca3af;
            --card-bg: #1f2937;
            --border-color: rgba(59, 130, 246, 0.2);
            --input-bg: #374151;
            --input-border: #4b5563;
            --table-border: #374151;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            background-color: var(--bg-ivory);
            color: var(--text-dark);
            line-height: 1.7;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        header {
            background: var(--pure-white);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 40px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-container img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            border-radius: 10px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-dark);
        }

        .logo span {
            color: var(--flag-red);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 35px;
            align-items: center;
        }

        .nav-links a {
            font-size: 0.95rem;
            font-weight: 500;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--flag-red);
        }

        .theme-toggle-btn {
            background: none;
            border: 1px solid var(--border-color);
            color: var(--text-dark);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            transition: var(--transition);
        }

        .theme-toggle-btn:hover {
            border-color: var(--flag-blue);
            color: var(--flag-blue);
        }

        .page-banner {
            background-color: var(--flag-blue);
            color: #ffffff;
            text-align: center;
            padding: 80px 20px;
            margin-bottom: 50px;
            border-bottom: 6px solid var(--flag-red);
        }

        .page-banner h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .page-banner p {
            font-size: 1.1rem;
            font-weight: 300;
            opacity: 0.95;
        }

        .section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px 80px 30px;
        }

        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 40px;
            align-items: start;
        }

        .checkout-card {
            background: var(--pure-white);
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .checkout-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--flag-blue);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--table-border);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group.full-width {
            grid-column: span 2;
        }

        .form-label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 0.95rem;
            color: var(--text-dark);
            outline: none;
            transition: var(--transition);
        }

        .form-control:focus {
            border-color: var(--flag-blue);
            box-shadow: 0 0 0 3px rgba(0, 45, 179, 0.1);
        }

        .payment-section-content {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px dashed var(--table-border);
        }

        .payment-section-content.active {
            display: block;
        }

        .kpay-instruction {
            font-size: 0.9rem;
            color: var(--text-muted);
            background: var(--input-bg);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid var(--input-border);
            margin-bottom: 15px;
        }

        .kpay-instruction strong {
            color: var(--flag-blue);
        }

        .order-summary-box {
            background: var(--pure-white);
            border-radius: 16px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
            position: sticky;
            top: 100px;
        }

        .summary-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid var(--table-border);
            font-size: 0.95rem;
        }

        .summary-item-details {
            flex-grow: 1;
        }

        .summary-item-details h5 {
            font-family: 'Playfair Display', serif;
            font-size: 0.95rem;
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .summary-item-details p {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .summary-totals {
            margin-top: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 0.95rem;
            color: var(--text-muted);
        }

        .summary-row.total {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-dark);
            border-top: 1px solid var(--table-border);
            padding-top: 15px;
            margin-top: 15px;
        }

        .summary-row.total span:last-child {
            color: var(--flag-blue);
        }

        .btn-place-order {
            display: block;
            width: 100%;
            background: var(--flag-blue);
            color: #ffffff;
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 25px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-place-order:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.6);
            display: flex; justify-content: center; align-items: center;
            opacity: 0; visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
            z-index: 2000;
        }
        .modal-overlay.active { opacity: 1; visibility: visible; }
        .modal-content {
            background: var(--pure-white); color: var(--text-dark); padding: 40px 30px;
            border-radius: 16px; text-align: center; max-width: 400px; width: 90%;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
            transform: translateY(20px); transition: transform 0.3s ease;
            border: 1px solid var(--border-color);
        }
        .modal-overlay.active .modal-content { transform: translateY(0); }
        .success-icon { font-size: 3.5rem; color: #10b981; margin-bottom: 15px; }
        .modal-content h2 { font-family: 'Playfair Display', serif; margin-bottom: 10px; font-size: 1.5rem; color: var(--flag-blue); }
        .modal-content p { color: var(--text-muted); font-size: 0.95rem; margin-bottom: 25px; line-height: 1.5; }
        .modal-content button { width: 100%; padding: 12px; border: none; background: var(--flag-blue); color: #fff; border-radius: 8px; font-weight: 600; cursor: pointer; }

        footer {
            background: var(--pure-white);
            color: var(--text-dark);
            text-align: center;
            padding: 50px 20px;
            margin-top: 80px;
            border-top: 5px solid var(--flag-red);
        }

        footer p {
            font-size: 0.95rem;
            opacity: 0.9;
            letter-spacing: 0.5px;
        }

        @media (max-width: 950px) {
            .checkout-layout {
                grid-template-columns: 1fr;
            }
            .form-grid {
                grid-template-columns: 1fr;
            }
            .form-group.full-width {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>

    <!-- Luxurious Navigation Header -->
    <header>
        <div class="nav-container">
            <a href="index.php" class="logo-container">
                <img src="uploads/logo.png" alt="Store Logo">
                 <div class="logo">Traditional<span>Store</span></div>
            </a>
           
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="women.php">Women</a></li>
                <li><a href="men.php">Men</a></li>
                <li><a href="children.php">Children</a></li>
                <li><a href="culture.php">Culture</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                <li>
                    <button class="theme-toggle-btn" id="themeToggle" title="Toggle Light/Dark Mode">
                        <i class="fa-solid fa-moon" id="themeIcon"></i>
                    </button>
                </li>
            </ul>
        </div>
    </header>

    <!-- Page Banner -->
    <div class="page-banner">
        <h1>Secure Checkout</h1>
        <p>Complete your shipping information and finalize your traditional items purchase.</p>
    </div>

    <!-- Main Checkout Section -->
    <main class="section">
        <form action="checkout.php" method="POST" enctype="multipart/form-data" id="checkoutForm">
            <!-- Hidden Inputs to pass calculated grand total and cart list back to PHP -->
            <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
            <input type="hidden" name="cart_data" id="cartDataInput" value="[]">

            <div class="checkout-layout">
                
                <!-- Left Column: Shipping & Payment Information -->
                <div>
                    <!-- Shipping Address Card -->
                    <div class="checkout-card">
                        <h2 class="checkout-title"><i class="fa-solid fa-truck-fast"></i> Checkout Details</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label" for="firstName">First Name</label>
                                <input type="text" id="firstName" name="first_name" class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="lastName">Last Name</label>
                                <input type="text" id="lastName" name="last_name" class="form-control" placeholder="Last Name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="email@example.com" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="phone">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-control" placeholder="+95 9..." required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label" for="address">Street Address</label>
                                <input type="text" id="address" name="address" class="form-control" placeholder="Street Address, House No." required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label" for="city">City / Township</label>
                                <input type="text" id="city" name="city" class="form-control" placeholder="Yangon" required>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method Card with Dropdown -->
                    <div class="checkout-card">
                        <h2 class="checkout-title"><i class="fa-solid fa-credit-card"></i> Payment Method</h2>
                        
                        <div class="form-group full-width">
                            <label class="form-label" for="paymentMethodSelect">Choose Payment Method</label>
                            <select id="paymentMethodSelect" name="payment_method" class="form-control" required>
                                <option value="card">Credit / Debit Card</option>
                                <option value="cash">Cash on Delivery</option>
                                <option value="kpay">KBZPay (KPay)</option>
                            </select>
                        </div>

                        <!-- 1. Card Details View -->
                        <div id="cardDetailsSection" class="payment-section-content active">
                            <div class="form-group full-width">
                                <label class="form-label" for="cardName">Name on Card</label>
                                <input type="text" id="cardName" name="card_name" class="form-control" placeholder="Cardholder Name">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label" for="cardNumber">Card Number</label>
                                <input type="text" id="cardNumber" name="card_number" class="form-control" placeholder="**** **** **** ****">
                            </div>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label" for="expiry">Expiry Date</label>
                                    <input type="text" id="expiry" name="card_expiry" class="form-control" placeholder="MM/YY">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="cvv">CVV Code</label>
                                    <input type="password" id="cvv" name="card_cvv" class="form-control" placeholder="123">
                                </div>
                            </div>
                        </div>

                        <!-- 2. Cash on Delivery View -->
                        <div id="cashDetailsSection" class="payment-section-content">
                            <div class="kpay-instruction">
                                <i class="fa-solid fa-circle-info"></i> You will pay with cash directly to the courier upon delivery of your traditional items.
                            </div>
                        </div>

                        <!-- 3. KPay View with Screenshot Upload -->
                        <div id="kpayDetailsSection" class="payment-section-content">
                            <div class="kpay-instruction">
                                Please transfer the total amount to KBZPay Account: <strong>09-123456789 (Traditional Store)</strong>, then upload your transaction screenshot below.
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label" for="paymentScreenshot">Upload Payment Screenshot</label>
                                <input type="file" id="paymentScreenshot" name="payment_screenshot" class="form-control" accept="image/*">
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Column: Order Summary Sidebar -->
                <div>
                    <div class="order-summary-box">
                        <h2 class="checkout-title" style="margin-bottom: 20px;">Order Summary</h2>
                        
                        <div id="summaryItemsContainer">
                            <p style="color: var(--text-muted); font-size: 0.9rem;">Loading items...</p>
                        </div>

                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span id="displaySubtotal">0 MMK</span>
                            </div>
                            <div class="summary-row">
                                <span>Estimated Shipping</span>
                                <span id="displayShipping">10,000 MMK</span>
                            </div>
                            <div class="summary-row total">
                                <span>Total Amount</span>
                                <span id="displayTotal">0 MMK</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-place-order">Place Order Securely</button>
                    </div>
                </div>

            </div>
        </form>
    </main>

    <!-- Order Success Popup Modal -->
    <div id="orderSuccessModal" class="modal-overlay <?php echo $orderSuccess ? 'active' : ''; ?>">
        <div class="modal-content">
            <div class="success-icon"><i class="fa-solid fa-circle-check"></i></div>
            <h2>Order Placed Successfully!</h2>
            <p>Thank you for your purchase. Your order has been successfully saved to our database.</p>
            <button id="closeModalBtn">Continue Shopping</button>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 TraditionalStore. Celebrating Heritage & Culture with Modern Elegance.</p>
    </footer>

    <script>
    // 1. Theme Switcher Logic
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const htmlElement = document.documentElement;

    const savedTheme = localStorage.getItem('theme') || 'light';
    if (savedTheme === 'dark') {
        htmlElement.setAttribute('data-theme', 'dark');
        if (themeIcon) themeIcon.classList.replace('fa-moon', 'fa-sun');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            if (htmlElement.getAttribute('data-theme') === 'dark') {
                htmlElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                if (themeIcon) themeIcon.classList.replace('fa-sun', 'fa-moon');
            } else {
                htmlElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                if (themeIcon) themeIcon.classList.replace('fa-moon', 'fa-sun');
            }
        });
    }

    // 2. Payment Method Fields Switcher
    const paymentSelect = document.getElementById('paymentMethodSelect');
    const cardSection = document.getElementById('cardDetailsSection');
    const cashSection = document.getElementById('cashDetailsSection');
    const kpaySection = document.getElementById('kpayDetailsSection');

    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            if (cardSection) cardSection.classList.remove('active');
            if (cashSection) cashSection.classList.remove('active');
            if (kpaySection) kpaySection.classList.remove('active');

            if (this.value === 'card' && cardSection) {
                cardSection.classList.add('active');
            } else if (this.value === 'cash' && cashSection) {
                cashSection.classList.add('active');
            } else if (this.value === 'kpay' && kpaySection) {
                kpaySection.classList.add('active');
            }
        });
    }

    // 3. Load Cart Items, Compute Totals, and Setup Form Submission 
    document.addEventListener('DOMContentLoaded', () => {
        let cart = JSON.parse(localStorage.getItem('cart')) 
                || JSON.parse(localStorage.getItem('kattire_cart')) 
                || JSON.parse(localStorage.getItem('shopping_cart')) 
                || [];

        console.log("Loaded Cart Data:", cart);

        const summaryContainer = document.getElementById('summaryItemsContainer');
        const displaySubtotal = document.getElementById('displaySubtotal');
        const displayShipping = document.getElementById('displayShipping');
        const displayTotal = document.getElementById('displayTotal');
        const totalAmountInput = document.getElementById('totalAmountInput');
        const cartDataInput = document.getElementById('cartDataInput');
        const checkoutForm = document.getElementById('checkoutForm');

        const shippingCost = 10000;

        // Serialize cart items into hidden input on submission
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', () => {
                let currentCartStr = localStorage.getItem('cart') 
                                    || localStorage.getItem('kattire_cart') 
                                    || localStorage.getItem('shopping_cart') 
                                    || '[]';
                cartDataInput.value = currentCartStr;
            });
        }

        if (!cart || cart.length === 0) {
            if (summaryContainer) summaryContainer.innerHTML = `<p style="color: var(--text-muted); font-size: 0.9rem; padding: 10px 0;">Your cart is empty. Please add items from the shop.</p>`;
            if (displaySubtotal) displaySubtotal.textContent = "0 MMK";
            if (displayShipping) displayShipping.textContent = "0 MMK";
            if (displayTotal) displayTotal.textContent = "0 MMK";
            if (totalAmountInput) totalAmountInput.value = 0;
            if (cartDataInput) cartDataInput.value = "[]";
        } else {
            let html = '';
            let subtotal = 0;

            cart.forEach(item => {
                let itemName = item.name || item.title || 'Traditional Item';
                let itemPrice = parseFloat(item.price || item.cost || 0);
                let itemQty = parseInt(item.quantity || item.qty || 1);
                let itemSubtotal = itemPrice * itemQty;
                subtotal += itemSubtotal;
                
                let itemVariant = item.variant || item.color || item.size || '';

                html += `
                    <div class="summary-item">
                        <div class="summary-item-details">
                            <h5>${itemName}</h5>
                            <p>Qty: ${itemQty} ${itemVariant ? '| ' + itemVariant : ''}</p>
                        </div>
                        <span>${itemSubtotal.toLocaleString()} MMK</span>
                    </div>
                `;
            });

            let grandTotal = subtotal + shippingCost;

            if (summaryContainer) summaryContainer.innerHTML = html;
            if (displaySubtotal) displaySubtotal.textContent = `${subtotal.toLocaleString()} MMK`;
            if (displayShipping) displayShipping.textContent = `${shippingCost.toLocaleString()} MMK`;
            if (displayTotal) displayTotal.textContent = `${grandTotal.toLocaleString()} MMK`;
            if (totalAmountInput) totalAmountInput.value = grandTotal;
        }

        // 4.  Success modal 
        const closeModalBtn = document.getElementById('closeModalBtn');
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', () => {
                localStorage.removeItem('cart');
                localStorage.removeItem('kattire_cart');
                localStorage.removeItem('shopping_cart');
                window.location.href = 'index.php';
            });
        }
    });
</script>
</body>
</html>