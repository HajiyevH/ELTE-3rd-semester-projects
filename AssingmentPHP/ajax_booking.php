<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'failure', 'message' => 'You must be logged in to book a car.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'storage.php';

    $carId = $_POST['car_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if (strtotime($endDate) <= strtotime($startDate)) {
        echo json_encode(['status' => 'failure', 'message' => 'Invalid date range.']);
        exit();
    }

    $bookingStorage = new Storage(new JsonIO('./data/bookings.json'));
    $bookings = $bookingStorage->findAll();

    foreach ($bookings as $booking) {
        if ($booking['car_id'] == $carId && (
            ($startDate >= $booking['start_date'] && $startDate <= $booking['end_date']) ||
            ($endDate >= $booking['start_date'] && $endDate <= $booking['end_date'])
        )) {
            echo json_encode(['status' => 'failure', 'message' => 'The selected interval is already booked.']);
            exit();
        }
    }

    $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
    $car = json_decode(file_get_contents('data/cars.json'), true)[$carId - 1];
    $totalPrice = $days * $car['daily_price_huf'];

    $newBooking = [
        "id" => count($bookings) + 1,
        "user_id" => $_SESSION['user']['id'],
        "car_id" => $carId,
        "start_date" => $startDate,
        "end_date" => $endDate,
        "total_price" => $totalPrice
    ];
    $bookingStorage->add($newBooking);

    echo json_encode(['status' => 'success', 'message' => 'Booking successful!', 'total_price' => $totalPrice]);
    exit();
}

echo json_encode(['status' => 'failure', 'message' => 'Invalid request.']);
exit();
?>