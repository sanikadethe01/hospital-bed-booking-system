<?php
// Start the session to check if the user is logged in
session_start();

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']); // Assuming 'user_id' is stored in session on successful login

// If the user is logged in and tries to access the logout page, destroy the session
if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();  // Remove all session variables
    session_destroy();  // Destroy the session
    header("Location: home.php"); // Redirect to home page after logout
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Smart Hospital Queue & Bed Allocation System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #1a7a7a;
            --primary-light: #2a9f9f;
            --primary-dark: #0f5555;
            --accent-color: #00bcd4;
            --danger-color: #ff6b6b;
            --light-bg: #f8f9fa;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            background-color: var(--light-bg);
            color: #333;
            overflow-x: hidden;
        }

        main {
            flex: 1;
        }

        /* =================== NAVBAR =================== */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            box-shadow: 0 2px 15px rgba(26, 122, 122, 0.1);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 700;
            color: var(--white) !important;
            font-size: 1.3rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .navbar-brand img {
            height: 45px;
            margin-right: 12px;
            filter: brightness(1.2);
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            margin: 0 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 0;
            background-color: var(--accent-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .btn-login {
            background: var(--accent-color);
            color: var(--white) !important;
            padding: 6px 12px;
            border-radius: 20px;
            transition: all 0.3s ease;
            margin-left: 8px;
            text-decoration: none;
        }

        .btn-login:hover {
            background: var(--primary-color);
            color: var(--white) !important;
            text-decoration: none;
        }

        /* =================== HERO SECTION =================== */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            padding: 80px 0;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.5;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .hero-section h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            opacity: 0.95;
            font-weight: 300;
        }

        .hero-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-primary-custom {
            background-color: var(--accent-color);
            color: var(--primary-dark);
            padding: 12px 35px;
            border-radius: 30px;
            font-weight: 600;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            background-color: var(--white);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-secondary-custom {
            background-color: transparent;
            color: var(--white);
            padding: 12px 35px;
            border: 2px solid var(--white);
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary-custom:hover {
            background-color: var(--white);
            color: var(--primary-color);
        }

        /* =================== FEATURES SECTION =================== */
        .features-section {
            padding: 80px 0;
            background: var(--white);
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 60px;
            color: var(--primary-color);
            position: relative;
        }

        .section-title::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            margin: 20px auto 0;
            border-radius: 2px;
        }

        .feature-card {
            background: var(--light-bg);
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            border: 2px solid transparent;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-color: var(--accent-color);
            background: var(--white);
            box-shadow: 0 15px 40px rgba(26, 122, 122, 0.15);
        }

        .feature-icon {
            font-size: 3.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            color: var(--accent-color);
            transform: scale(1.1);
        }

        .feature-card h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .feature-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* =================== STATS SECTION =================== */
        .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            padding: 80px 0;
            position: relative;
        }

        .stat-box {
            text-align: center;
            padding: 40px;
        }

        .stat-number {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            color: var(--accent-color);
        }

        .stat-label {
            font-size: 1.1rem;
            font-weight: 500;
            opacity: 0.9;
        }

        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--accent-color);
        }

        /* =================== TESTIMONIALS SECTION =================== */
        .testimonials-section {
            padding: 80px 0;
            background: var(--light-bg);
        }

        .testimonial-card {
            background: var(--white);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border-left: 4px solid var(--accent-color);
            height: 100%;
        }

        .testimonial-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(26, 122, 122, 0.15);
        }

        .testimonial-text {
            font-size: 1rem;
            color: #555;
            margin-bottom: 20px;
            font-style: italic;
            line-height: 1.6;
        }

        .testimonial-author {
            font-weight: 600;
            color: var(--primary-color);
            font-family: 'Poppins', sans-serif;
        }

        .stars {
            color: #ffc107;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        /* =================== CTA SECTION =================== */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: var(--white);
            padding: 80px 0;
            text-align: center;
        }

        .cta-section h2 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 30px;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        /* =================== FOOTER =================== */
        footer {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 50px 0 20px;
            text-align: center;
            margin-top: auto;
        }

        .footer-content {
            margin-bottom: 30px;
        }

        .footer-content p {
            margin: 8px 0;
            opacity: 0.9;
        }

        .footer-links a {
            color: var(--accent-color);
            text-decoration: none;
            margin: 0 15px;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: var(--white);
            text-decoration: underline;
        }

        .footer-copy {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 20px;
            margin-top: 20px;
            opacity: 0.8;
            font-size: 0.9rem;
        }

        /* =================== RESPONSIVENESS =================== */
        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.2rem;
            }

            .hero-section p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .stat-number {
                font-size: 2rem;
            }

            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-brand img {
                height: 35px;
            }

            .btn-login {
                margin-left: 0;
                margin-top: 10px;
            }

            .hero-buttons {
                flex-direction: column;
                align-items: center;
            }

            .cta-section h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>

<body>
    <!-- =================== NAVBAR =================== -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid ps-4 pe-4">
            <a class="navbar-brand" href="home.php">
                <img src="https://media.istockphoto.com/id/1312665318/vector/medical-logo-design-vector.jpg?s=612x612&w=0&k=20&c=dp5fFItTDGnZy8j1gB0GVjqVyJPG_Xznp_JTRZFXCXs=" alt="Medical Team Logo">
                <span>Medical Team</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="home.php"><i class="fas fa-home"></i> Home</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item"><a class="nav-link" href="book.php"><i class="fas fa-bed"></i> Book Bed</a></li>
                        <li class="nav-item"><a class="nav-link" href="bookappointment.php"><i class="fas fa-calendar"></i> Appointment</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="aboutus.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link" href="healthtips.php"><i class="fas fa-heart"></i> Health Tips</a></li>
                    <li class="nav-item">
                        <?php if ($isLoggedIn): ?>
                            <a href="?logout=true" class="nav-link btn-login"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="nav-link btn-login"><i class="fas fa-sign-in-alt"></i> Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <!-- =================== HERO SECTION =================== -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1>Welcome to Medical Team</h1>
                    <p>Your trusted healthcare provider. Expert care, modern facilities, and dedicated specialists.</p>

                    <?php if ($isLoggedIn): ?>
                        <p style="font-size: 1.1rem; margin-top: 20px;">
                            <i class="fas fa-check-circle" style="color: var(--accent-color);"></i>
                            Hello, <strong><?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?></strong>! You're logged in.
                        </p>
                    <?php else: ?>
                        <p style="font-size: 1.1rem; margin-top: 20px;">
                            Get started with us today and experience world-class healthcare services.
                        </p>
                        <div class="hero-buttons">
                            <button class="btn-primary-custom" onclick="window.location.href='login.php'">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                            <button class="btn-secondary-custom" onclick="window.location.href='register.php'">
                                <i class="fas fa-user-plus"></i> Register Now
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <!-- =================== FEATURES SECTION =================== -->
        <section class="features-section">
            <div class="container">
                <h2 class="section-title">Our Key Features</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <i class="fas fa-user-md feature-icon"></i>
                            <h5>Expert Specialists</h5>
                            <p>Find and connect with the best medical specialists in your area with proven expertise and credentials.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <i class="fas fa-hospital-user feature-icon"></i>
                            <h5>Easy Bed Booking</h5>
                            <p>Instant bed availability checking and seamless booking system for your hospital admission needs.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <i class="fas fa-calendar-check feature-icon"></i>
                            <h5>Smart Appointments</h5>
                            <p>Schedule appointments with minimal waiting time and receive timely reminders and confirmations.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <i class="fas fa-heartbeat feature-icon"></i>
                            <h5>Health Tips & Info</h5>
                            <p>Access curated health articles, medical tips, and wellness information from certified healthcare experts.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================== STATS SECTION =================== -->
        <section class="stats-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-box">
                            <i class="fas fa-hospital stat-icon"></i>
                            <div class="stat-number">200+</div>
                            <div class="stat-label">Hospital Partners</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-box">
                            <i class="fas fa-user-md stat-icon"></i>
                            <div class="stat-number">1000+</div>
                            <div class="stat-label">Verified Specialists</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-box">
                            <i class="fas fa-users stat-icon"></i>
                            <div class="stat-number">50K+</div>
                            <div class="stat-label">Happy Patients</div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="stat-box">
                            <i class="fas fa-star stat-icon"></i>
                            <div class="stat-number">4.8★</div>
                            <div class="stat-label">Average Rating</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================== TESTIMONIALS SECTION =================== -->
        <section class="testimonials-section">
            <div class="container">
                <h2 class="section-title">What Our Patients Say</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="testimonial-card">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"I was able to find the right specialist and book a bed easily. The service was absolutely excellent and the staff was very helpful!"</p>
                            <p class="testimonial-author"><i class="fas fa-user-circle"></i> Pallavi More</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="testimonial-card">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"The health tips provided by the website helped me considerably in maintaining my health. Highly informative and practical advice!"</p>
                            <p class="testimonial-author"><i class="fas fa-user-circle"></i> Sakshi Sankaye</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="testimonial-card">
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"The appointment booking process was smooth and efficient. I received timely medical attention from certified professionals. Highly recommend!"</p>
                            <p class="testimonial-author"><i class="fas fa-user-circle"></i> Tanishka Kadam</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================== CTA SECTION =================== -->
        <section class="cta-section">
            <div class="container">
                <h2>Ready to Get Started?</h2>
                <p>Join thousands of satisfied patients who trust us with their healthcare needs.</p>
                <?php if (!$isLoggedIn): ?>
                    <button class="btn-primary-custom" onclick="window.location.href='register.php'" style="background-color: white; color: var(--primary-color);">
                        <i class="fas fa-user-plus"></i> Create Account Today
                    </button>
                <?php else: ?>
                    <button class="btn-primary-custom" onclick="window.location.href='book.php'" style="background-color: white; color: var(--primary-color);">
                        <i class="fas fa-bed"></i> Book a Bed
                    </button>
                    <button class="btn-primary-custom" onclick="window.location.href='bookappointment.php'" style="background-color: white; color: var(--primary-color); margin-left: 15px;">
                        <i class="fas fa-calendar"></i> Schedule Appointment
                    </button>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- =================== FOOTER =================== -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <p><strong>Smart Hospital Queue & Bed Allocation System</strong></p>
                <p>Providing world-class healthcare management and patient services.</p>
                <p>Email: <strong>info@medicalteam.com</strong> | Phone: <strong>1-800-MEDICAL</strong></p>
                <div class="footer-links" style="margin-top: 15px;">
                    <a href="#"><i class="fab fa-facebook"></i> Facebook</a>
                    <a href="#"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
                    <a href="#"><i class="fab fa-linkedin"></i> LinkedIn</a>
                </div>
            </div>
            <div class="footer-copy">
                <p>&copy; 2024 Medical Team. All rights reserved. | <a href="#" style="color: var(--accent-color);">Privacy Policy</a> | <a href="#" style="color: var(--accent-color);">Terms of Service</a></p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle (includes Popper) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>