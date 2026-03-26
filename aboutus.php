<?php
// Start the session if needed
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
    <title>About Us - Smart Hospital System</title>
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
            background-color: var(--accent-color);
            color: var(--primary-dark) !important;
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-left: 15px;
        }

        .btn-login:hover {
            background-color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 188, 212, 0.3);
        }

        /* =================== HERO SECTION =================== */
        .about-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            padding: 60px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .about-hero::before {
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

        .about-hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
            position: relative;
            z-index: 2;
        }

        .about-hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            font-weight: 300;
            position: relative;
            z-index: 2;
        }

        /* =================== ABOUT CONTENT SECTION =================== */
        .about-section {
            padding: 80px 0;
        }

        .about-content-card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            padding: 50px 40px;
            margin-bottom: 40px;
        }

        .about-content-card h2 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 15px;
        }

        .about-content-card h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 2px;
        }

        .about-content-card p {
            font-size: 1.05rem;
            color: #555;
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .about-content-card p:last-child {
            margin-bottom: 0;
        }

        /* =================== MISSION VISION SECTION =================== */
        .mission-vision-section {
            padding: 80px 0;
            background: var(--light-bg);
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

        .value-card {
            background: var(--white);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .value-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 40px rgba(26, 122, 122, 0.15);
        }

        .value-card i {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }

        .value-card h5 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }

        .value-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* =================== TEAM SECTION =================== */
        .team-section {
            padding: 80px 0;
            background: var(--white);
        }

        .team-member-card {
            background: var(--light-bg);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            text-align: center;
        }

        .team-member-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(26, 122, 122, 0.15);
        }

        .team-member-image {
            width: 100%;
            height: 250px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .team-member-image i {
            font-size: 4rem;
            color: rgba(255, 255, 255, 0.8);
            z-index: 2;
            position: relative;
        }

        .team-member-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,122.7C672,117,768,139,864,144C960,149,1056,139,1152,128C1248,117,1344,107,1392,101.3L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') no-repeat bottom;
            background-size: cover;
            opacity: 0.3;
        }

        .team-member-info {
            padding: 30px 20px;
        }

        .team-member-info h5 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .team-member-info p {
            color: var(--accent-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .team-member-info .description {
            color: #666;
            font-size: 0.85rem;
            margin-top: 10px;
            line-height: 1.5;
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
            .about-hero h1 {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .about-content-card {
                padding: 30px 20px;
            }

            .about-content-card h2 {
                font-size: 1.6rem;
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
                    <li class="nav-item"><a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item"><a class="nav-link" href="book.php"><i class="fas fa-bed"></i> Book Bed</a></li>
                        <li class="nav-item"><a class="nav-link" href="bookappointment.php"><i class="fas fa-calendar"></i> Appointment</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link active" href="aboutus.php"><i class="fas fa-info-circle"></i> About</a></li>
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
        <section class="about-hero">
            <div class="container">
                <h1><i class="fas fa-hospital"></i> About Medical Team</h1>
                <p>Revolutionizing Healthcare Management with Smart Technology</p>
            </div>
        </section>

        <!-- =================== ABOUT CONTENT SECTION =================== -->
        <section class="about-section">
            <div class="container">
                <div class="about-content-card">
                    <h2><i class="fas fa-bullseye"></i> Our Mission</h2>
                    <p>
                        Welcome to the Medical Team platform, your trusted partner in streamlining healthcare services. Our mission is to create a bridge between
                        patients and healthcare providers, ensuring timely care and accessibility. Whether it's booking OPD appointments, checking bed availability,
                        or accessing specialized care, we're here to simplify healthcare for you.
                    </p>
                    <p>
                        Together, we aim to build a smarter and more integrated healthcare ecosystem, connecting hospitals across the city and empowering communities with better healthcare access.
                    </p>
                </div>

                <div class="about-content-card">
                    <h2><i class="fas fa-eye"></i> Our Vision</h2>
                    <p>
                        To become the leading healthcare management platform in the region, enhancing patient experiences and hospital operations through innovative technology
                        and patient-centric services. We envision a future where healthcare is accessible, affordable, and efficient for everyone.
                    </p>
                    <p>
                        Our platform aims to reduce wait times, optimize resource allocation, and create a seamless healthcare experience that benefits both patients and healthcare professionals.
                    </p>
                </div>
            </div>
        </section>

        <!-- =================== VALUES SECTION =================== -->
        <section class="mission-vision-section">
            <div class="container">
                <h2 class="section-title">Our Core Values</h2>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="value-card">
                            <i class="fas fa-handshake"></i>
                            <h5>Patient-First</h5>
                            <p>We prioritize patient welfare and ensure every decision benefits those we serve.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="value-card">
                            <i class="fas fa-star"></i>
                            <h5>Excellence</h5>
                            <p>We strive for the highest standards in healthcare management and service delivery.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="value-card">
                            <i class="fas fa-lock"></i>
                            <h5>Trust & Security</h5>
                            <p>We safeguard patient data and maintain the highest confidentiality standards.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="value-card">
                            <i class="fas fa-lightbulb"></i>
                            <h5>Innovation</h5>
                            <p>We continuously evolve with technology to improve healthcare accessibility.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="value-card">
                            <i class="fas fa-globe"></i>
                            <h5>Accessibility</h5>
                            <p>We make healthcare services available to everyone, regardless of location or background.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="value-card">
                            <i class="fas fa-users"></i>
                            <h5>Collaboration</h5>
                            <p>We work together with hospitals and professionals to deliver better care outcomes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================== TEAM SECTION =================== -->
        <section class="team-section">
            <div class="container">
                <h2 class="section-title">Meet Our Team</h2>
                <div class="row g-4">
                    <?php
                    $teamMembers = [
                        [
                            'name' => 'Pooja Chile',
                            'role' => 'Project Leader',
                            'description' => 'Visionary leader with extensive healthcare management experience'
                        ],
                        [
                            'name' => 'Sanika Dethe',
                            'role' => 'Project Member',
                            'description' => 'Dedicated team member committed to delivering quality solutions'
                        ]
                    ];

                    foreach ($teamMembers as $member) {
                        echo '
                        <div class="col-md-6 col-lg-4">
                            <div class="team-member-card">
                                <div class="team-member-image">
                                    <i class="fas fa-user-md"></i>
                                </div>
                                <div class="team-member-info">
                                    <h5>' . htmlspecialchars($member['name']) . '</h5>
                                    <p>' . htmlspecialchars($member['role']) . '</p>
                                    <p class="description">' . htmlspecialchars($member['description']) . '</p>
                                </div>
                            </div>
                        </div>
                        ';
                    }
                    ?>
                </div>
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
