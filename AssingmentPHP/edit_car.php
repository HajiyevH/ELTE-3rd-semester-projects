<?php
session_start();
require_once 'storage.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$carStorage = new Storage(new JsonIO('./data/cars.json'));
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'] ?? null;
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = filter_var($_POST['year'], FILTER_VALIDATE_INT);
    $fuelType = trim($_POST['fuel_type'] ?? '');
    $passengers = filter_var($_POST['passengers'], FILTER_VALIDATE_INT);
    $dailyPrice = filter_var($_POST['daily_price_huf'], FILTER_VALIDATE_INT);
    $imageUrl = trim($_POST['image_url'] ?? '');

    if ($carId && $brand && $model && $year && $fuelType && $passengers && $dailyPrice && $imageUrl) {
        $car = $carStorage->findById($carId);
        if ($car) {
            $car['brand'] = $brand;
            $car['model'] = $model;
            $car['year'] = $year;
            $car['fuel_type'] = $fuelType;
            $car['passengers'] = $passengers;
            $car['daily_price_huf'] = $dailyPrice;
            $car['image'] = $imageUrl;

            $carStorage->update($carId, $car);

            header("Location: index.php");
            exit();
        } else {
            $error = "Car not found.";
        }
    } else {
        $error = "Please fill out all fields correctly.";
    }
}

$carId = $_GET['car_id'] ?? null;
$car = $carId ? $carStorage->findById($carId) : null;

if (!$car) {
    die('Car not found.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Car</title>
    <link rel="stylesheet" href="edit.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="edit-car-container">
        <h1>Edit Car</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST" class="edit-car-form">
            <input type="hidden" name="car_id" value="<?= htmlspecialchars($car['id']) ?>">
            <div>
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" value="<?= htmlspecialchars($car['brand']) ?>" required>
            </div>
            <div>
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" value="<?= htmlspecialchars($car['model']) ?>" required>
            </div>
            <div>
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" value="<?= htmlspecialchars($car['year']) ?>" required>
            </div>
            <div>
                <label for="fuel_type">Fuel Type:</label>
                <input type="text" id="fuel_type" name="fuel_type" value="<?= htmlspecialchars($car['fuel_type']) ?>" required>
            </div>
            <div>
                <label for="passengers">Passengers:</label>
                <input type="number" id="passengers" name="passengers" value="<?= htmlspecialchars($car['passengers']) ?>" required>
            </div>
            <div>
                <label for="daily_price_huf">Daily Price (HUF):</label>
                <input type="number" id="daily_price_huf" name="daily_price_huf" value="<?= htmlspecialchars($car['daily_price_huf']) ?>" required>
            </div>
            <div>
                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($car['image']) ?>" required>
            </div>
            <button type="submit" class="edit-btn">Save Changes</button>
        </form>
    </main>
</body>
</html>