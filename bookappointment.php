<?php
session_start();

$error = "";
$success = "";
$isLoggedIn = isset($_SESSION['user_id']);
if (!$isLoggedIn) {
    echo "You must be logged in to book an appointment.";
    exit;
}

// DB config
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hms";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Session check
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to book an appointment.");
}

$user_id = $_SESSION['user_id'];

// Fetch hospital list
$hospital_list = $conn->query("SELECT hospital_id, hospital_name FROM hospitals");

// Fetch doctor list with all required fields
$doctor_list = $conn->query("SELECT doctor_id, name, specialization, fees, contact FROM doctor");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hospital_id = $_POST['hospital_id'] ?? '';
    $doctor_id = $_POST['doctor_id'] ?? '';
    $booking_date = $_POST['date'] ?? '';
    $booking_time = $_POST['time'] ?? '';

    if (empty($hospital_id) || empty($doctor_id) || empty($booking_date) || empty($booking_time)) {
        $error = "All fields are required.";
    } else {
        // Fetch specialization from the doctor table
        $specialization_result = $conn->query("SELECT specialization FROM doctor WHERE doctor_id = $doctor_id");
        if ($specialization_result && $row = $specialization_result->fetch_assoc()) {
            $specialization = $row['specialization'];

            // Insert appointment
            $stmt = $conn->prepare("INSERT INTO appointments (hospital_id, specialization, booking_date, booking_time, user_id, doctor_id) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                $error = "Prepare failed: " . $conn->error;
            } else {
                $stmt->bind_param("isssii", $hospital_id, $specialization, $booking_date, $booking_time, $user_id, $doctor_id);
                if ($stmt->execute()) {
                    $success = "Appointment Booked Successfully";
                } else {
                    $error = "Error: " . $stmt->error;
                }
                $stmt->close();
            }
        } else {
            $error = "Doctor not found.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Smart Hospital System</title>
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
            --success-color: #4caf50;
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
        .appointment-hero {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            padding: 60px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .appointment-hero::before {
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

        .appointment-hero h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
            position: relative;
            z-index: 2;
        }

        .appointment-hero p {
            font-size: 1.2rem;
            opacity: 0.95;
            font-weight: 300;
            position: relative;
            z-index: 2;
        }

        /* =================== BOOKING FORM SECTION =================== */
        .appointment-section {
            padding: 80px 0;
        }

        .booking-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .booking-card {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(26, 122, 122, 0.15);
            padding: 50px;
            border-top: 5px solid var(--accent-color);
        }

        .booking-card h2 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 40px;
            text-align: center;
            font-size: 2rem;
        }

        .booking-card .icon-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .booking-card .icon-header i {
            font-size: 3.5rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.95rem;
        }

        .form-group select,
        .form-group input[type="date"],
        .form-group input[type="time"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }

        .form-group select:focus,
        .form-group input[type="date"]:focus,
        .form-group input[type="time"]:focus {
            outline: none;
            border-color: var(--accent-color);
            background-color: var(--white);
            box-shadow: 0 0 0 3px rgba(0, 188, 212, 0.1);
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%231a7a7a' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 20px;
            padding-right: 40px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            color: var(--white);
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(26, 122, 122, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        /* =================== MESSAGES =================== */
        .alert-message {
            margin-bottom: 25px;
            padding: 16px 20px;
            border-radius: 10px;
            border-left: 5px solid;
            font-weight: 500;
            animation: slideIn 0.3s ease;
        }

        .alert-error {
            background-color: #ffebee;
            color: #c62828;
            border-left-color: #c62828;
        }

        .alert-success {
            background-color: #e8f5e9;
            color: #2e7d32;
            border-left-color: #2e7d32;
        }

        .alert-message i {
            margin-right: 10px;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* =================== INFO CARDS =================== */
        .info-section {
            padding: 60px 0;
            background: var(--light-bg);
        }

        .info-section h3 {
            font-family: 'Poppins', sans-serif;
            text-align: center;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 40px;
            font-size: 2rem;
        }

        .info-card {
            background: var(--white);
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(26, 122, 122, 0.15);
        }

        .info-card i {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 15px;
        }

        .info-card h5 {
            font-family: 'Poppins', sans-serif;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 10px;
        }

        .info-card p {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* =================== FOOTER =================== */
        footer {
            background-color: var(--primary-dark);
            color: var(--white);
            padding: 30px 0 10px;
            text-align: center;
            margin-top: auto;
        }

        footer p {
            margin: 5px 0;
            opacity: 0.9;
        }

        /* =================== RESPONSIVENESS =================== */
        @media (max-width: 768px) {
            .appointment-hero h1 {
                font-size: 2.2rem;
            }

            .booking-card {
                padding: 35px 25px;
            }

            .booking-card h2 {
                font-size: 1.5rem;
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
                    <li class="nav-item"><a class="nav-link" href="book.php"><i class="fas fa-bed"></i> Book Bed</a></li>
                    <li class="nav-item"><a class="nav-link active" href="bookappointment.php"><i class="fas fa-calendar"></i> Appointment</a></li>
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
        <section class="appointment-hero">
            <div class="container">
                <h1><i class="fas fa-calendar-check"></i> Book Your Appointment</h1>
                <p>Schedule a consultation with our expert doctors at your convenience</p>
            </div>
        </section>

        <!-- =================== BOOKING FORM SECTION =================== -->
        <section class="appointment-section">
            <div class="container">
                <div class="booking-container">
                    <div class="booking-card">
                        <div class="icon-header">
                            <i class="fas fa-stethoscope"></i>
                            <h2>Schedule Your Consultation</h2>
                        </div>

                        <?php if (!empty($error)): ?>
                            <div class="alert-message alert-error">
                                <i class="fas fa-exclamation-circle"></i>
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($success)): ?>
                            <div class="alert-message alert-success">
                                <i class="fas fa-check-circle"></i>
                                <?= htmlspecialchars($success) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="form-group">
                                <label for="hospital_id"><i class="fas fa-hospital"></i> Select Hospital:</label>
                                <select name="hospital_id" id="hospital_id" required>
                                    <option value="">-- Choose a Hospital --</option>
                                    <?php if ($hospital_list && $hospital_list->num_rows > 0): ?>
                                        <?php while ($row = $hospital_list->fetch_assoc()): ?>
                                            <option value="<?= $row['hospital_id'] ?>"><?= htmlspecialchars($row['hospital_name']) ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="doctor_id"><i class="fas fa-user-md"></i> Select Doctor:</label>
                                <select name="doctor_id" id="doctor_id" required>
                                    <option value="">-- Choose a Doctor --</option>
                                    <?php if ($doctor_list && $doctor_list->num_rows > 0): ?>
                                        <?php while ($row = $doctor_list->fetch_assoc()): ?>
                                            <option value="<?= $row['doctor_id'] ?>">
                                                <?= htmlspecialchars("Dr. {$row['name']} | {$row['specialization']} | ₹{$row['fees']}") ?>
                                            </option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="date"><i class="fas fa-calendar-alt"></i> Appointment Date:</label>
                                <input type="date" name="date" id="date" required>
                            </div>

                            <div class="form-group">
                                <label for="time"><i class="fas fa-clock"></i> Appointment Time:</label>
                                <input type="time" name="time" id="time" required>
                            </div>

                            <button type="submit" class="submit-btn">
                                <i class="fas fa-check"></i> Confirm Appointment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- =================== INFO SECTION =================== -->
        <section class="info-section">
            <div class="container">
                <h3>Why Book With Us?</h3>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-3">
                        <div class="info-card">
                            <i class="fas fa-user-md"></i>
                            <h5>Expert Doctors</h5>
                            <p>Experienced and certified medical professionals ready to help you.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info-card">
                            <i class="fas fa-clock"></i>
                            <h5>Flexible Timing</h5>
                            <p>Choose appointment times that fit your schedule perfectly.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info-card">
                            <i class="fas fa-check-circle"></i>
                            <h5>Instant Confirmation</h5>
                            <p>Get immediate booking confirmation and appointment reminders.</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="info-card">
                            <i class="fas fa-headset"></i>
                            <h5>24/7 Support</h5>
                            <p>Our customer support team is always available to assist you.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- =================== FOOTER =================== -->
    <footer>
        <div class="container">
            <p><strong>Smart Hospital Queue & Bed Allocation System</strong></p>
            <p>&copy; <?= date('Y') ?> Medical Team. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>

</html>
