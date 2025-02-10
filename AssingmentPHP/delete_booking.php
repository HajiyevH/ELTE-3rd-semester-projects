<?php
session_start();
require_once 'storage.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$bookingStorage = new Storage(new JsonIO('./data/bookings.json'));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookingId = trim($_POST['booking_id']);

    $booking = $bookingStorage->findById($bookingId);
    if ($booking) {
        $bookingStorage->delete($bookingId);
    }

    header("Location: profile.php");
    exit();
}
?>