<?php
// index.php
session_start();
$pageTitle = isset($pageTitle) ? $pageTitle : "KARENIA | Traditional Karen Attire";
$isLoggedIn = isset($_SESSION['user_id']) ? 'true' : 'false';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="css/style.css">
    
    <style>
        /* Light Mode Theme Variables (Inspired by the Blue Traditional Backpack Design) */
        :root {
            --bg-color: #f4f6fb;          
            --text-color: #0f172a;        
            --card-bg: #ffffff;            
            --nav-bg: #ffffff;             
            --footer-bg: #0f172a;          
            --footer-text: #ffffff;
            --border-color: #3b82f6;      
            --accent-primary: #180673;    
            --accent-secondary: #dc2626;   
        }

        /* Dark Mode  */
        [data-theme="dark"] {
            --bg-color: #000000;        
            --text-color: #ffffff;      
            --card-bg: #000000;          
            --nav-bg: #000000; 
            --nav-text: #ffffff;        
            --footer-bg: #000000;      
            --footer-text: #ffffff;
            --border-color: #990000;  
            --accent-primary: #b30000; 
            --accent-secondary: #ff3333;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        
        [data-theme="dark"] *, 
        [data-theme="dark"] *:before, 
        [data-theme="dark"] *:after {
            background-color: transparent !important;
        }

        
        [data-theme="dark"] body,
        [data-theme="dark"] .navbar,
        [data-theme="dark"] .mobile-menu,
        [data-theme="dark"] .search-overlay,
        [data-theme="dark"] .footer,
        [data-theme="dark"] .category-card, 
        [data-theme="dark"] .product-card, 
        [data-theme="dark"] .review-card,
        [data-theme="dark"] .style-item,
        [data-theme="dark"] input, 
        [data-theme="dark"] textarea {
            background-color: #000000 !important;
        }

        /* Borders & Framing using the Dark Red Aesthetic */
        [data-theme="dark"] .navbar,
        [data-theme="dark"] .mobile-menu,
        [data-theme="dark"] .search-overlay {
            border-bottom: 2px solid #990000 !important;
        }

        [data-theme="dark"] .footer {
            border-top: 3px solid #990000 !important;
        }

        [data-theme="dark"] .category-card, 
        [data-theme="dark"] .product-card, 
        [data-theme="dark"] .review-card,
        [data-theme="dark"] .style-item,
        [data-theme="dark"] input, 
        [data-theme="dark"] textarea {
            border: 1px solid #990000 !important;
        }

        /* Solid High-Contrast Dark Red Buttons & Badges */
        [data-theme="dark"] .product-label,
        [data-theme="dark"] .primary-btn,
        [data-theme="dark"] .add-cart,
        [data-theme="dark"] .newsletter button,
        [data-theme="dark"] button[type="submit"] {
            background-color: #990000 !important;
            color: #ffffff !important;
            border: 1px solid #ff3333 !important;
        }

        /* Theme Toggle Button Styling */
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
            transition: all 0.3s ease;
        }
        .theme-toggle-btn:hover {
            border-color: var(--accent-secondary);
            color: var(--accent-secondary);
        }

            /* Logo Styling */
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
            font-family: 'Inter', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text-dark);
        }

        .logo span {
            color: var(--flag-red);
        }
    </style>
</head>
<body>

    <!-- Announcement Bar -->
    <div class="announcement">
        Free delivery on orders over Ks 100,000
    </div>

    <!-- Navigation -->
    <header class="navbar" id="navbar">
        <div class="nav-container">

             <a href="index.php" class="logo-container">
                <img src="uploads/logo.png" alt="K Attire Logo">
                 <div class="logo">K <span>ATTIRE</span></div>
            </a>

            <nav class="nav-menu">
                <a href="index.php" class="active">Home</a>

                <div class="nav-dropdown">
                    <a href="women.php">Women <i class="fa-solid fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="women.php">All Women</a>
                        <a href="#">Traditional Dresses</a>
                        <a href="#">Blouses</a>
                        <a href="#">Skirts</a>
                        <a href="#">Accessories</a>
                    </div>
                </div>

                <div class="nav-dropdown">
                    <a href="men.php">Men <i class="fa-solid fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="men.php">All Men</a>
                        <a href="#">Traditional Shirts</a>
                        <a href="#">Traditional Pants</a>
                        <a href="#">Accessories</a>
                    </div>
                </div>

                <a href="children.php">Children</a>

                <div class="nav-dropdown">
                    <a href="collection.php">Collections <i class="fa-solid fa-chevron-down"></i></a>
                    <div class="dropdown-menu">
                        <a href="#">Traditional</a>
                        <a href="#">Modern Karen</a>
                        <a href="#">Ceremony</a>
                        <a href="#">Everyday</a>
                    </div>
                </div>

                <a href="culture.php">Culture</a>
                <a href="about.php">About</a>
            </nav>

            <div class="nav-actions">
                <!-- Theme Toggle Button -->
                <button class="theme-toggle-btn" id="themeToggleBtn" onclick="toggleTheme()" aria-label="Toggle theme">
                    <i class="fa-solid fa-moon" id="themeIcon"></i> <span id="themeText">Dark</span>
                </button>

                <!-- Dynamic Account / Login Icon Routing -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="account.php" class="icon-btn" aria-label="Account" title="Logged in as <?php echo htmlspecialchars($_SESSION['first_name'] ?? ''); ?>">
                        <i class="fa-solid fa-user-check" style="color: var(--accent-secondary);"></i>
                    </a>
                    <a href="logout.php" class="icon-btn" aria-label="Logout" title="Logout" style="font-size: 0.85rem; margin-left: 2px;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </a>
                <?php else: ?>
                    <a href="login.php" class="icon-btn" aria-label="Account" title="Sign In">
                        <i class="fa-regular fa-user"></i>
                    </a>
                <?php endif; ?>

                <a href="cart.php" class="cart-btn" aria-label="Shopping Cart">
                    <i class="fa-solid fa-bag-shopping"></i>
                    <span class="cart-count" id="cartCount">0</span>
                </a>

                <button class="menu-btn" id="menuBtn" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>

        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="index.php">Home</a>
        <a href="women.php">Women</a>
        <a href="men.php">Men</a>
        <a href="children.php">Children</a>
        <a href="collection.php">Collections</a>
        <a href="culture.php">Culture</a>
        <a href="about.php">About</a>
    </div>

    <!-- Search -->
        <div class="search-box">
            <p>SEARCH HERE</p>
            <div class="search-input">
                <input type="text" id="searchInput" placeholder="Search traditional attire...">
                <button aria-label="Submit Search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
        </div>

    <!-- Main Content -->
    <main>

        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-image"></div>
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <p class="hero-small">
                    KAREN TRADITIONAL ATTIRE
                </p>
                <h1>
                    Preserving Heritage<br>
                    <span>Through Every Thread</span>
                </h1>
                <p class="hero-description">
                    Discover timeless Karen clothing inspired by
                    tradition, craftsmanship and culture.
                </p>
                <a href="collection.php" class="primary-btn">
                    Explore Collection
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div class="hero-scroll">
                <span></span>
                Scroll to explore
            </div>
        </section>

        <!-- Collection Categories -->
        <section class="categories section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">SHOP BY CATEGORY</p>
                    <h2>
                        The Collection
                    </h2>
                </div>
                <a href="collections.php" class="view-link">
                    View All
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="category-grid">
                <a href="women.php" class="category-card">
                    <div class="category-image women-image"></div>
                    <div class="category-info">
                        <span>01</span>
                        <h3>Women</h3>
                        <p>Traditional elegance</p>
                        <span class="category-arrow"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                    </div>
                </a>

                <a href="men.php" class="category-card">
                    <div class="category-image men-image"></div>
                    <div class="category-info">
                        <span>02</span>
                        <h3>Men</h3>
                        <p>Heritage collection</p>
                        <span class="category-arrow"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                    </div>
                </a>

                <a href="children.php" class="category-card">
                    <div class="category-image children-image"></div>
                    <div class="category-info">
                        <span>03</span>
                        <h3>Children</h3>
                        <p>Little traditions</p>
                        <span class="category-arrow"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>
                    </div>
                </a>
            </div>
        </section>

        <!-- Featured Products -->
        <section class="featured section">
            <div class="section-heading centered">
                <p class="eyebrow">HANDPICKED FOR YOU</p>
                <h2>Featured Pieces</h2>
                <p class="section-description">
                    Discover our collection of carefully selected
                    Karen traditional attire.
                </p>
            </div>

            <div class="product-grid">
                <!-- Product 1 -->
                <article class="product-card">
                    <div class="product-image">
                        <span class="product-label">New</span>
                        <button class="wishlist auth-action-btn" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        <img src="uploads/11.jpg" alt="Traditional Karen woven dress">
                        <button class="add-cart auth-action-btn" data-id="1" data-name="Karen Woven Dress" data-price="45000">Add to Cart</button>
                    </div>
                    <div class="product-info">
                        <p class="product-category">Women · Traditional</p>
                        <h3>Karen Woven Dress</h3>
                        <p class="price">Ks 45,000</p>
                    </div>
                </article>

                <!-- Product 2 -->
                <article class="product-card">
                    <div class="product-image">
                        <button class="wishlist auth-action-btn" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        <img src="uploads/men/m11.jpg" alt="Traditional Karen men's shirt">
                        <button class="add-cart auth-action-btn" data-id="2" data-name="Karen Heritage Shirt" data-price="35000">Add to Cart</button>
                    </div>
                    <div class="product-info">
                        <p class="product-category">Men · Traditional</p>
                        <h3>Karen Heritage Shirt</h3>
                        <p class="price">Ks 35,000</p>
                    </div>
                </article>

                <!-- Product 3 -->
                <article class="product-card">
                    <div class="product-image">
                        <span class="product-label">Popular</span>
                        <button class="wishlist auth-action-btn" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        <img src="uploads/d5.jpg" alt="Karen traditional woven skirt">
                        <button class="add-cart auth-action-btn" data-id="3" data-name="Traditional Woven Skirt" data-price="40000">Add to Cart</button>
                    </div>
                    <div class="product-info">
                        <p class="product-category">Women · Woven</p>
                        <h3>Traditional Woven Skirt</h3>
                        <p class="price">Ks 40,000</p>
                    </div>
                </article>

                <!-- Product 4 -->
                <article class="product-card">
                    <div class="product-image">
                        <button class="wishlist auth-action-btn" aria-label="Add to wishlist"><i class="fa-regular fa-heart"></i></button>
                        <img src="uploads/b7.jpg" alt="Karen woven traditional bag">
                        <button class="add-cart auth-action-btn" data-id="4" data-name="Karen Woven Bag" data-price="15000">Add to Cart</button>
                    </div>
                    <div class="product-info">
                        <p class="product-category">Accessories</p>
                        <h3>Karen Woven Bag</h3>
                        <p class="price">Ks 15,000</p>
                    </div>
                </article>
            </div>

            <div class="center-button">
                <a href="collection.php" class="secondary-btn">
                    Shop All Products
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <!-- Culture Feature -->
        <section class="culture-feature">
            <div class="culture-image"></div>
            <div class="culture-content">
                <p class="eyebrow">THE ART OF WEAVING</p>
                <h2>
                    Woven With<br>
                    <em>Meaning</em>
                </h2>
                <p>
                    From traditional patterns to carefully chosen colors,
                    Karen textiles carry stories of identity, family and
                    heritage.
                </p>
                <a href="culture.php" class="light-btn">
                    Explore Our Heritage
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </section>

        <!-- Shop By Style -->
        <section class="styles section">
            <div class="section-heading centered">
                <p class="eyebrow">FIND YOUR STYLE</p>
                <h2>Shop By Style</h2>
            </div>

            <div class="style-grid">
                <a href="#" class="style-item">
                    <span>Traditional</span>
                    <strong>01</strong>
                </a>
                <a href="#" class="style-item">
                    <span>Modern Karen</span>
                    <strong>02</strong>
                </a>
                <a href="#" class="style-item">
                    <span>Ceremony</span>
                    <strong>03</strong>
                </a>
                <a href="#" class="style-item">
                    <span>Everyday</span>
                    <strong>04</strong>
                </a>
            </div>
        </section>

        <!-- Reviews -->
        <section class="reviews section">
            <div class="section-heading centered">
                <p class="eyebrow">CUSTOMER STORIES</p>
                <h2>Loved By Our Community</h2>
            </div>

            <div class="review-grid">
                <article class="review-card">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p>
                        "The quality is beautiful and the traditional
                        details make the dress feel very special."
                    </p>
                    <div class="review-author">
                        <div class="avatar">M</div>
                        <div>
                            <strong>May Thu</strong>
                            <span>Verified Customer</span>
                        </div>
                    </div>
                </article>

                <article class="review-card featured-review">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p>
                        "I love how traditional Karen patterns are
                        presented in such a modern way."
                    </p>
                    <div class="review-author">
                        <div class="avatar">S</div>
                        <div>
                            <strong>Su Su</strong>
                            <span>Verified Customer</span>
                        </div>
                    </div>
                </article>

                <article class="review-card">
                    <div class="stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <p>
                        "A beautiful collection that makes me proud
                        to wear Karen traditional clothing."
                    </p>
                    <div class="review-author">
                        <div class="avatar">N</div>
                        <div>
                            <strong>Naw Paw</strong>
                            <span>Verified Customer</span>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="newsletter">
            <div class="newsletter-pattern"></div>
            <div class="newsletter-content">
                <p class="eyebrow">STAY CONNECTED</p>
                <h2>Join Our Community</h2>
                <p>
                    Discover new collections, Karen culture stories
                    and special offers.
                </p>
                <form id="newsletterForm">
                    <input type="email" placeholder="Enter your email address" required>
                    <button type="submit">Subscribe</button>
                </form>
                <small id="newsletterMessage"></small>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-main">
            <div class="footer-brand">
                <a href="index.php" class="footer-logo">
                    <span>K </span>ATTIRE
                </a>
                <p>
                    Celebrating Karen heritage through traditional clothing and contemporary design.
                </p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                </div>
            </div>

            <div class="footer-column">
                <h4>Shop</h4>
                <a href="women.php">Women</a>
                <a href="men.php">Men</a>
                <a href="children.php">Children</a>
                <a href="collections.php">Collections</a>
            </div>

            <div class="footer-column">
                <h4>Explore</h4>
                <a href="culture.php">Karen Culture</a>
                <a href="about.php">Our Story</a>
                <a href="reviews.php">Reviews</a>
                <a href="contact.php">Contact</a>
            </div>

            <div class="footer-column">
                <h4>Help</h4>
                <a href="#">FAQ</a>
                <a href="#">Shipping</a>
                <a href="#">Returns</a>
                <a href="#">Privacy Policy</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>
                &copy; <?php echo date('Y'); ?> KATTIRE. All Rights Reserved.
            </p>
            <p>
                Made with respect for Karen heritage.
            </p>
        </div>
    </footer>

    <!-- Toast -->
    <div class="toast" id="toast">
        <i class="fa-solid fa-check"></i>
        <p>Added to cart</p>
    </div>

    <script src="js/main.js"></script>

    <!-- Authentication and Theme Control Script -->
    <script>
        const userIsLoggedIn = <?php echo $isLoggedIn; ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // Intercept protected action clicks (Add to Cart, Wishlist) if guest
            const protectedButtons = document.querySelectorAll('.auth-action-btn, .add-cart, .wishlist');
            protectedButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    if (!userIsLoggedIn) {
                        e.preventDefault();
                        e.stopPropagation();
                        window.location.href = 'register.php';
                    }
                });
            });
        });

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

        // Instantly apply saved preference on load to avoid screen flash
        (function() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();

        // Update button text/icons when DOM content is fully loaded
        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            const themeIcon = document.getElementById('themeIcon');
            const themeText = document.getElementById('themeText');
            if (savedTheme === 'dark' && themeIcon && themeText) {
                themeIcon.className = 'fa-solid fa-sun';
                themeText.textContent = 'Light';
            }
        });
    </script>
</body>
</html>