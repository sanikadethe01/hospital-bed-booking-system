<?php
// Initialize session
session_start();

// Database configuration
$servername = "localhost";  // Your database server
$username = "root";         // MySQL username
$password = "";             // MySQL password
$dbname = "hms";         // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$name = $age = $gender = $email = $password = "";
$nameErr = $ageErr = $genderErr = $emailErr = $passwordErr = "";
$registerSuccess = $registerError = "";

// Handle registration request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? 0;
    $gender = $_POST['gender'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input fields
    if (empty($name)) {
        $nameErr = "Name is required!";
    }
    if (empty($age)) {
        $ageErr = "Age is required!";
    }
    if (empty($gender)) {
        $genderErr = "Gender is required!";
    }
    if (empty($email)) {
        $emailErr = "Email is required!";
    }
    if (empty($password)) {
        $passwordErr = "Password is required!";
    }

    // If no errors, proceed to insert data into database
    if (empty($nameErr) && empty($ageErr) && empty($genderErr) && empty($emailErr) && empty($passwordErr)) {
        // Check if user already exists
        $stmt = $conn->prepare("SELECT id FROM register WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $registerError = "User already exists!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user into the database
            $stmt = $conn->prepare("INSERT INTO register (name, age, gender, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sisss", $name, $age, $gender, $email, $hashedPassword);

            if ($stmt->execute()) {
                $registerSuccess = "Registration successful! You can now log in.";
            } else {
                $registerError = "Error during registration: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Smart Hospital System</title>

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

        /* =================== REGISTER SECTION =================== */
        .register-section {
            padding: 80px 0;
            min-height: calc(100vh - 200px);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(26, 122, 122, 0.1);
            padding: 50px;
            max-width: 600px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .register-header p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .form-floating {
            margin-bottom: 20px;
        }

        .form-floating > label {
            color: #666;
            font-weight: 500;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.25);
        }

        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.25);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--white);
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(26, 122, 122, 0.3);
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
        }

        .btn-login {
            background: var(--white);
            border: 2px solid var(--accent-color);
            border-radius: 12px;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-color);
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .btn-login:hover {
            background: var(--accent-color);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 188, 212, 0.3);
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }

        .divider span {
            background: var(--white);
            padding: 0 20px;
            color: #666;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .error-message {
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 5px;
            font-weight: 500;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 25px;
        }

        .alert-success {
            background: linear-gradient(135deg, rgba(76, 175, 80, 0.1), rgba(76, 175, 80, 0.05));
            color: #2e7d32;
            font-weight: 500;
        }

        .alert-danger {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.1), rgba(255, 107, 107, 0.05));
            color: var(--danger-color);
            font-weight: 500;
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
            margin-bottom: 5px;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        footer a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: 600;
        }

        footer a:hover {
            text-decoration: underline;
        }

        /* =================== RESPONSIVE =================== */
        @media (max-width: 768px) {
            .register-container {
                margin: 20px;
                padding: 30px;
            }

            .register-header h1 {
                font-size: 2rem;
            }

            .navbar-brand {
                font-size: 1.1rem;
            }
        }

        /* Custom form row for better layout */
        .form-row {
            display: flex;
            gap: 20px;
        }

        @media (max-width: 576px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>

<body>
<!-- =================== NAVBAR =================== -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid ps-4 pe-4">
      <a class="navbar-brand" href="home.php">
        <img
          src="https://media.istockphoto.com/id/1312665318/vector/medical-logo-design-vector.jpg?s=612x612&w=0&k=20&c=dp5fFItTDGnZy8j1gB0GVjqVyJPG_Xznp_JTRZFXCXs="
          alt="Medical Team Logo">
        <span>Medical Team</span>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="home.php"><i class="fas fa-home"></i> Home</a></li>
          <?php if (isset($_SESSION['user_id'])): ?>
            <li class="nav-item"><a class="nav-link" href="book.php"><i class="fas fa-bed"></i> Book Bed</a></li>
            <li class="nav-item"><a class="nav-link" href="bookappointment.php"><i class="fas fa-calendar"></i>
                Appointment</a></li>
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="aboutus.php"><i class="fas fa-info-circle"></i> About</a></li>
          <li class="nav-item"><a class="nav-link" href="healthtips.php"><i class="fas fa-heart"></i> Health Tips</a>
          </li>
          
        </ul>
      </div>
    </div>
  </nav>

<!-- Register Section -->
<main>
    <section class="register-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="register-container">
                        <div class="register-header">
                            <h1>Join Our Healthcare Community</h1>
                            <p>Create your account to access our medical services</p>
                        </div>

                        <?php if (!empty($registerSuccess)): ?>
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo htmlspecialchars($registerSuccess); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($registerError)): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($registerError); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" value="<?php echo htmlspecialchars($name); ?>" required>
                                <label for="name">
                                    <i class="fas fa-user me-2"></i>Full Name
                                </label>
                                <?php if (!empty($nameErr)): ?>
                                    <div class="error-message"><?php echo htmlspecialchars($nameErr); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-row">
                                <div class="form-floating flex-fill">
                                    <input type="number" class="form-control" id="age" name="age" placeholder="Enter your age" value="<?php echo htmlspecialchars($age); ?>" min="1" max="120" required>
                                    <label for="age">
                                        <i class="fas fa-birthday-cake me-2"></i>Age
                                    </label>
                                    <?php if (!empty($ageErr)): ?>
                                        <div class="error-message"><?php echo htmlspecialchars($ageErr); ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="form-floating flex-fill">
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="male" <?php if ($gender == "male") echo "selected"; ?>>Male</option>
                                        <option value="female" <?php if ($gender == "female") echo "selected"; ?>>Female</option>
                                        <option value="other" <?php if ($gender == "other") echo "selected"; ?>>Other</option>
                                    </select>
                                    <label for="gender">
                                        <i class="fas fa-venus-mars me-2"></i>Gender
                                    </label>
                                    <?php if (!empty($genderErr)): ?>
                                        <div class="error-message"><?php echo htmlspecialchars($genderErr); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" value="<?php echo htmlspecialchars($email); ?>" required>
                                <label for="email">
                                    <i class="fas fa-envelope me-2"></i>Email Address
                                </label>
                                <?php if (!empty($emailErr)): ?>
                                    <div class="error-message"><?php echo htmlspecialchars($emailErr); ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Create a strong password" value="<?php echo htmlspecialchars($password); ?>" required minlength="6">
                                <label for="password">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <?php if (!empty($passwordErr)): ?>
                                    <div class="error-message"><?php echo htmlspecialchars($passwordErr); ?></div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" class="btn btn-register">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                        </form>

                        <div class="divider">
                            <span>Already have an account?</span>
                        </div>

                        <button class="btn btn-login" onclick="window.location.href='login.php'">
                            <i class="fas fa-sign-in-alt me-2"></i>Sign In Instead
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Footer -->
<footer>
    <p>&copy; 2024 Smart Hospital Queue & Bed Allocation System. All rights reserved.</p>
    <p>For inquiries, <a href="mailto:info@medicalteam.com" style="color: var(--accent-color);">contact us via email</a>.</p>
    <p>Visit our <a href="#" style="color: var(--accent-color);">Privacy Policy</a> and <a href="#" style="color: var(--accent-color);">Terms & Conditions</a>.</p>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>
