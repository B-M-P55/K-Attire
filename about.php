<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - KAttire</title>
    <!-- Google Fonts for Modern Clean Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --flag-blue: #1b00b3;
            --flag-red: #e60000;
            --pure-white: #ffffff;
            --bg-ivory: #fafafa;
            --text-dark: #111827;
            --text-muted: #4b5563;
            --card-bg: #ffffff;
            --nav-bg: rgba(255, 255, 255, 0.9);
            --border-color: rgba(2, 4, 140, 0.1);
            --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        /* Dark Mode Theme Variables */
        [data-theme="dark"] {
            --flag-blue: #3b82f6;
            --flag-red: #ff3333;
            --pure-white: #000000;
            --bg-ivory: #000000;
            --text-dark: #ffffff;
            --text-muted: #a1a1aa;
            --card-bg: #000000;
            --nav-bg: rgba(0, 0, 0, 0.85);
            --border-color: #3b82f6;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-ivory);
            color: var(--text-dark);
            line-height: 1.7;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* Force pure black structural blocks in dark mode */
        [data-theme="dark"] body,
        [data-theme="dark"] header,
        [data-theme="dark"] footer,
        [data-theme="dark"] .value-card,
        [data-theme="dark"] .about-image {
            background-color: #000000 !important;
        }

        /* Luxurious Sticky Header */
        header {
            background: var(--nav-bg);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            transition: background 0.3s ease;
        }

        .nav-container {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
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

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
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

        /* Theme Toggle Button Styling */
        .theme-toggle-btn {
            background: none;
            border: 1.5px solid var(--flag-blue);
            color: var(--text-dark);
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            transition: var(--transition);
        }
        
        .theme-toggle-btn:hover {
            border-color: var(--flag-red);
            color: var(--flag-red);
        }

        /* Modern Luxury Hero Banner */
        .page-banner {
            background: linear-gradient(135deg, rgba(3, 63, 246, 0.95) 0%, rgba(230, 0, 0, 0.9) 100%);
            color: #ffffff;
            text-align: center;
            padding: 110px 20px;
            margin-bottom: 70px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-bottom: 6px solid var(--flag-blue);
        }

        [data-theme="dark"] .page-banner {
            border-bottom: 6px solid var(--flag-red);
        }

        .page-banner-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .page-banner h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .page-banner p {
            font-size: 1.15rem;
            font-weight: 300;
            opacity: 0.95;
            line-height: 1.8;
        }

        /* Main Content Layout */
        .section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 30px 80px 30px;
        }

        /* Story Grid */
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            margin-bottom: 80px;
        }

        .about-text h2 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--flag-blue);
            margin-bottom: 20px;
        }

        .about-text p {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 20px;
            font-weight: 400;
        }

        .about-image {
            width: 100%;
            height: 400px;
            background-color: #e5e7eb;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            border: 1px solid var(--border-color);
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Values Cards */
        .values-title {
            text-align: center;
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--flag-blue);
            margin-bottom: 40px;
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
        }

        .value-card {
            background: var(--card-bg);
            padding: 40px 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            border-top: 4px solid var(--flag-blue);
            border-left: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition);
        }

        .value-card:nth-child(even) {
            border-top-color: var(--flag-red);
        }

        .value-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
        }

        .value-card i {
            font-size: 2.2rem;
            color: var(--flag-blue);
            margin-bottom: 20px;
        }

        .value-card:nth-child(even) i {
            color: var(--flag-red);
        }

        .value-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .value-card p {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.7;
        }

        /* Footer Style */
        footer {
            background: var(--flag-blue);
            color: #ffffff;
            padding: 40px 20px;
            margin-top: 80px;
            border-top: 5px solid var(--flag-red);
            transition: background 0.3s ease;
        }

        [data-theme="dark"] footer {
            background: #000000;
            border-top: 5px solid var(--flag-blue);
            border-bottom: 1px solid #3b82f6;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            text-align: center;
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

        footer p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .about-grid {
                grid-template-columns: 1fr;
                gap: 40px;
            }
            .page-banner h1 {
                font-size: 2.3rem;
            }
            .nav-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Luxurious Navigation Header with Logo -->
    <header>
        <div class="nav-container">
            <!-- Brand Logo & Name -->
            <a href="index.php" class="logo-container">
                <img src="uploads/logo.png" alt="KAttire Logo">
                <div class="logo">K<span>Attire</span></div>
            </a>

            <!-- Navigation Links -->
            <ul class="nav-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="women.php">Women</a></li>
                <li><a href="men.php">Men</a></li>
                <li><a href="children.php">Children</a></li>
                <li><a href="culture.php">Culture</a></li>
                <li><a href="about.php" class="active" style="color: var(--flag-red);">About</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                <li>
                    <!-- Theme Toggle Button -->
                    <button class="theme-toggle-btn" id="themeToggleBtn" onclick="toggleTheme()" aria-label="Toggle theme">
                        <i class="fa-solid fa-moon" id="themeIcon"></i> <span id="themeText">Dark</span>
                    </button>
                </li>
            </ul>
        </div>
    </header>

    <!-- Modern About Banner -->
    <div class="page-banner">
        <div class="page-banner-content">
            <h1>Our Story & Mission</h1>
            <p>Bridging timeless heritage and modern luxury through authentic craftsmanship, handcrafted garments, and deep-rooted cultural pride.</p>
        </div>
    </div>

    <!-- Main Content Section -->
    <main class="section">
        <!-- Story Section -->
        <div class="about-grid">
            <div class="about-text">
                <h2>Preserving Heritage Through Every Stitch</h2>
                <p>Founded with a deep passion for cultural preservation, KAttire works hand-in-hand with skilled rural artisan communities. Every textile we offer reflects generations of traditional backstrap weaving techniques, passing down stories of resilience, artistry, and identity.</p>
                <p>We strive to bring authentic heritage fashion into contemporary wardrobes, ensuring that traditional motifs can be worn with utmost grace and pride in everyday modern life.</p>
            </div>
            <div class="about-image">
                <img src="uploads/logo.png" alt="Traditional Weaving Craftsmanship">
            </div>
        </div>

        <!-- Core Values Section -->
        <h2 class="values-title">What We Stand For</h2>
        <div class="values-grid">
            
            <div class="value-card">
                <i class="fa-solid fa-hands-holding-child"></i>
                <h3>Authentic Craftsmanship</h3>
                <p>Every piece is carefully handcrafted using time-honored weaving methods preserved across generations of master artisans.</p>
            </div>

            <div class="value-card">
                <i class="fa-solid fa-seedling"></i>
                <h3>Sustainable Practices</h3>
                <p>We champion eco-friendly materials and natural dyes, ensuring our creations respect both our cultural roots and the environment.</p>
            </div>

            <div class="value-card">
                <i class="fa-solid fa-heart-circle-check"></i>
                <h3>Empowering Communities</h3>
                <p>By connecting rural weavers directly with global admirers, we support local livelihoods and keep ancient textile traditions thriving.</p>
            </div>

        </div>
    </main>

    <!-- Footer with Logo -->
    <footer>
        <div class="footer-container">
            <div class="footer-logo">
                <img src="uploads/logo.png" alt="KAttire Logo">
                <span>KAttire</span>
            </div>
            <p>&copy; 2026 KAttire. Celebrating Heritage & Culture with Modern Elegance.</p>
        </div>
    </footer>

    <!-- Theme Toggle & Persistence Script -->
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