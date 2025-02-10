<?php
session_start();
$status = $_GET['status'] ?? 'failure';
$carId = $_GET['car_id'] ?? null;
$startDate = $_GET['start_date'] ?? null;
$endDate = $_GET['end_date'] ?? null;
$totalPrice = $_GET['total_price'] ?? null;
$message = $_GET['message'] ?? 'There was an error processing your booking. Please ensure all details are correct and try again.';

$car = null;
if ($carId) {
    $cars = json_decode(file_get_contents('data/cars.json'), true);
    foreach ($cars as $c) {
        if ($c['id'] == $carId) {
            $car = $c;
            break;
        }
    }
}

if (!$car) {
    $status = 'failure';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Status</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles_booking_status.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main>
    <?php if ($status === 'success' && $car): ?>
        <div class="success-container">
            <h2>Successful booking!</h2>
            <p>The <?= htmlspecialchars($car['brand'] . " " . $car['model']) ?> has been successfully booked for the interval <?= htmlspecialchars($startDate) ?> to <?= htmlspecialchars($endDate) ?>.</p>
            <p>You can track the status of your reservation on your profile page.</p>
            <img src="imgs/success.png" alt="Success">
            <button onclick="window.location.href='profile.php'">My profile</button>
        </div>
    <?php else: ?>
        <div class="failure-container">
            <h2>Booking failed!</h2>
            <p><?= htmlspecialchars($message) ?></p>
            <?php if ($car): ?>
                <p>The <?= htmlspecialchars($car['brand'] . " " . $car['model']) ?> is not available in the specified interval from <?= htmlspecialchars($startDate) ?> to <?= htmlspecialchars($endDate) ?>.</p>
            <?php endif; ?>
            <p>Try entering a different interval or searching for another vehicle.</p>
            <img src="imgs/fail.png" alt="Failure">
            <button onclick="window.location.href='index.php'">Back to the vehicle side</button>
        </div>
    <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> iKarRental. All rights reserved.
    </footer>
</body>
</html>