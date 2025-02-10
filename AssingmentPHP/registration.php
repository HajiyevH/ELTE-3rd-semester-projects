<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    if ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        $users = json_decode(file_get_contents('data/users.json'), true);
        $userExists = false;

        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $userExists = true;
                break;
            }
        }

        if ($userExists) {
            $error = "Email already registered.";
        } else {
            $newUser = [
                'id' => count($users) + 1,
                'full_name' => $fullName,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];
            $users[] = $newUser;
            file_put_contents('data/users.json', json_encode($users, JSON_PRETTY_PRINT));
            $_SESSION['user'] = $newUser;
            header("Location: index.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="registiration.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="form-container">
        <h1>Register</h1>
        <?php if (!empty($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="full_name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email address" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Register</button>
        </form>
        <p class="form-link">Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>