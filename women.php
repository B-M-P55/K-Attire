<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women's Collection - Traditional Store</title>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Light Mode Theme Variables */
        :root {
            --bg-color: #f4f6fb;         
            --text-color: #0f172a;        
            --card-bg: #ffffff;            
            --nav-bg: #ffffff;            
            --footer-bg: #10035a;        
            --footer-text: #ffffff;
            --border-color: #3b48f6;     
            --accent-primary: #10033e;    
            --accent-secondary: #dc2626;  
        }

        /* Dark Mode */
        [data-theme="dark"] {
            --bg-color: #000000;        
            --text-color: #ffffff;      
            --card-bg: #000000;          
            --nav-bg: #000000;        
            --footer-bg: #040351;    
            --footer-text: #ffffff;
            --border-color: #2216cf;    
            --accent-primary: #b30000; 
            --accent-secondary: #ff3333;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        [data-theme="dark"] body,
        [data-theme="dark"] header,
        [data-theme="dark"] footer,
        [data-theme="dark"] .product-card,
        [data-theme="dark"] .wishlist {
            background-color: #000000 !important;
        }

        header {
            background: var(--nav-bg);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 2px solid var(--border-color);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
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
        }

        .logo span {
            color: var(--accent-secondary);
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 25px;
            align-items: center;
        }

        .nav-links a {
            font-weight: 500;
            color: var(--text-color);
            transition: color 0.3s;
        }

        .nav-links a:hover, .nav-links a.active {
            color: var(--accent-secondary);
        }

        .cart-badge {
            background-color: var(--accent-secondary);
            color: white;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 50%;
            margin-left: 4px;
            font-weight: bold;
        }

        .theme-toggle-btn {
            background: none;
            border: 1.5px solid var(--border-color);
            color: var(--text-color);
            padding: 5px 10px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
        }

        .page-banner {
            background: linear-gradient(rgba(107, 11, 2, 0.5), rgba(35, 2, 250, 0.5));
            color: #fff;
            text-align: center;
            padding: 60px 20px;
            margin-bottom: 40px;
            border-bottom: 3px solid var(--border-color);
        }
        .page-banner h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 60px 20px;
        }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            position: relative;
            border: 1px solid var(--border-color);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
        }

        .product-image {
            width: 100%;
            height: 280px;
            background-color: #eee;
            position: relative;
            overflow: hidden; /* Hides the button until hover */
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .product-card:hover .product-image img {
            transform: scale(1.05);
        }

        .product-label {
            position: absolute;
            top: 12px;
            left: 12px;
            background: #8b0000;
            color: #fff;
            font-size: 0.75rem;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 4px;
            z-index: 2;
            text-transform: uppercase;
        }

        .wishlist {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #fff;
            border: 1px solid var(--border-color);
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 2;
        }
        [data-theme="dark"] .wishlist {
            color: #ffffff !important;
            background: #222;
        }
        
        /* Sliding Hover Add-to-Cart Button */
        .add-cart {
            position: absolute;
            bottom: -50px;
            left: 0;
            width: 100%;
            background: #8b0000;
            color: #fff;
            border: none;
            padding: 12px;
            font-weight: bold;
            cursor: pointer;
            transition: bottom 0.3s ease, background 0.2s;
            z-index: 10;
        }

        .product-card:hover .add-cart {
            bottom: 0;
        }

        .add-cart:hover {
            background: #a90000;
        }

        .product-info {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .product-category {
            font-size: 0.8rem;
            color: #666;
            text-transform: uppercase;
        }
        [data-theme="dark"] .product-category {
            color: #aaa !important;
        }
        .product-info h3 {
            font-size: 1rem;
            color: var(--text-color);
            font-weight: 600;
        }
        .price {
            font-size: 0.95rem;
            font-weight: bold;
            color: var(--accent-secondary);
        }

        footer {
            background: var(--footer-bg);
            color: var(--footer-text);
            text-align: center;
            padding: 30px 20px;
            margin-top: 40px;
            border-top: 3px solid var(--border-color);
        }
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .footer-logo img {
            width: 30px;
            height: 30px;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <!-- Navigation Header -->
    <header>
        <div class="nav-container">
             <a href="index.php" class="logo-container">
                <img src="uploads/logo.png" alt="KAttire Logo">
                 <div class="logo">Traditional<span>Store</span></div>
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="women.php" class="active">Women</a></li>
                <li><a href="men.php">Men</a></li>
                <li><a href="children.php">Children</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart <span id="cartCount" class="cart-badge">0</span></a></li>
                <li>
                    <button class="theme-toggle-btn" id="themeToggleBtn" onclick="toggleTheme()" aria-label="Toggle theme">
                        <i class="fa-solid fa-moon" id="themeIcon"></i> <span id="themeText">Dark</span>
                    </button>
                </li>
            </ul>
        </div>
    </header>

    <!-- Page Banner -->
    <div class="page-banner">
        <h1>Women's Collection</h1>
        <p>Explore our handcrafted traditional dresses, skirts, blouses, and accessories.</p>
    </div>

    <!-- Product Grid -->
    <main>
        <section class="featured section">
            <div class="product-grid">
                
                <?php
                $womenProducts = [
                    ["name" => "Karen Woven Dress", "price" => "45,000", "cat" => "Women · Traditional", "img" => "2.jpg", "label" => "New"],
                    ["name" => "Traditional Woven Skirt", "price" => "40,000", "cat" => "Women · Woven", "img" => "3.jpg", "label" => "Popular"],
                    ["name" => "Modern Heritage Blouse", "price" => "28,000", "cat" => "Women · Blouses", "img" => "4.jpg", "label" => ""],
                    ["name" => "Karen Woven Bag", "price" => "15,000", "cat" => "Women · Accessories", "img" => "5.avif", "label" => ""],
                    ["name" => "Ceremonial Red Dress", "price" => "65,000", "cat" => "Women · Ceremony", "img" => "6.avif", "label" => "Sale"],
                    ["name" => "Handmade Woven Scarf", "price" => "18,000", "cat" => "Women · Accessories", "img" => "7.webp", "label" => ""],
                    ["name" => "Indigo Dyed Cotton Skirt", "price" => "38,000", "cat" => "Women · Skirts", "img" => "8.jpg", "label" => ""],
                    ["name" => "V-Neck Embroidered Blouse", "price" => "30,000", "cat" => "Women · Blouses", "img" => "9.jpg", "label" => ""],
                    ["name" => "Festival Weave Tunic", "price" => "55,000", "cat" => "Women · Traditional", "img" => "14.jpg", "label" => "New"],
                    ["name" => "Handmade Beaded Necklace", "price" => "12,000", "cat" => "Women · Accessories", "img" => "f3.jpg", "label" => ""]
                ];

                foreach ($womenProducts as $product) {
                    echo '<article class="product-card">';
                    echo '<div class="product-image">';
                    if (!empty($product['label'])) {
                        echo '<span class="product-label">' . $product['label'] . '</span>';
                    }
                    echo '<button class="wishlist" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>';
                    echo '<img src="uploads/women/' . $product['img'] . '" alt="' . htmlspecialchars($product['name']) . '">';
                    // Button stays inside product-image so it cleanly triggers on hover
                    echo '<button class="add-cart" data-product="' . htmlspecialchars($product['name']) . '" data-price="' . str_replace(',', '', $product['price']) . '">Add to Cart</button>';
                    echo '</div>';
                    
                    echo '<div class="product-info">';
                    echo '<p class="product-category">' . $product['cat'] . '</p>';
                    echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
                    echo '<p class="price">Ks ' . $product['price'] . '</p>';
                    echo '</div>';
                    echo '</article>';
                }
                ?>

            </div>
        </section>
    </main>

    

    <footer>
        <p>&copy; 2026 TraditionalStore. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');
            
            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                themeIcon.className = 'fa-solid fa-moon';
                themeText.textContent = 'Dark';
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                themeIcon.className = 'fa-solid fa-sun';
                themeText.textContent = 'Light';
                localStorage.setItem('theme', 'dark');
            }
        }

        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();

        // Function to update cart badge count in navbar
        function updateCartBadge() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            let totalCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            const badge = document.getElementById('cartCount');
            if (badge) {
                badge.textContent = totalCount;
            }
        }

        // Run badge count on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateCartBadge();

            const cartButtons = document.querySelectorAll('.add-cart');

            cartButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();

                    const name = button.getAttribute('data-product');
                    const price = parseFloat(button.getAttribute('data-price'));

                    let cart = JSON.parse(localStorage.getItem('cart')) || [];

                    const existingProduct = cart.find(item => item.name === name);
                    if (existingProduct) {
                        existingProduct.quantity += 1;
                    } else {
                        cart.push({
                            name: name,
                            price: price,
                            quantity: 1
                        });
                    }

                    localStorage.setItem('cart', JSON.stringify(cart));
                    
                    // Immediately update the badge counter in real-time
                    updateCartBadge();

                    alert(name + ' has been added to your cart!');
                });
            });
        });
    </script>
</body>
</html>