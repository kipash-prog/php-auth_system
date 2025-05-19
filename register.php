<!-- Keep your PHP logic at the top -->
<?php
session_start();
require_once 'db.php';

// Initialize variables
$username = $email = $password = $confirm_password = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($username)) $errors[] = 'Username is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
    if (empty($password) || strlen($password) < 6) $errors[] = 'Password must be at least 6 characters.';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match.';

    // Check for unique username/email
    if (empty($errors)) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ? OR email = ?');
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) $errors[] = 'Username or email already exists.';
    }

    // Register user if no errors
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $verification_token = bin2hex(random_bytes(32)); // Generate a verification token
        $stmt = $pdo->prepare('INSERT INTO users (username, email, password, verification_token) VALUES (?, ?, ?, ?)');
        if ($stmt->execute([$username, $email, $hashed_password, $verification_token])) {
            // Send verification email using PHPMailer
            require 'vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'kidusshimelis3@gmail.com';
            $mail->Password = 'lmjs irtx nfqv dvlb'; // App Password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('noreply@example.com', 'Auth System');
            $mail->addAddress($email);
            $mail->Subject = 'Verify your email';
            // Email body with verification link
            $mail->Body = "Click the following link to verify your email: http://localhost/auth_system/verify_email.php?token=$verification_token";
            if ($mail->send()) {
                // Redirect to login after sending email
                header('Location: login.php');
                exit;
            } else {
                $errors[] = 'Failed to send verification email.';
            }
        } else {
            $errors[] = 'Registration failed.';
        }
    }
}
?>

<!-- HTML START -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS + FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: white;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .card {
            border-radius: 1rem;
            background-color: #1a1a4b;
        }
        .form-control {
            border-radius: .5rem;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .input-group-text {
            background-color: #fff;
            border-right: 0;
        }
        .form-control {
            border-left: 0;
        }
        .alert ul {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg p-4">
                    <h2 class="text-center mb-4">Register</h2>

                    <?php if ($errors): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user text-dark"></i></span>
                                </div>
                                <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope text-dark"></i></span>
                                </div>
                                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock text-dark"></i></span>
                                </div>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-lock text-dark"></i></span>
                                </div>
                                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                        <p class="text-center mt-3">Already have an account? <a href="login.php" class="text-info">Login here</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
