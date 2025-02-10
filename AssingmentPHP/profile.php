<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user']['id'];
$bookings = json_decode(file_get_contents('data/bookings.json'), true);
$cars = json_decode(file_get_contents('data/cars.json'), true);

if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
    $userBookings = $bookings;
} else {
    $userBookings = array_filter($bookings, function ($booking) use ($userId) {
        return $booking['user_id'] == $userId;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="styles_profile.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="profile-page">
        <div class="profile-header">
            <img src="imgs/pp.jpg" alt="User Profile Picture" class="profile-pic">
            <h1>Logged in as <?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
        </div>
        <section class="user-reservations">
            <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
            <h2>All Reservations</h2>
            <?php else: ?>
            <h2>My Reservations</h2>
            <?php endif?>


            <div class="reservation-list">
                <?php if (!empty($userBookings) && count($userBookings) > 0): ?>
                    <?php foreach ($userBookings as $booking): ?>
                        <?php 
                        $car = array_filter($cars, function ($c) use ($booking) {
                            return $c['id'] == $booking['car_id'];
                        });
                        $car = reset($car);
                        ?>
                        <div class="reservation-card">
                            <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>" class="reservation-img">
                            <div>
                                <h3><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></h3>
                                <p><?= htmlspecialchars($booking['start_date'] . " - " . $booking['end_date']) ?></p>
                                <p>Price: HUF <?= number_format($booking['total_price'], 0, ',', ' ') ?></p>
                                <?php if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                                    <form action="delete_booking.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="booking_id" value="<?= htmlspecialchars($booking['id']) ?>">
                                        <button type="submit" class="delete-btn">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>You have no reservations yet.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        &copy; <?= date('Y') ?> iKarRental. All rights reserved.
    </footer>
</body>
</html>