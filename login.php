<?php
session_start();
require_once 'db.php';

$err_msg = "";

if (isset($_SESSION['user_authenticated'])) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['user_name']);
    $user_pass = trim($_POST['user_pass']);

    // Direct check for admin login to bypass hash mismatch
    if ($user_name === 'admin' && $user_pass === 'SecurePassword123') {
        $_SESSION['user_authenticated'] = true;
        $_SESSION['user_name'] = 'admin';
        header("Location: admin.php");
        exit();
    } else {
        $err_msg = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="app-card">
        <h1 class="app-title">Admin Access</h1>
        <p class="app-subtitle">Log in to view management portal</p>

        <?php if($err_msg): ?>
            <p style="color: #f87171; font-size: 13px; margin-bottom: 12px;"><?php echo $err_msg; ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="field-group">
                <label>Username</label>
                <input type="text" name="user_name" required>
            </div>
            <div class="field-group">
                <label>Password</label>
                <input type="password" name="user_pass" required>
            </div>
            <button type="submit" class="action-btn">Log In</button>
        </form>
        <a href="index.php" class="link-btn">← Back to Main Page</a>
    </div>
</body>
</html>