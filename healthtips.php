<?php
session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    session_unset();
    session_destroy();
    header("Location: home.php");
    exit();
}

$isLoggedIn = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Tips - Smart Hospital System</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
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

        .nav-link.active {
            color: var(--accent-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent-color);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        /* =================== BTN-LOGIN =================== */
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
        .hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 50%, var(--accent-color) 100%);
            color: var(--white);
            padding: 120px 0 80px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(0, 188, 212, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 80%, rgba(42, 159, 159, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }

        .hero p {
            font-size: 1.2rem;
            font-weight: 300;
            opacity: 0.95;
            margin-bottom: 0;
        }

        /* =================== TIPS SECTION =================== */
        .tips-section {
            padding: 80px 0;
        }

        .section-title {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary-color);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 50px;
            font-weight: 300;
        }

        /* =================== TIP CARDS =================== */
        .tip-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(26, 122, 122, 0.08);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            background: var(--white);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tip-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 15px 40px rgba(26, 122, 122, 0.15);
        }

        .tip-card-img-container {
            position: relative;
            overflow: hidden;
            height: 250px;
            background: linear-gradient(135deg, var(--primary-light), var(--accent-color));
        }

        .tip-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .tip-card:hover img {
            transform: scale(1.08);
        }

        .tip-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(26, 122, 122, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .tip-card:hover .tip-card-overlay {
            opacity: 1;
        }

        .tip-card-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 50px;
            height: 50px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 188, 212, 0.3);
            z-index: 3;
        }

        .tip-card-body {
            padding: 28px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .tip-card h5 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 12px;
        }

        .tip-card p {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.6;
            margin-bottom: 0;
            flex-grow: 1;
        }

        .tip-card-footer {
            margin-top: auto;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .tip-card a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .tip-card a:hover {
            color: var(--primary-color);
            gap: 12px;
        }

        /* =================== FOOTER =================== */
        footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            text-align: center;
            padding: 30px 0;
            margin-top: auto;
            box-shadow: 0 -2px 15px rgba(26, 122, 122, 0.1);
        }

        footer p {
            margin-bottom: 0;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* =================== RESPONSIVE =================== */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .hero p {
                font-size: 1rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .tip-card-body {
                padding: 20px;
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
                    <li class="nav-item"><a class="nav-link" href="aboutus.php"><i class="fas fa-info-circle"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link active" href="healthtips.php"><i class="fas fa-heart"></i> Health Tips</a></li>
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

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <h1>Health Tips & Wellness</h1>
        <p>Simple habits for a healthy and happy lifestyle</p>
    </div>
</section>

<!-- Tips Section -->
<main>
    <section class="tips-section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Essential Health Tips</h2>
                <p class="section-subtitle">Follow these simple practices to maintain a healthy lifestyle</p>
            </div>

            <div class="row g-4">
                <?php
                $healthTips = [
                    [
                        "title"=>"Stay Hydrated",
                        "desc"=>"Drink at least 8 glasses of water daily to maintain proper hydration, boost energy, and support organ function.",
                        "img"=>"https://images.unsplash.com/photo-1502741338009-cac2772e18bc?w=500&h=400&fit=crop",
                        "icon"=>"fa-droplet"
                    ],
                    [
                        "title"=>"Balanced Diet",
                        "desc"=>"Incorporate fruits, vegetables, whole grains, and protein into your meals for optimal nutrition.",
                        "img"=>"https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=500&h=400&fit=crop",
                        "icon"=>"fa-apple-alt"
                    ],
                    [
                        "title"=>"Regular Exercise",
                        "desc"=>"Perform at least 30 minutes of physical activity daily to strengthen your body and improve cardiovascular health.",
                        "img"=>"https://images.unsplash.com/photo-1554284126-aa88f22d8b74?w=500&h=400&fit=crop",
                        "icon"=>"fa-dumbbell"
                    ],
                    [
                        "title"=>"Quality Sleep",
                        "desc"=>"Aim for 7-9 hours of quality sleep every night for better mental health and immune function.",
                        "img"=>"https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=500&h=400&fit=crop",
                        "icon"=>"fa-moon"
                    ],
                    [
                        "title"=>"Stress Management",
                        "desc"=>"Practice yoga, meditation, or breathing exercises to reduce stress and improve mental well-being.",
                        "img"=>"https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=500&h=400&fit=crop",
                        "icon"=>"fa-spa"
                    ],
                    [
                        "title"=>"Social Connection",
                        "desc"=>"Connect with friends and family regularly to boost mental health and build strong relationships.",
                        "img"=>"https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=500&h=400&fit=crop",
                        "icon"=>"fa-people-group"
                    ]
                ];

                foreach($healthTips as $tip){
                ?>

                <div class="col-md-6 col-lg-4">
                    <div class="card tip-card">
                        <div class="tip-card-img-container">
                            <img src="<?= htmlspecialchars($tip['img']) ?>" alt="<?= htmlspecialchars($tip['title']) ?>">
                            <div class="tip-card-overlay"></div>
                            <div class="tip-card-icon">
                                <i class="fas <?= $tip['icon'] ?>"></i>
                            </div>
                        </div>
                        <div class="tip-card-body">
                            <h5><?= htmlspecialchars($tip['title']) ?></h5>
                            <p><?= htmlspecialchars($tip['desc']) ?></p>
                            <div class="tip-card-footer">
                                <a href="#" title="Learn more about <?= htmlspecialchars($tip['title']) ?>">
                                    <span>Learn More</span>
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <?php } ?>

            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Smart Hospital Queue & Bed Allocation System. All rights reserved.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>