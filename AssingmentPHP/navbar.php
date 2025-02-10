<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="navbar.css">

<div class="navbar-container">
    <header class="navbar">
        <a href="index.php" class="logo">iKarRental</a>
        <div class="auth-buttons">
            <?php if (isset($_SESSION['user'])): ?>
                <span>Welcome, <?= htmlspecialchars($_SESSION['user']['full_name']) ?></span>
                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                <?php endif; ?>
                <a href="profile.php">
                    <img src="imgs/pp.jpg" alt="Profile Picture" class="profile-pic">
                </a>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="login.php" class="login-btn">Login</a>
                <a href="registration.php" class="register-btn">Registration</a>
            <?php endif; ?>
        </div>
    </header>
</div>