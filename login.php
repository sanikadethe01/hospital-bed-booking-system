<?php
// Start session to manage logged-in user
session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
  session_unset();
  session_destroy();
  header("Location: home.php");
  exit();
}

$isLoggedIn = isset($_SESSION['user_id']);

// Database connection settings
$servername = "localhost"; // Change if necessary
$username = "root";        // Replace with your MySQL username
$password = "";            // Replace with your MySQL password
$dbname = "hms";        // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$loginError = ""; // Variable to store error messages

// Check if login form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
  // Sanitize input
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Prepare SQL query to fetch user data based on email
  $stmt = $conn->prepare("SELECT id, password FROM register WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  // If user exists with the entered email
  if ($stmt->num_rows > 0) {
    // Fetch the user data
    $stmt->bind_result($id, $hashedPassword);
    $stmt->fetch();

    // Verify the entered password with the hashed password in the database
    if (password_verify($password, $hashedPassword)) {
      // Login successful, set session variables
      $_SESSION['user_id'] = $id;
      $_SESSION['email'] = $email;

      // Redirect to home.php
      header('Location: home.php');
      exit;
    } else {
      // Invalid password
      $loginError = "Invalid email or password!";
    }
  } else {
    // Email not found in database
    $loginError = "Invalid email or password!";
  }

  // Close statement
  $stmt->close();
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Smart Hospital System</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Google Fonts -->
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;500;600&display=swap"
    rel="stylesheet">

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

    html,
    body {
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

    /* =================== LOGIN SECTION =================== */
    .login-section {
      padding: 80px 0;
      min-height: calc(100vh - 200px);
      display: flex;
      align-items: center;
    }

    .login-container {
      background: var(--white);
      border-radius: 20px;
      box-shadow: 0 15px 40px rgba(26, 122, 122, 0.1);
      padding: 50px;
      max-width: 500px;
      width: 100%;
      position: relative;
      overflow: hidden;
    }

    .login-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    }

    .login-header {
      text-align: center;
      margin-bottom: 40px;
    }

    .login-header h1 {
      font-family: 'Poppins', sans-serif;
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 10px;
    }

    .login-header p {
      color: #666;
      font-size: 1.1rem;
      margin-bottom: 0;
    }

    .form-floating {
      margin-bottom: 20px;
    }

    .form-floating>label {
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

    .btn-login {
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

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(26, 122, 122, 0.3);
      background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
    }

    .btn-register {
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

    .btn-register:hover {
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

    .forgot-password {
      text-align: center;
      margin-top: 20px;
    }

    .forgot-password a {
      color: var(--accent-color);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .forgot-password a:hover {
      color: var(--primary-color);
      text-decoration: underline;
    }

    .alert {
      border-radius: 12px;
      border: none;
      margin-bottom: 25px;
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
      .login-container {
        margin: 20px;
        padding: 30px;
      }

      .login-header h1 {
        font-size: 2rem;
      }

      .navbar-brand {
        font-size: 1.1rem;
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
          <?php if ($isLoggedIn): ?>
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

  <!-- Login Section -->
  <main>
    <section class="login-section">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-md-8">
            <div class="login-container">
              <div class="login-header">
                <h1>Welcome Back</h1>
                <p>Sign in to access your account</p>
              </div>

              <?php if (!empty($loginError)): ?>
                <div class="alert alert-danger" role="alert">
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  <?php echo htmlspecialchars($loginError); ?>
                </div>
              <?php endif; ?>

              <form method="POST" action="">
                <div class="form-floating">
                  <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                    required>
                  <label for="email">
                    <i class="fas fa-envelope me-2"></i>Email Address
                  </label>
                </div>

                <div class="form-floating">
                  <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" required>
                  <label for="password">
                    <i class="fas fa-lock me-2"></i>Password
                  </label>
                </div>

                <button type="submit" class="btn btn-login" name="login">
                  <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
              </form>

              <div class="divider">
                <span>Don't have an account?</span>
              </div>

              <button class="btn btn-register" onclick="window.location.href='register.php'">
                <i class="fas fa-user-plus me-2"></i>Create New Account
              </button>

              <div class="forgot-password">
                <a href="#" title="Contact support for password reset">
                  <i class="fas fa-key me-1"></i>Forgot your password?
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer>
    <p>&copy; 2024 Smart Hospital Queue & Bed Allocation System. All rights reserved.</p>
    <p>For inquiries, <a href="mailto:info@medicalteam.com" style="color: var(--accent-color);">contact us via
        email</a>.</p>
    <p>Visit our <a href="#" style="color: var(--accent-color);">Privacy Policy</a> and <a href="#"
        style="color: var(--accent-color);">Terms & Conditions</a>.</p>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>

</html>