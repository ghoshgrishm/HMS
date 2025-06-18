<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinbadh Hospitals - Step into the finest healthcare</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #ffffff;
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Decorative stars */
        .star {
            position: absolute;
            color: #87ceeb;
            font-size: 24px;
            opacity: 0.7;
            animation: twinkle 3s ease-in-out infinite alternate;
        }

        .star-1 {
            top: 20px;
            left: 15%;
            animation-delay: 0s;
        }

        .star-2 {
            top: 30px;
            right: 20%;
            animation-delay: 1s;
        }

        .star-3 {
            bottom: 40px;
            left: 10%;
            animation-delay: 2s;
        }

        .star-4 {
            bottom: 60px;
            right: 15%;
            animation-delay: 0.5s;
        }

        @keyframes twinkle {
            0% { opacity: 0.3; transform: scale(1); }
            100% { opacity: 0.8; transform: scale(1.1); }
        }

        /* Header Navigation */
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 30px 0;
            position: relative;
            overflow: hidden;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="0.5" fill="rgba(255,255,255,0.05)"/><circle cx="80" cy="40" r="0.3" fill="rgba(255,255,255,0.03)"/><circle cx="40" cy="80" r="0.4" fill="rgba(255,255,255,0.04)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0 40px;
            position: relative;
            z-index: 2;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 300;
            color: white;
            letter-spacing: -1px;
            text-align: center;
            margin-bottom: 30px;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 60px;
            justify-content: center;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-menu a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 400;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 12px 0;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-menu a::after {
            content: '';
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 1px;
            background: #87ceeb;
            transition: width 0.3s ease;
        }

        .nav-menu a:hover {
            color: white;
        }

        .nav-menu a:hover::after {
            width: 100%;
        }

        /* Main Content Area */
        .main-content {
            background: #ffffff;
            position: relative;
            padding: 80px 20px 60px;
        }

        .content-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            min-height: 70vh;
        }

        /* Left side - Image */
        .image-section {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .main-image {
            width: 100%;
            max-width: 500px;
            height: 600px;
            background-image: url('https://images.unsplash.com/photo-1586773860418-d37222d8fce3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');
            background-size: cover;
            background-position: center;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(30, 60, 114, 0.15);
            position: relative;
            overflow: hidden;
        }

        .main-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(30, 60, 114, 0.1), transparent);
            border-radius: 20px;
        }

        /* Right side - Content */
        .content-section {
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            padding: 60px 50px;
            border-radius: 30px;
            position: relative;
            box-shadow: 0 10px 25px rgba(30, 60, 114, 0.08);
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            border-radius: 32px;
            z-index: -1;
            opacity: 0.1;
        }

        .content-title {
            font-family: 'Playfair Display', serif;
            font-size: 56px;
            font-weight: 300;
            color: #1e3c72;
            line-height: 1.1;
            margin-bottom: 30px;
            letter-spacing: -1px;
        }

        .content-title .highlight {
            font-style: italic;
            color: #2a5298;
            position: relative;
        }

        .content-subtitle {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 40px;
            line-height: 1.7;
            font-weight: 400;
        }

        .cta-button {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            padding: 16px 40px;
            border: none;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(30, 60, 114, 0.3);
            position: relative;
            overflow: hidden;
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s ease;
        }

        .cta-button:hover::before {
            left: 100%;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(30, 60, 114, 0.4);
        }

        /* Decorative Badge */
        .decorative-badge {
            position: absolute;
            bottom: -30px;
            right: -30px;
            width: 120px;
            height: 120px;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            box-shadow: 0 10px 25px rgba(30, 60, 114, 0.3);
            border: 4px solid white;
        }

        /* Services Section */
        .services-section {
            background: #ffffff;
            padding: 100px 20px;
            position: relative;
        }

        .services-title {
            text-align: center;
            margin-bottom: 80px;
        }

        .services-title h2 {
            font-family: 'Playfair Display', serif;
            font-size: 42px;
            font-weight: 300;
            color: #1e3c72;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }

        .services-title p {
            font-size: 18px;
            color: #64748b;
            max-width: 600px;
            margin: 0 auto;
        }

        .services-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 40px;
        }

        .service-card {
            background: #ffffff;
            padding: 50px 30px;
            border-radius: 25px;
            text-align: center;
            color: #1e3c72;
            box-shadow: 0 8px 32px rgba(30, 60, 114, 0.08);
            border: 2px solid #f1f5f9;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(30, 60, 114, 0.03), transparent);
            transition: left 0.6s ease;
        }

        .service-card:hover::before {
            left: 100%;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(30, 60, 114, 0.15);
            border-color: #87ceeb;
        }

        .service-icon {
            font-size: 48px;
            margin-bottom: 25px;
            opacity: 0.9;
            transition: all 0.3s ease;
            filter: drop-shadow(0 4px 8px rgba(30, 60, 114, 0.1));
        }

        .service-card:hover .service-icon {
            transform: scale(1.1);
            opacity: 1;
        }

        .service-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            margin-bottom: 18px;
            font-weight: 400;
            letter-spacing: -0.3px;
            color: #1e3c72;
        }

        .service-card p {
            line-height: 1.7;
            opacity: 0.8;
            font-size: 16px;
            font-weight: 400;
            color: #64748b;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .content-container {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .main-image {
                max-width: 400px;
                height: 500px;
            }

            .content-section {
                padding: 50px 40px;
            }

            .content-title {
                font-size: 46px;
            }
        }

        @media (max-width: 768px) {
            .nav-container {
                padding: 0 20px;
            }

            .nav-menu {
                gap: 30px;
                flex-wrap: wrap;
            }

            .nav-menu a {
                font-size: 12px;
                letter-spacing: 1px;
            }

            .logo {
                font-size: 36px;
                margin-bottom: 20px;
            }

            .main-content {
                padding: 60px 20px 40px;
            }

            .main-image {
                max-width: 100%;
                height: 400px;
            }

            .content-section {
                padding: 40px 30px;
                margin-top: 40px;
            }

            .content-title {
                font-size: 36px;
            }

            .content-subtitle {
                font-size: 16px;
            }

            .decorative-badge {
                width: 80px;
                height: 80px;
                font-size: 24px;
                bottom: -20px;
                right: -20px;
            }

            .services-container {
                gap: 30px;
            }

            .service-card {
                padding: 40px 25px;
            }

            .services-title h2 {
                font-size: 32px;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            border-radius: 6px;
            transition: background 0.3s ease;
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .mobile-toggle:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Decorative Stars -->
    <div class="star star-1">✦</div>
    <div class="star star-2">✧</div>
    <div class="star star-3">✦</div>
    <div class="star star-4">✧</div>

    <!-- Header Navigation -->
    <header class="header">
        <div class="nav-container">
            <div style="text-align: center; width: 100%;">
                <div class="logo">Sinbadh Hospitals</div>
                <nav>
                    <ul class="nav-menu">
                        <li><a href="#home">Home</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#services">Services</a></li>
                        <li><a href="index_staff.php">Hospital Staff</a></li>
                        <li><a href="patient/new_appointment.php">Book Appointment</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                    <button class="mobile-toggle">☰</button>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <section class="main-content">
        <div class="content-container">
            <!-- Left side - Image -->
            <div class="image-section">
                <div class="main-image"></div>
            </div>

            <!-- Right side - Content -->
            <div class="content-section">
                <h1 class="content-title">
                    Ready to experience the <span class="highlight">finest</span> healthcare?
                </h1>
                <p class="content-subtitle">
                    Elevate your health journey with our world-class medical facilities, 
                    expert healthcare professionals, and personalized treatment approaches 
                    designed for your complete well-being.
                </p>
                <button class="cta-button">Book a Call</button>
                
                <!-- Decorative Badge -->
                <div class="decorative-badge">
                    🏥
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <div class="services-title">
            <h2>Our Healthcare Services</h2>
            <p>Comprehensive medical care tailored to your needs with compassion and expertise</p>
        </div>
        
        <div class="services-container">
            <div class="service-card">
                <div class="service-icon">📋</div>
                <h3>About Us</h3>
                <p>Where compassion meets innovation - discover our story</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🩺</div>
                <h3>Services</h3>
                <p>From diagnostics to emergency care - approach our services</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🖼️</div>
                <h3>Gallery</h3>
                <p>Experience our services through the lens - visit our gallery</p>
            </div>

            <div class="service-card">
                <div class="service-icon">📞</div>
                <h3>Contact us</h3>
                <p>Your health is one click away - contact us today</p>
            </div>
        </div>
    </section>
</body>
</html>