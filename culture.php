<?php
// You can include any database connection or session handling here if needed
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cultural Heritage - Traditional Store</title>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS Variables for Light Mode (Default) */
        :root {
            --bg-color: #f8f9fa;
            --text-color: #212529;
            --header-bg: #ffffff;
            --card-bg: #ffffff;
            --card-text: #495057;
            --card-heading: #0033cc;
            --banner-bg: linear-gradient(135deg, #0033cc 0%, #ff0000 100%);
            --banner-text: #ffffff;
            --highlight-bg: #ffffff;
            --highlight-border: #0033cc;
            --highlight-text: #495057;
            --footer-bg: #0033cc;
            --footer-text: #ffffff;
            --shadow-color: rgba(0,0,0,0.08);
        }

        /* CSS Variables for Dark Mode (Black & Dark Red) */
        [data-theme="dark"] {
            --bg-color: #0a0a0a;
            --text-color: #e0e0e0;
            --header-bg: #121212;
            --card-bg: #161616;
            --card-text: #b0b0b0;
            --card-heading: #ff3333;
            --banner-bg: linear-gradient(135deg, #1a0000 0%, #4d0000 100%);
            --banner-text: #ffffff;
            --highlight-bg: #161616;
            --highlight-border: #800000;
            --highlight-text: #b0b0b0;
            --footer-bg: #121212;
            --footer-text: #ffffff;
            --shadow-color: rgba(255,0,0,0.05);
        }

        /* Base & Reset Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        a {
            text-decoration: none;
            color: inherit;
        }

        /* Navigation Header */
        header {
            background: var(--header-bg);
            box-shadow: 0 2px 8px var(--shadow-color);
            position: sticky;
            top: 0;
            z-index: 1000;
            border-bottom: 4px solid #800000;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
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
            gap: 25px;
            align-items: center;
        }
        .nav-links a {
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav-links a:hover, .nav-links a.active {
            color: #cc0000;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            background: none;
            border: 2px solid #800000;
            color: var(--text-color);
            padding: 6px 12px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .theme-toggle:hover {
            background: #800000;
            color: #ffffff;
        }

        /* Culture Page Banner */
        .page-banner {
            background: var(--banner-bg);
            color: var(--banner-text);
            text-align: center;
            padding: 75px 20px;
            margin-bottom: 40px;
            box-shadow: inset 0 0 30px rgba(0,0,0,0.3);
            border-bottom: 6px solid #800000;
        }
        .page-banner h1 {
            font-size: 2.8rem;
            margin-bottom: 15px;
            letter-spacing: 1px;
        }
        .page-banner p {
            font-size: 1.15rem;
            max-width: 700px;
            margin: 0 auto;
            opacity: 0.95;
            line-height: 1.6;
        }

        /* Content Sections */
        .section {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px 60px 20px;
        }

        .culture-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .culture-card {
            background: var(--card-bg);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px var(--shadow-color);
            border-top: 4px solid #b30000;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .culture-card:nth-child(even) {
            border-top-color: #ff3333;
        }
        .culture-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(128,0,0,0.2);
        }

        .culture-img {
            width: 100%;
            height: 220px;
            background-color: #222;
            overflow: hidden;
        }
        .culture-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .culture-card:hover .culture-img img {
            transform: scale(1.05);
        }

        .culture-body {
            padding: 25px;
        }
        .culture-body h3 {
            font-size: 1.3rem;
            color: var(--card-heading);
            margin-bottom: 12px;
        }
        .culture-body p {
            font-size: 0.95rem;
            color: var(--card-text);
            line-height: 1.6;
        }

        /* Heritage Highlight Box */
        .highlight-box {
            background: var(--highlight-bg);
            border: 2px solid var(--highlight-border);
            border-radius: 8px;
            padding: 40px;
            text-align: center;
            margin-top: 20px;
            box-shadow: 0 4px 15px var(--shadow-color);
        }
        .highlight-box h2 {
            color: var(--card-heading);
            font-size: 2rem;
            margin-bottom: 15px;
        }
        .highlight-box p {
            color: var(--highlight-text);
            font-size: 1.05rem;
            max-width: 800px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* Footer */
        footer {
            background: var(--footer-bg);
            color: var(--footer-text);
            text-align: center;
            padding: 35px 20px;
            margin-top: 40px;
            border-top: 5px solid #800000;
        }
        footer p {
            font-size: 0.9rem;
            opacity: 0.95;
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
                <li><a href="women.php">Women</a></li>
                <li><a href="men.php">Men</a></li>
                <li><a href="children.php">Children</a></li>
                <li><a href="culture.php" class="active" style="color: #cc0000;">Culture</a></li>
                <li><a href="cart.php"><i class="fa-solid fa-cart-shopping"></i> Cart</a></li>
                <li>
                    <button class="theme-toggle" id="themeToggleBtn" onclick="toggleTheme()">
                        <i class="fa-solid fa-moon" id="themeIcon"></i> <span id="themeText">Dark</span>
                    </button>
                </li>
            </ul>
        </div>
    </header>

    <!-- Page Banner -->
    <div class="page-banner">
        <h1>Our Cultural Heritage</h1>
        <p>Celebrating the rich history, generational weaving artistry, and vibrant traditions preserved through every handcrafted thread.</p>
    </div>

    <!-- Main Content -->
    <main class="section">
        <div class="culture-grid">
            
            <!-- Tradition 1 -->
            <article class="culture-card">
                <div class="culture-img">
                    <img src="uploads/cover.jpg" alt="Traditional Weaving">
                </div>
                <div class="culture-body">
                    <h3>The Art of Backstrap Weaving</h3>
                    <p>Passed down from generation to generation, backstrap weaving is a cornerstone of our heritage. Using vibrant threads of crimson red, royal blue, and pure white, artisans create intricate geometric patterns that tell stories of community and nature.</p>
                </div>
            </article>

            <!-- Tradition 2 -->
            <article class="culture-card">
                <div class="culture-img">
                    <img src="uploads/w7.jpg" alt="Festive Attire">
                </div>
                <div class="culture-body">
                    <h3>Festivals & Ceremonial Attire</h3>
                    <p>During momentous seasonal celebrations, community members don traditional garments adorned with special symbolic weaves. Each color and pattern choice represents unity, status, and deep respect for ancestral roots.</p>
                </div>
            </article>

            <!-- Tradition 3 -->
            <article class="culture-card">
                <div class="culture-img">
                    <img src="uploads/homeimage.jpg" alt="Handcrafted Details">
                </div>
                <div class="culture-body">
                    <h3>Sustainable Craftsmanship</h3>
                    <p>We work directly with rural artisan communities who utilize sustainably sourced cotton and natural dyes. Every piece in our collection honors time-tested techniques while supporting local families and keeping ancient crafts alive.</p>
                </div>
            </article>

        </div>

        <!-- Highlight Section -->
        <div class="highlight-box">
            <h2>Preserving Identity Through Fashion</h2>
            <p>Our mission goes beyond clothing—it is about preserving identity. By combining classic motifs with clean modern tailoring, we ensure that the beauty of our cultural heritage can be worn with pride in everyday life, connecting past traditions with future generations.</p>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; <?php echo date("Y"); ?> TraditionalStore. Celebrating Heritage & Culture.</p>
    </footer>

    <!-- JavaScript for Theme Toggle -->
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

        // Check for saved user preference on load
        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                document.getElementById('themeIcon').className = 'fa-solid fa-sun';
                document.getElementById('themeText').textContent = 'Light';
            }
        });
    </script>
</body>
</html>