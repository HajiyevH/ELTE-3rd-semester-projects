<?php
session_start();
require_once 'storage.php';

$carStorage = new Storage(new JsonIO('./data/cars.json'));
$bookingStorage = new Storage(new JsonIO('./data/bookings.json'));

$carId = $_GET['id'] ?? null;
$car = $carId ? $carStorage->findById($carId) : null;

if (!$car) {
    die('Car not found. Please go back and try again.');
}

$bookings = $bookingStorage->findAll(['car_id' => $carId]);

$bookedRanges = [];
foreach ($bookings as $booking) {
    $bookedRanges[] = [
        'start_date' => $booking['start_date'],
        'end_date' => $booking['end_date'],
    ];
}

$today = date('Y-m-d'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Details - <?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles_car_details.css">
    <link rel="stylesheet" href="navbar.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <main class="car-details">
        <div class="car-image-container">
            <img src="<?= htmlspecialchars($car['image']) ?>" alt="<?= htmlspecialchars($car['brand'] . " " . $car['model']) ?>">
        </div>
        <div class="car-info">
            <h1><?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></h1>
            <p><strong>Fuel:</strong> <?= htmlspecialchars($car['fuel_type']) ?></p>
            <p><strong>Shifter:</strong> <?= htmlspecialchars($car['shifter'] ?? 'N/A') ?></p>
            <p><strong>Year of Manufacture:</strong> <?= htmlspecialchars($car['year']) ?></p>
            <p><strong>Number of Seats:</strong> <?= htmlspecialchars($car['passengers']) ?></p>
            <h2>HUF <?= number_format($car['daily_price_huf'], 0, ',', ' ') ?>/day</h2>
            <div class="action-buttons">
                <?php if (isset($_SESSION['user'])): ?>
                    <form id="booking-form" novalidate>
                        <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                        <label for="start_date">Start Date:</label>
                        <input type="date" id="start_date" name="start_date" required>
                        <label for="end_date">End Date:</label>
                        <input type="date" id="end_date" name="end_date" required>
                        <button type="submit">Book It</button>
                    </form>
                <?php else: ?>
                    <button onclick="window.location.href='login.php'">Login to Book</button>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div id="modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modal-message"></p>
            <div id="booking-details"></div>
            <button id="modal-button"></button>
        </div>
    </div>

    <script type="application/json" id="bookings-data">
        <?= json_encode($bookedRanges) ?>
    </script>
    <script src="calendar.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('booking-form');
            const modal = document.getElementById('modal');
            const modalMessage = document.getElementById('modal-message');
            const bookingDetails = document.getElementById('booking-details');
            const modalButton = document.getElementById('modal-button');
            const closeModal = document.querySelector('.close');

            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const formData = new FormData(form);

                fetch('ajax_booking.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    modalMessage.textContent = data.message;
                    if (data.status === 'success') {
                        bookingDetails.innerHTML = `
                            <p><strong>Car:</strong> <?= htmlspecialchars($car['brand'] . " " . $car['model']) ?></p>
                            <p><strong>Start Date:</strong> ${formData.get('start_date')}</p>
                            <p><strong>End Date:</strong> ${formData.get('end_date')}</p>
                            <p><strong>Total Price:</strong> HUF ${data.total_price}</p>
                        `;
                        modalButton.textContent = 'Go to Profile';
                        modalButton.onclick = function() {
                            window.location.href = 'profile.php';
                        };
                    } else {
                        bookingDetails.innerHTML = '';
                        modalButton.textContent = 'Close';
                        modalButton.onclick = function() {
                            modal.style.display = 'none';
                        };
                    }
                    modal.style.display = 'block';
                })
                .catch(error => {
                    modalMessage.textContent = 'An error occurred. Please try again.';
                    bookingDetails.innerHTML = '';
                    modalButton.textContent = 'Close';
                    modalButton.onclick = function() {
                        modal.style.display = 'none';
                    };
                    modal.style.display = 'block';
                });
            });

            closeModal.addEventListener('click', function() {
                modal.style.display = 'none';
            });

            window.addEventListener('click', function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>