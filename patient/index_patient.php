<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinbadh Hospitals - Patient Portal</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            line-height: 1.6;
            color: #334155;
        }

        /* Header Navigation */
        .header {
            background-color: #ffffff;
            color: #334155;
            padding: 20px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid #e2e8f0;
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #2563eb;
            letter-spacing: -0.025em;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 40px;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-menu a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .nav-menu a:hover {
            color: #2563eb;
            background-color: #f1f5f9;
        }

        /* Main Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            text-align: center;
            padding: 80px 20px 60px;
        }

        .hero-content h1 {
            font-size: 56px;
            margin-bottom: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .hero-content p {
            font-size: 24px;
            margin-bottom: 40px;
            opacity: 0.9;
            font-weight: 400;
        }

        /* Patient Selection Section */
        .patient-selection {
            background-color: #ffffff;
            padding: 80px 20px;
            text-align: center;
        }

        .selection-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .selection-title {
            font-size: 42px;
            color: #1e293b;
            margin-bottom: 24px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .selection-subtitle {
            font-size: 20px;
            color: #64748b;
            margin-bottom: 60px;
            line-height: 1.7;
            max-width: 700px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 60px;
        }

        .selection-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 32px;
            max-width: 800px;
            margin: 0 auto;
        }

        .selection-button {
            background: #ffffff;
            color: #334155;
            border: 2px solid #e2e8f0;
            padding: 48px 32px;
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 320px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .selection-button:hover {
            border-color: #2563eb;
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.25), 0 0 0 1px rgba(37, 99, 235, 0.05);
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        }

        .selection-button:active {
            transform: translateY(-4px) scale(1.01);
        }

        .selection-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.08), transparent);
            transition: left 0.6s ease;
        }

        .selection-button:hover::before {
            left: 100%;
        }

        .selection-button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at var(--mouse-x, 50%) var(--mouse-y, 50%), rgba(37, 99, 235, 0.1) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }

        .selection-button:hover::after {
            opacity: 1;
        }

        .button-icon {
            font-size: 56px;
            margin-bottom: 20px;
            color: #2563eb;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 4px 8px rgba(37, 99, 235, 0.1));
        }

        .selection-button:hover .button-icon {
            transform: scale(1.15) rotate(5deg);
            filter: drop-shadow(0 8px 16px rgba(37, 99, 235, 0.2));
        }

        .button-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #1e293b;
            transition: color 0.3s ease;
            text-align: center;
        }

        .selection-button:hover .button-title {
            color: #2563eb;
        }

        .button-description {
            font-size: 14px;
            color: #64748b;
            text-align: center;
            line-height: 1.5;
            transition: color 0.3s ease;
        }

        .selection-button:hover .button-description {
            color: #475569;
        }

        /* SLIDESHOW SECTION */
        .slideshow-section {
            background-color: #f8fafc;
            padding: 80px 20px;
        }

        .slideshow-container {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .slide {
            display: none;
            position: relative;
            width: 100%;
            height: 500px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .slide.active {
            display: block;
        }

        .slide:nth-child(1) {
            background-image: url('https://images.unsplash.com/photo-1576091160550-2173dba999ef?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(2) {
            background-image: url('https://images.unsplash.com/photo-1559757175-0eb30cd8c063?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide:nth-child(3) {
            background-image: url('https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
        }

        .slide-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.8), rgba(29, 78, 216, 0.6));
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .slide-content {
            color: white;
            max-width: 600px;
            padding: 40px;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.8s ease;
        }

        .slide.active .slide-content {
            transform: translateY(0);
            opacity: 1;
        }

        .slide-content h2 {
            font-size: 36px;
            margin-bottom: 20px;
            font-weight: 700;
            letter-spacing: -0.025em;
            animation: slideInUp 1s ease 0.3s both;
        }

        .slide-content p {
            font-size: 18px;
            line-height: 1.7;
            opacity: 0.95;
            animation: slideInUp 1s ease 0.6s both;
        }

        @keyframes slideInUp {
            from {
                transform: translateY(30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Navigation Arrows */
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.9);
            color: #334155;
            border: none;
            padding: 16px 20px;
            cursor: pointer;
            font-size: 20px;
            transition: all 0.2s ease;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .prev {
            left: 24px;
        }

        .next {
            right: 24px;
        }

        .prev:hover, .next:hover {
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-50%) scale(1.05);
        }

        /* Slide Indicators */
        .slide-indicators {
            text-align: center;
            padding: 32px 0 0;
        }

        .indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #cbd5e1;
            margin: 0 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .indicator.active {
            background: #2563eb;
            transform: scale(1.2);
        }

        .indicator:hover {
            background: #64748b;
        }

        /* Services Section */
        .services-section {
            background-color: #ffffff;
            padding: 80px 20px;
        }

        .services-title {
            text-align: center;
            font-size: 42px;
            color: #1e293b;
            margin-bottom: 60px;
            font-weight: 700;
            letter-spacing: -0.025em;
        }

        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
        }

        .service-card {
            background: #ffffff;
            padding: 40px 32px;
            border-radius: 16px;
            text-align: center;
            color: #334155;
            border: 1px solid #e2e8f0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.05) 0%, rgba(37, 99, 235, 0.02) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-12px) scale(1.03);
            box-shadow: 0 25px 50px -12px rgba(37, 99, 235, 0.15), 0 0 0 1px rgba(37, 99, 235, 0.1);
            border-color: #2563eb;
        }

        .service-card:hover::before {
            opacity: 1;
        }

        .service-card:hover .service-icon {
            transform: scale(1.2) rotate(10deg);
            filter: drop-shadow(0 8px 16px rgba(37, 99, 235, 0.3));
        }

        .service-icon {
            font-size: 48px;
            margin-bottom: 24px;
            color: #2563eb;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            filter: drop-shadow(0 4px 8px rgba(37, 99, 235, 0.1));
            position: relative;
            z-index: 1;
        }

        .service-card h3 {
            font-size: 24px;
            margin-bottom: 16px;
            font-weight: 600;
            color: #1e293b;
            transition: color 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .service-card:hover h3 {
            color: #2563eb;
        }

        .service-card p {
            line-height: 1.6;
            color: #64748b;
            font-size: 16px;
            transition: color 0.3s ease;
            position: relative;
            z-index: 1;
        }

        .service-card:hover p {
            color: #475569;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hero-content h1 {
                font-size: 40px;
            }

            .hero-content p {
                font-size: 20px;
            }

            .selection-title {
                font-size: 32px;
            }

            .selection-subtitle {
                font-size: 18px;
            }

            .selection-buttons {
                grid-template-columns: 1fr;
                gap: 20px;
                max-width: 360px;
            }

            .selection-button {
                height: 280px;
                padding: 36px 28px;
            }

            .slide {
                height: 400px;
            }

            .slide-content h2 {
                font-size: 28px;
            }

            .slide-content p {
                font-size: 16px;
            }

            .prev, .next {
                width: 48px;
                height: 48px;
                font-size: 18px;
            }

            .prev {
                left: 16px;
            }

            .next {
                right: 16px;
            }

            .services-container {
                grid-template-columns: 1fr;
                gap: 24px;
            }

            .services-title {
                font-size: 32px;
            }

            .patient-selection, .slideshow-section, .services-section {
                padding: 60px 20px;
            }

            .hero-section {
                padding: 60px 20px 40px;
            }
        }

        /* Mobile Menu Toggle */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #64748b;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .mobile-toggle:hover {
            background-color: #f1f5f9;
            color: #2563eb;
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }

        /* Enhanced Accessibility */
        .selection-button:focus,
        .prev:focus,
        .next:focus,
        .indicator:focus {
            outline: 2px solid #2563eb;
            outline-offset: 2px;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">Sinbadh Hospitals</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index_staff.php">Staff Portal</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
                <button class="mobile-toggle">☰</button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>Welcome to Sinbadh Hospitals</h1>
            <p>Your health is our priority</p>
        </div>
    </section>

    <!-- Patient Selection Section -->
    <section class="patient-selection">
        <div class="selection-container">
            <h2 class="selection-title">How Can We Assist You Today?</h2>
            <p class="selection-subtitle">Please select the option that best describes your situation so we can provide you with the most appropriate care and service.</p>
            
            <div class="selection-buttons">
                <a href="visited.php" class="selection-button">
                    <div class="button-icon">🏥</div>
                    <div class="button-title">I have visited the hospital before</div>
                    <div class="button-description">Access your medical records, view test results, schedule follow-up appointments, and continue your ongoing care.</div>
                </a>
                
                <a href="not_visited.php" class="selection-button">
                    <div class="button-icon">👋</div>
                    <div class="button-title">I have not visited the hospital before</div>
                    <div class="button-description">Get started with our registration process, learn about our services, and schedule your first appointment.</div>
                </a>
            </div>
        </div>
    </section>

    <!-- SLIDESHOW SECTION -->
    <section class="slideshow-section">
        <div class="slideshow-container">
            <div class="slide active">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>Compassionate Patient Care</h2>
                        <p>We provide personalized care that addresses your unique medical needs and concerns with compassion and understanding.</p>
                    </div>
                </div>
            </div>

            <div class="slide">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>Modern Healthcare Technology</h2>
                        <p>Experience the latest in medical technology and diagnostic equipment for accurate, efficient healthcare delivery.</p>
                    </div>
                </div>
            </div>

            <div class="slide">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>Expert Medical Professionals</h2>
                        <p>Our team of experienced healthcare professionals is dedicated to providing you with the highest quality medical care.</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button class="prev" onclick="changeSlide(-1)">❮</button>
            <button class="next" onclick="changeSlide(1)">❯</button>
        </div>

        <!-- Slide Indicators -->
        <div class="slide-indicators">
            <span class="indicator active" onclick="currentSlide(1)"></span>
            <span class="indicator" onclick="currentSlide(2)"></span>
            <span class="indicator" onclick="currentSlide(3)"></span>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services-section">
        <h2 class="services-title">Our Patient Services</h2>
        <div class="services-container">
            <div class="service-card">
                <div class="service-icon">📋</div>
                <h3>Online Registration</h3>
                <p>Complete your registration process online before your visit to save time and ensure a smooth experience.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">📅</div>
                <h3>Appointment Scheduling</h3>
                <p>Book appointments with our specialists at your convenience through our easy-to-use online system.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🔬</div>
                <h3>Test Results</h3>
                <p>Access your laboratory results and diagnostic reports securely through our patient portal.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">💊</div>
                <h3>Prescription Management</h3>
                <p>View and manage your prescriptions, request refills, and track your medication history.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">🏥</div>
                <h3>Emergency Services</h3>
                <p>24/7 emergency care with our dedicated team of emergency medicine specialists.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">💬</div>
                <h3>Patient Support</h3>
                <p>Get assistance with any questions or concerns from our dedicated patient support team.</p>
            </div>
        </div>
    </section>

    <script>
        let currentSlideIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.indicator');
        const totalSlides = slides.length;

        // Auto-advance slideshow
        function autoSlide() {
            currentSlideIndex = (currentSlideIndex + 1) % totalSlides;
            showSlide(currentSlideIndex);
        }

        // Set interval for auto-advance (6 seconds)
        let slideInterval = setInterval(autoSlide, 6000);

        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Show current slide
            slides[index].classList.add('active');
            indicators[index].classList.add('active');
        }

        function changeSlide(direction) {
            // Clear auto-advance temporarily
            clearInterval(slideInterval);
            
            currentSlideIndex += direction;
            
            if (currentSlideIndex >= totalSlides) {
                currentSlideIndex = 0;
            } else if (currentSlideIndex < 0) {
                currentSlideIndex = totalSlides - 1;
            }
            
            showSlide(currentSlideIndex);
            
            // Restart auto-advance
            slideInterval = setInterval(autoSlide, 6000);
        }

        function currentSlide(index) {
            // Clear auto-advance temporarily
            clearInterval(slideInterval);
            
            currentSlideIndex = index - 1;
            showSlide(currentSlideIndex);
            
            // Restart auto-advance
            slideInterval = setInterval(autoSlide, 6000);
        }

        // Pause slideshow on hover
        const slideshowContainer = document.querySelector('.slideshow-container');
        slideshowContainer.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });

        slideshowContainer.addEventListener('mouseleave', () => {
            slideInterval = setInterval(autoSlide, 6000);
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                changeSlide(-1);
            } else if (e.key === 'ArrowRight') {
                changeSlide(1);
            }
        });

        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;

        slideshowContainer.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });

        slideshowContainer.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });

        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;
            
            if (Math.abs(swipeDistance) > swipeThreshold) {
                if (swipeDistance > 0) {
                    changeSlide(-1); // Swipe right, go to previous slide
                } else {
                    changeSlide(1); // Swipe left, go to next slide
                }
            }
        }

        // Add mouse tracking for selection buttons
        const selectionButtons = document.querySelectorAll('.selection-button');
        selectionButtons.forEach(button => {
            button.addEventListener('mousemove', (e) => {
                const rect = button.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 100;
                const y = ((e.clientY - rect.top) / rect.height) * 100;
                button.style.setProperty('--mouse-x', x + '%');
                button.style.setProperty('--mouse-y', y + '%');
            });

            button.addEventListener('click', function(e) {
                // Create ripple effect
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(37, 99, 235, 0.15);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.8s cubic-bezier(0.4, 0, 0.2, 1);
                    pointer-events: none;
                    z-index: 10;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 800);
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2.5);
                    opacity: 0;
                }
            }
            
            .selection-button {
                animation: fadeInUp 0.8s ease both;
            }
            
            .selection-button:nth-child(1) {
                animation-delay: 0.1s;
            }
            
            .selection-button:nth-child(2) {
                animation-delay: 0.2s;
            }
            
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .service-card {
                animation: fadeInUp 0.6s ease both;
            }
            
            .service-card:nth-child(1) { animation-delay: 0.1s; }
            .service-card:nth-child(2) { animation-delay: 0.2s; }
            .service-card:nth-child(3) { animation-delay: 0.3s; }
            .service-card:nth-child(4) { animation-delay: 0.4s; }
            .service-card:nth-child(5) { animation-delay: 0.5s; }
            .service-card:nth-child(6) { animation-delay: 0.6s; }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>