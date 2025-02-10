<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$bookings = json_decode(file_get_contents('data/bookings.json'), true);
$cars = json_decode(file_get_contents('data/cars.json'), true);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $carId = $_POST['car_id'] ?? null;

    if ($action == 'delete' && $carId) {
        $cars = array_filter($cars, fn($car) => $car['id'] != $carId);
        $bookings = array_filter($bookings, fn($booking) => $booking['car_id'] != $carId);
        file_put_contents('data/cars.json', json_encode($cars, JSON_PRETTY_PRINT));
        file_put_contents('data/bookings.json', json_encode($bookings, JSON_PRETTY_PRINT));
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link rel="stylesheet" href="styles_aprofile.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="profile-page">
        <h2>All Bookings</h2>
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Car</th>
                    <th>Period</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking): ?>
                    <?php 
                    $car = array_filter($cars, fn($c) => $c['id'] == $booking['car_id']);
                    $car = reset($car);
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['user_id']) ?></td>
                        <td><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></td>
                        <td><?= htmlspecialchars($booking['start_date'] . " - " . $booking['end_date']) ?></td>
                        <td>HUF <?= number_format($booking['total_price'], 0, ',', ' ') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h2>Manage Cars</h2>
        <div class="cars-management">
            <?php foreach ($cars as $car): ?>
                <div class="car-card">
                    <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>" class="car-img">
                    <h3><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></h3>
                    <p>Daily Price: HUF <?= number_format($car['daily_price'], 0, ',', ' ') ?></p>
                    <form method="POST">
                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                        <button type="submit" name="action" value="delete" class="delete-btn">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer>
        &copy; <?= date('Y') ?> iKarRental. All rights reserved.
    </footer>
</body>
</html>