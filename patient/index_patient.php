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
            font-family: 'Arial', sans-serif;
            background-color: #1e3c72;
        }

        /* Header Navigation */
        .header {
            background-color: #1e3c72;
            color: white;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            font-size: 24px;
            font-weight: bold;
            color: white;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-menu li {
            position: relative;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 0;
            transition: color 0.3s ease;
        }

        .nav-menu a:hover {
            color: #87ceeb;
        }

        /* Main Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            text-align: center;
            padding: 60px 20px 40px;
        }

        .hero-content h1 {
            font-size: 48px;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .hero-content p {
            font-size: 20px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        /* Patient Selection Section */
        .patient-selection {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 60px 20px;
            text-align: center;
        }

        .selection-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .selection-title {
            font-size: 36px;
            color: white;
            margin-bottom: 20px;
            font-weight: 300;
        }

        .selection-subtitle {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 50px;
            line-height: 1.6;
        }

        .selection-buttons {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .selection-button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 40px 30px;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 300px;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .selection-button:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.6);
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .selection-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .selection-button:hover::before {
            left: 100%;
        }

        .button-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #87ceeb;
        }

        .button-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .button-description {
            font-size: 14px;
            opacity: 0.8;
            text-align: center;
            line-height: 1.4;
        }

        /* SLIDESHOW SECTION */
        .slideshow-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 60px 20px;
        }

        .slideshow-container {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .slide {
            display: none;
            position: relative;
            width: 100%;
            height: 400px;
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
            background: linear-gradient(45deg, rgba(30, 60, 114, 0.7), rgba(42, 82, 152, 0.5));
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .slide-content {
            color: white;
            max-width: 600px;
            padding: 20px;
        }

        .slide-content h2 {
            font-size: 32px;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .slide-content p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.95;
        }

        /* Navigation Arrows */
        .prev, .next {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 15px 20px;
            cursor: pointer;
            font-size: 24px;
            transition: background 0.3s ease;
            border-radius: 5px;
            backdrop-filter: blur(10px);
        }

        .prev {
            left: 20px;
        }

        .next {
            right: 20px;
        }

        .prev:hover, .next:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Slide Indicators */
        .slide-indicators {
            text-align: center;
            padding: 20px 0;
        }

        .indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            margin: 0 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .indicator.active {
            background: white;
        }

        /* Services Section */
        .services-section {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            padding: 60px 20px;
        }

        .services-title {
            text-align: center;
            font-size: 36px;
            color: white;
            margin-bottom: 50px;
            font-weight: 300;
        }

        .services-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .service-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            color: white;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .service-icon {
            font-size: 48px;
            margin-bottom: 20px;
            color: #87ceeb;
        }

        .service-card h3 {
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 500;
        }

        .service-card p {
            line-height: 1.6;
            opacity: 0.9;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
            }

            .hero-content h1 {
                font-size: 36px;
            }

            .selection-title {
                font-size: 28px;
            }

            .selection-buttons {
                flex-direction: column;
                align-items: center;
            }

            .selection-button {
                min-width: 280px;
            }

            .slide {
                height: 300px;
            }

            .slide-content h2 {
                font-size: 24px;
            }

            .slide-content p {
                font-size: 14px;
            }

            .prev, .next {
                padding: 10px 15px;
                font-size: 20px;
            }

            .services-container {
                grid-template-columns: 1fr;
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
        }

        @media (max-width: 768px) {
            .mobile-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <header class="header">
        <div class="nav-container">
            <div class="logo">Sinbadh Hospitals - Patient Portal</div>
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
            <h1>Welcome to Patient Care</h1>
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

        // Add click animation to selection buttons
        const selectionButtons = document.querySelectorAll('.selection-button');
        selectionButtons.forEach(button => {
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
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add CSS for ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>