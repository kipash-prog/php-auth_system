<?php
session_start();
require_once 'db.php';

// Get the token from the URL
$token = $_GET['token'] ?? '';
$errors = [];

// Check if token is provided
if (empty($token)) {
    $errors[] = 'Invalid verification link.';
} else {
    // Look up the user by verification token
    $stmt = $pdo->prepare('SELECT id FROM users WHERE verification_token = ?');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Mark user as verified and clear the token
        $stmt = $pdo->prepare('UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?');
        $stmt->execute([$user['id']]);
        $message = 'Email verified successfully. You can now login.';
    } else {
        $errors[] = 'Invalid verification link.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Verify Email</h2>
        <?php if ($errors): ?>
            <ul class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
        <p><a href="login.php">Go to Login</a></p>
    </div>
</body>
</html> 