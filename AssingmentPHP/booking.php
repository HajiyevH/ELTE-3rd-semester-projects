<?php
session_start();
ini_set('display_errors', 0);
error_reporting(E_ALL);

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = $_POST['car_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];

    if (strtotime($endDate) <= strtotime($startDate)) {
        header("Location: booking-status.php?status=failure&message=Invalid date range.");
        exit();
    }

    $bookings = json_decode(file_get_contents('data/bookings.json'), true);

    foreach ($bookings as $booking) {
        if ($booking['car_id'] == $carId && (
            ($startDate >= $booking['start_date'] && $startDate <= $booking['end_date']) ||
            ($endDate >= $booking['start_date'] && $endDate <= $booking['end_date'])
        )) {
            header("Location: booking-status.php?status=failure&message=The selected interval is already booked.");
            exit();
        }
    }

    $days = (strtotime($endDate) - strtotime($startDate)) / (60 * 60 * 24);
    $car = json_decode(file_get_contents('data/cars.json'), true)[$carId - 1];
    $totalPrice = $days * $car['daily_price_huf'];

    $bookings[] = [
        "id" => count($bookings) + 1,
        "user_id" => $_SESSION['user']['id'],
        "car_id" => $carId,
        "start_date" => $startDate,
        "end_date" => $endDate,
        "total_price" => $totalPrice
    ];
    file_put_contents('data/bookings.json', json_encode($bookings, JSON_PRETTY_PRINT));

    header("Location: booking-status.php?status=success&car_id=$carId&start_date=$startDate&end_date=$endDate&total_price=$totalPrice");
    exit();
}
?>