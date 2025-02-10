<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $adminEmail = 'admin@ikarrental.hu';
    $adminPassword = 'admin';

    if ($email === $adminEmail && $password === $adminPassword) {
        $_SESSION['user'] = [
            'id' => 0,
            'full_name' => 'Administrator',
            'email' => $adminEmail,
            'role' => 'admin'
        ];
        header("Location: index.php");
        exit();
    }

    $users = json_decode(file_get_contents('data/users.json'), true);
    $foundUser = null;

    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            $foundUser = $user;
            break;
        }
    }

    if ($foundUser) {
        $_SESSION['user'] = $foundUser;
        header("Location: index.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="form-container">
        <h1>Login</h1>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email address" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p class="form-link">Don't have an account? <a href="register.php">Register</a></p>
    </div>
</body>
</html>