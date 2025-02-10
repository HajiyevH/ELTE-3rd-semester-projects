<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$carStorage = new Storage(new JsonIO('./data/cars.json'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = filter_var($_POST['year'], FILTER_VALIDATE_INT);
    $fuelType = trim($_POST['fuel_type']);
    $passengers = filter_var($_POST['passengers'], FILTER_VALIDATE_INT);
    $dailyPrice = filter_var($_POST['daily_price_huf'], FILTER_VALIDATE_INT);
    $imageUrl = trim($_POST['image_url']);

    if ($brand && $model && $year && $year < 2025 && $fuelType && in_array($fuelType, ['Diesel', 'Electric', 'Petrol']) && $passengers && $dailyPrice && $imageUrl) {
        $newCar = [
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'fuel_type' => $fuelType,
            'passengers' => $passengers,
            'daily_price_huf' => $dailyPrice,
            'image' => $imageUrl
        ];
        $carStorage->add($newCar);

        header("Location: index.php");
        exit();
    } else {
        $error = "Please fill out all fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Car</title>
    <link rel="stylesheet" href="add_car.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="add-car-container">
        <h1>Add New Car</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="POST">
            <div>
                <label for="brand">Brand:</label>
                <input type="text" id="brand" name="brand" required>
            </div>
            <div>
                <label for="model">Model:</label>
                <input type="text" id="model" name="model" required>
            </div>
            <div>
                <label for="year">Year:</label>
                <input type="number" id="year" name="year" max="2024" required>
            </div>
            <div>
                <label for="fuel_type">Fuel Type:</label>
                <select id="fuel_type" name="fuel_type" required>
                    <option value="">Select Fuel Type</option>
                    <option value="Diesel">Diesel</option>
                    <option value="Electric">Electric</option>
                    <option value="Petrol">Petrol</option>
                </select>
            </div>
            <div>
                <label for="passengers">Passengers:</label>
                <input type="number" id="passengers" name="passengers" required>
            </div>
            <div>
                <label for="daily_price_huf">Daily Price (HUF):</label>
                <input type="number" id="daily_price_huf" name="daily_price_huf" required>
            </div>
            <div>
                <label for="image_url">Image URL:</label>
                <input type="text" id="image_url" name="image_url" required>
            </div>
            <button type="submit" class="add-btn">Add Car</button>
        </form>
    </main>
</body>
</html>