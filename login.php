<?php
session_start();
require_once 'db.php';

// Redirect to dashboard if already logged in
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$username_email = $password = '';
$errors = [];

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_email = trim($_POST['username_email']);
    $password = $_POST['password'];

    // Validate input
    if (empty($username_email) || empty($password)) {
        $errors[] = 'All fields are required.';
    } else {
        // Check if user exists by username or email
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username_email, $username_email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // Check if user is verified
            if ($user['is_verified'] == 0) {
                // Resend verification email if not verified
                require 'vendor/autoload.php';
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'kidusshimelis3@gmail.com';
                $mail->Password = 'your_16_character_app_password'; // Use your App Password
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->setFrom('noreply@example.com', 'Auth System');
                $mail->addAddress($user['email']);
                $mail->Subject = 'Verify your email';
                // Email body with verification link
                $mail->Body = "Click the following link to verify your email: http://localhost/auth_system/verify_email.php?token=" . $user['verification_token'];
                if ($mail->send()) {
                    $errors[] = 'Please verify your email before logging in. A new verification email has been sent.';
                } else {
                    $errors[] = 'Failed to send verification email. Please try again.';
                }
            } else {
                // Login successful
                $_SESSION['user'] = $user['username'];
                // Handle Remember Me functionality
                if (isset($_POST['remember'])) {
                    $token = bin2hex(random_bytes(32));
                    setcookie('remember_token', $token, time() + 30 * 24 * 60 * 60, '/');
                    $stmt = $pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?');
                    $stmt->execute([$token, $user['id']]);
                }
                header('Location: dashboard.php');
                exit;
            }
        } else {
            $errors[] = 'Invalid username/email or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color:white;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background-color: #1c1c64;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .input-group-text {
            background-color: #fff;
            border: none;
            color: #08083e;
            width: 45px;
            justify-content: center;
        }
        .form-control {
            border-left: none;
        }
        .input-group .form-control:focus {
            box-shadow: none;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .alert {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2 class="text-center mb-4 "><strong>Welcome to Kidus's Login</strong></h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label>Username or Email</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                </div>
                <input type="text" name="username_email" class="form-control" placeholder="Enter username or email" value="<?= htmlspecialchars($username_email) ?>" required>
            </div>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
        </div>

        <div class="form-group form-check">
            <input type="checkbox" name="remember" class="form-check-input" id="remember">
            <label class="form-check-label" for="remember">Remember me</label>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>

    <p class="text-center mt-3">Don't have an account? <a href="register.php" class="text-info">Register here</a></p>
</div>

</body>
</html>
