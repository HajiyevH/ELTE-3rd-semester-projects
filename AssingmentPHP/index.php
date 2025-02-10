<?php
require_once 'storage.php';
session_start();

$carStorage = new Storage(new JsonIO('./data/cars.json'));
$bookingStorage = new Storage(new JsonIO('./data/bookings.json'));

$seats = $_GET['seats'] ?? null;
$from = $_GET['from'] ?? null;
$until = $_GET['until'] ?? null;
$fuelType = $_GET['fuel_type'] ?? null;
$minPrice = $_GET['min_price'] ?? null;
$maxPrice = $_GET['max_price'] ?? null;

$cars = $carStorage->findAll();

if ($seats) {
    $cars = array_filter($cars, function($car) use ($seats) {
        return $car['passengers'] >= $seats;
    });
}

if ($fuelType) {
    $cars = array_filter($cars, function($car) use ($fuelType) {
        return $car['fuel_type'] === $fuelType;
    });
}

if ($minPrice) {
    $cars = array_filter($cars, function($car) use ($minPrice) {
        return $car['daily_price_huf'] >= $minPrice;
    });
}

if ($maxPrice) {
    $cars = array_filter($cars, function($car) use ($maxPrice) {
        return $car['daily_price_huf'] <= $maxPrice;
    });
}

if ($from && $until) {
    $cars = array_filter($cars, function($car) use ($from, $until, $bookingStorage) {
        $bookings = $bookingStorage->findAll(['car_id' => $car['id']]);
        foreach ($bookings as $booking) {
            if (($from >= $booking['start_date'] && $from <= $booking['end_date']) ||
                ($until >= $booking['start_date'] && $until <= $booking['end_date']) ||
                ($from <= $booking['start_date'] && $until >= $booking['end_date'])) {
                return false;
            }
        }
        return true;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iKarRental</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="hero">
        <h2>Rent cars easily!</h2>
    </section>

    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
        <div class="add-car-container">
            <a href="add_car.php" class="add-car-btn">Add New Car</a>
        </div>
    <?php endif; ?>

    <section class="filter">
        <form method="GET" novalidate>
            <div class="filter-row">
                <input type="number" name="seats" placeholder="Seats" value="<?= htmlspecialchars($seats) ?>">
                <input type="date" id="from" name="from" value="<?= htmlspecialchars($from) ?>">
                <input type="date" id="until" name="until" value="<?= htmlspecialchars($until) ?>">
                <select name="fuel_type">
                    <option value="">Fuel Type</option>
                    <option value="Petrol" <?= $fuelType === 'Petrol' ? 'selected' : '' ?>>Petrol</option>
                    <option value="Diesel" <?= $fuelType === 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                    <option value="Electric" <?= $fuelType === 'Electric' ? 'selected' : '' ?>>Electric</option>
                </select>
                <input type="number" name="min_price" placeholder="Min Price" value="<?= htmlspecialchars($minPrice) ?>">
                <input type="number" name="max_price" placeholder="Max Price" value="<?= htmlspecialchars($maxPrice) ?>">
                <button type="submit" class="filter-btn">Filter</button>
            </div>
        </form>
    </section>

    <main class="car-grid">
        <?php foreach ($cars as $car): ?>
            <div class="car-card">
                <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>" class="car-image">
                <div class="car-info">
                    <h3><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></h3>
                    <p>HUF <?= number_format($car['daily_price_huf'], 0, ',', ' ') ?>/day</p>
                    <a href="car-details.php?id=<?= htmlspecialchars($car['id']) ?>" class="book-btn">Book</a>
                    <?php if (isset($_SESSION['user']) && isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <form action="edit_car.php" method="GET" style="display:inline;">
                            <input type="hidden" name="car_id" value="<?= htmlspecialchars($car['id']) ?>">
                            <button type="submit" class="edit-btn">Edit</button>
                        </form>
                        <form action="delete_car.php" method="POST" style="display:inline;">
                            <input type="hidden" name="car_id" value="<?= htmlspecialchars($car['id']) ?>">
                            <button type="submit" class="delete-btn">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
    <!-- I used inline JS because it was not on the stuff to avoid and this is the only way 
    i could find how add this functionality to filtering whoch i thought is essential -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fromInput = document.getElementById('from');
            const untilInput = document.getElementById('until');
            const today = new Date().toISOString().split('T')[0];

            fromInput.min = today;
            untilInput.min = today;

            fromInput.addEventListener('change', function() {
                untilInput.min = this.value;
            });

            untilInput.addEventListener('change', function() {
                fromInput.max = this.value;
            });
        });
    </script>
</body>
</html>