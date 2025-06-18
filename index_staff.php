<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sinbadh Hospitals - Step into the finest healthcare</title>
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
            height: 500px;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .slide.active {
            display: block;
        }

        /* PASTE YOUR IMAGE LINKS HERE - Replace the background-image URLs below */
        .slide:nth-child(1) {
            background-image: url('https://images.unsplash.com/photo-1559757148-5c350d0d3c56?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            /* SLIDE 1 IMAGE - Replace with your hospital exterior image */
        }

        .slide:nth-child(2) {
            background-image: url('https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            /* SLIDE 2 IMAGE - Replace with your medical equipment image */
        }

        .slide:nth-child(3) {
            background-image: url('https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            /* SLIDE 3 IMAGE - Replace with your doctors/staff image */
        }

        .slide:nth-child(4) {
            background-image: url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            /* SLIDE 4 IMAGE - Replace with your hospital interior image */
        }

        .slide:nth-child(5) {
            background-image: url('https://images.unsplash.com/photo-1584515933487-779824d29309?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            /* SLIDE 5 IMAGE - Replace with your patient care image */
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
            font-size: 36px;
            margin-bottom: 15px;
            font-weight: 300;
        }

        .slide-content p {
            font-size: 18px;
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

            .slide {
                height: 350px;
            }

            .slide-content h2 {
                font-size: 28px;
            }

            .slide-content p {
                font-size: 16px;
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
            <div class="logo">Sinbadh Hospitals</div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                </ul>
                <button class="mobile-toggle">☰</button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1> Step into the finest healthcare </h1>
            <p>How may we help you?</p>
        </div>
    </section>

    <!-- SLIDESHOW SECTION -->
    <section class="slideshow-section">
        <div class="slideshow-container">
            <!-- SLIDE 1 - Replace image URL above in CSS -->
            <div class="slide active">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>World-Class Medical Facilities</h2>
                        <p>Experience cutting-edge healthcare with our state-of-the-art medical equipment and modern facilities designed for your comfort and care.</p>
                    </div>
                </div>
            </div>

            <!-- SLIDE 2 - Replace image URL above in CSS -->
            <div class="slide">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>Advanced Medical Technology</h2>
                        <p>Our hospital is equipped with the latest medical technology and diagnostic equipment to ensure accurate diagnosis and effective treatment.</p>
                    </div>
                </div>
            </div>

            <!-- SLIDE 3 - Replace image URL above in CSS -->
            <div class="slide">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>Expert Medical Team</h2>
                        <p>Our dedicated team of experienced doctors, nurses, and healthcare professionals are committed to providing you with the best possible care.</p>
                    </div>
                </div>
            </div>

            <!-- SLIDE 4 - Replace image URL above in CSS -->
            <div class="slide">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>Comfortable Patient Environment</h2>
                        <p>Our modern and comfortable hospital environment is designed to promote healing and provide a peaceful atmosphere for our patients.</p>
                    </div>
                </div>
            </div>

            <!-- SLIDE 5 - Replace image URL above in CSS -->
            <div class="slide">
                <div class="slide-overlay">
                    <div class="slide-content">
                        <h2>Compassionate Patient Care</h2>
                        <p>At Sinbadh Hospitals, we believe in providing compassionate, personalized care that addresses not just your medical needs, but your overall well-being.</p>
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
            <span class="indicator" onclick="currentSlide(4)"></span>
            <span class="indicator" onclick="currentSlide(5)"></span>
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

        // Set interval for auto-advance (5 seconds)
        let slideInterval = setInterval(autoSlide, 5000);

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
            slideInterval = setInterval(autoSlide, 5000);
        }

        function currentSlide(index) {
            // Clear auto-advance temporarily
            clearInterval(slideInterval);
            
            currentSlideIndex = index - 1;
            showSlide(currentSlideIndex);
            
            // Restart auto-advance
            slideInterval = setInterval(autoSlide, 5000);
        }

        // Pause slideshow on hover
        const slideshowContainer = document.querySelector('.slideshow-container');
        slideshowContainer.addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });

        slideshowContainer.addEventListener('mouseleave', () => {
            slideInterval = setInterval(autoSlide, 5000);
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
    </script>
</body>
</html>