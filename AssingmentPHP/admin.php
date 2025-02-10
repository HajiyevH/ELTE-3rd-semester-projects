<?php
require_once 'storage.php';

$carStorage = new Storage(new JsonIO('./data/cars.json'));

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = $_POST['brand'] ?? '';
    $model = $_POST['model'] ?? '';
    $year = $_POST['year'] ?? '';
    $fuelType = $_POST['fuel_type'] ?? '';
    $passengers = $_POST['passengers'] ?? '';
    $price = $_POST['daily_price_huf'] ?? '';
    $imageUrl = $_POST['image_url'] ?? '';

    if (!$brand || !$model || !$year || !$fuelType || !$passengers || !$price || !$imageUrl) {
        $errors[] = 'All fields are required.';
    }

    if (empty($errors)) {
        $carStorage->add([
            'brand' => $brand,
            'model' => $model,
            'year' => (int)$year,
            'fuel_type' => $fuelType,
            'passengers' => (int)$passengers,
            'daily_price_huf' => (int)$price,
            'image' => $imageUrl,
        ]);
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Car</title>
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h1>Add a New Car</h1>
    <?php if ($errors): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="brand" placeholder="Brand" required>
        <input type="text" name="model" placeholder="Model" required>
        <input type="number" name="year" placeholder="Year" required>
        <input type="text" name="fuel_type" placeholder="Fuel Type" required>
        <input type="number" name="passengers" placeholder="Passengers" required>
        <input type="number" name="daily_price_huf" placeholder="Daily Price (HUF)" required>
        <input type="text" name="image_url" placeholder="Image URL" required>
        <button type="submit">Add Car</button>
    </form>
</body>
</html>