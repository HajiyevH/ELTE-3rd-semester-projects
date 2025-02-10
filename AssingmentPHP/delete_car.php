<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$carStorage = new Storage(new JsonIO('./data/cars.json'));
$bookingStorage = new Storage(new JsonIO('./data/bookings.json'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carId = trim($_POST['car_id']);

    $car = $carStorage->findOne(['id' => $carId]);
    if ($car) {
        $carStorage->delete($carId);
        $bookingStorage->deleteMany(function ($booking) use ($carId) {
            return $booking['car_id'] == $carId;
        });
    }

    header("Location: index.php");
    exit();
}
?>