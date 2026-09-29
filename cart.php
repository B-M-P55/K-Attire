<?php
// cart.php
session_start();
require_once 'db.php'; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - KAttire</title>
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
            --border-color: rgba(0, 45, 179, 0.08);
            --table-border: #f1f3f5;
            --qty-border: #d1d5db;
            --qty-hover: #f1f3f5;
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        [data-theme="dark"] {
            --flag-blue: #090497;
            --flag-red: #ff0707;
            --pure-white: #02093a;
            --bg-ivory: #02000a;
            --text-dark: #f9fafb;
            --text-muted: #9ca3af;
            --border-color: rgba(59, 130, 246, 0.2);
            --table-border: #220686;
            --qty-border: #e84f41;
            --qty-hover: #610505;
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
            padding: 20px 40px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-container img {
            width: 38px;
            height: 38px;
            object-fit: contain;
            border-radius: 8px;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--flag-blue);
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

        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 40px;
            align-items: start;
        }

        .cart-items-container, .cart-summary {
            background: var(--pure-white);
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cart-table th {
            text-align: left;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
            padding-bottom: 20px;
            border-bottom: 1px solid var(--table-border);
        }

        .cart-table td {
            padding: 20px 0;
            border-bottom: 1px solid var(--table-border);
            vertical-align: middle;
        }

        .product-info-cell {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .product-thumb {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            background-color: var(--table-border);
            object-fit: cover;
            border: 1px solid var(--border-color);
        }

        .product-details h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.1rem;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .product-details p {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            border: 1px solid var(--qty-border);
            border-radius: 8px;
            width: fit-content;
            overflow: hidden;
            background: var(--pure-white);
        }

        .qty-btn {
            background: none;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .qty-btn:hover {
            background: var(--qty-hover);
            color: var(--flag-blue);
        }

        .qty-input {
            width: 40px;
            text-align: center;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            outline: none;
            background: transparent;
            color: var(--text-dark);
        }

        .item-price {
            font-weight: 600;
            color: var(--flag-blue);
        }

        .remove-btn {
            background: none;
            border: none;
            color: var(--flag-red);
            cursor: pointer;
            font-size: 1.1rem;
            transition: var(--transition);
        }

        .remove-btn:hover {
            opacity: 0.75;
        }

        .empty-cart-msg {
            text-align: center;
            padding: 40px 0;
            color: var(--text-muted);
            font-size: 1.1rem;
        }

        .summary-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            color: var(--flag-blue);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--table-border);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
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

        .btn-checkout {
            display: block;
            width: 100%;
            background: var(--flag-blue);
            color: #ffffff;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 25px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
        }

        .btn-checkout:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

        footer {
            background: var(--pure-white);
            color: var(--text-dark);
            text-align: center;
            padding: 40px 20px;
            margin-top: 80px;
            border-top: 5px solid var(--flag-red);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .footer-logo img {
            width: 32px;
            height: 32px;
            object-fit: contain;
            border-radius: 6px;
        }

        @media (max-width: 950px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <header>
        <div class="nav-container">
            <a href="index.php" class="logo-container">
                <img src="uploads/logo.png" alt="KAttire Logo">
                <div class="logo">K<span>Attire</span></div>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="women.php">Women</a></li>
                <li><a href="men.php">Men</a></li>
                <li><a href="children.php">Children</a></li>
                <li><a href="culture.php">Culture</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="cart.php" class="active" style="color: var(--flag-red);"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                <li>
                    <button class="theme-toggle-btn" id="themeToggle" title="Toggle Light/Dark Mode">
                        <i class="fa-solid fa-moon" id="themeIcon"></i>
                    </button>
                </li>
            </ul>
        </div>
    </header>

    <div class="page-banner">
        <h1>Your Shopping Cart</h1>
        <p>Review your selected handcrafted items before proceeding to secure checkout.</p>
    </div>

    <main class="section">
        <div class="cart-layout">
            <div class="cart-items-container">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th><i class="fa-solid fa-trash-can"></i></th>
                        </tr>
                    </thead>
                    <tbody id="cartTableBody"></tbody>
                </table>
            </div>

            <div class="cart-summary">
                <h2 class="summary-title">Order Summary</h2>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="summarySubtotal">Ks 0</span>
                </div>
                <div class="summary-row">
                    <span>Estimated Shipping</span>
                    <span id="summaryShipping">Ks 5,000</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="summaryTotal">Ks 0</span>
                </div>
                <a href="checkout.php" class="btn-checkout">Proceed to Checkout</a>
            </div>
        </div>
    </main>

    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="uploads/logo.png" alt="KAttire Logo">
                <span>KAttire</span>
            </div>
            <p>&copy; 2026 KAttire. Celebrating Heritage & Culture with Modern Elegance.</p>
        </div>
    </footer>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;

        if ((localStorage.getItem('theme') || 'light') === 'dark') {
            htmlElement.setAttribute('data-theme', 'dark');
            themeIcon.classList.replace('fa-moon', 'fa-sun');
        }

        themeToggle.addEventListener('click', () => {
            const isDark = htmlElement.getAttribute('data-theme') === 'dark';
            htmlElement.setAttribute('data-theme', isDark ? 'light' : 'dark');
            localStorage.setItem('theme', isDark ? 'light' : 'dark');
            themeIcon.classList.replace(isDark ? 'fa-sun' : 'fa-moon', isDark ? 'fa-moon' : 'fa-sun');
            if (isDark) htmlElement.removeAttribute('data-theme');
        });

        function renderCartPage() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            const tbody = document.getElementById('cartTableBody');
            const subtotalEl = document.getElementById('summarySubtotal');
            const totalEl = document.getElementById('summaryTotal');

            if (cart.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="empty-cart-msg">Your cart is currently empty. <br><br><a href="women.php" style="color: var(--flag-red); text-decoration: underline;">Explore Collection</a></td></tr>`;
                subtotalEl.textContent = 'Ks 0';
                totalEl.textContent = 'Ks 5,000';
                return;
            }

            let html = '', subtotal = 0, shippingFee = 5000;

            cart.forEach((item, index) => {
                let itemPrice = parseFloat(item.price || item.cost || 0);
                let itemQty = parseInt(item.quantity || item.qty || 1);
                let itemSubtotal = itemPrice * itemQty;
                subtotal += itemSubtotal;

                let imgPath = item.image || item.img || 'uploads/logo.png';
                let itemName = item.name || item.title || 'Traditional Item';
                let variantText = (item.variant || item.color || item.size) ? `<p>Variant: ${item.variant || item.color || item.size}</p>` : `<p>Traditional Wear</p>`;

                html += `
                    <tr>
                        <td>
                            <div class="product-info-cell">
                                <img src="${imgPath}" alt="${itemName}" class="product-thumb" onerror="this.src='uploads/logo.png'">
                                <div class="product-details">
                                    <h4>${itemName}</h4>
                                    ${variantText}
                                </div>
                            </div>
                        </td>
                        <td><span class="item-price">Ks ${itemPrice.toLocaleString()}</span></td>
                        <td>
                            <div class="quantity-selector">
                                <button class="qty-btn" onclick="updateQuantity(${index}, -1)">-</button>
                                <input type="text" class="qty-input" value="${itemQty}" readonly>
                                <button class="qty-btn" onclick="updateQuantity(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td><span class="item-price">Ks ${itemSubtotal.toLocaleString()}</span></td>
                        <td>
                            <button class="remove-btn" title="Remove Item" onclick="removeItem(${index})"><i class="fa-solid fa-xmark"></i></button>
                        </td>
                    </tr>
                `;
            });

            tbody.innerHTML = html;
            subtotalEl.textContent = 'Ks ' + subtotal.toLocaleString();
            totalEl.textContent = 'Ks ' + (subtotal + shippingFee).toLocaleString();
        }

        function updateQuantity(index, change) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            if (cart[index]) {
                let qtyKey = cart[index].quantity !== undefined ? 'quantity' : 'qty';
                cart[index][qtyKey] = (cart[index][qtyKey] || 1) + change;
                if (cart[index][qtyKey] <= 0) cart.splice(index, 1);
                localStorage.setItem('cart', JSON.stringify(cart));
                renderCartPage();
            }
        }

        function removeItem(index) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCartPage();
        }

        document.addEventListener('DOMContentLoaded', renderCartPage);
    </script>
</body>
</html>