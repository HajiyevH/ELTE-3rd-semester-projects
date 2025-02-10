<?php
require 'storage_setup.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $newBooking = [
    'start_date' => $_POST['start_date'],
    'end_date' => $_POST['end_date'],
    'user_email' => $_POST['user_email'],
    'car_id' => $_POST['car_id']
  ];

  $bookingStorage->add($newBooking);
  echo "Booking successfully created!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book a Car</title>
</head>
<body>
  <h1>Book a Car</h1>
  <form method="POST">
    <input type="text" name="start_date" placeholder="Start Date (YYYY-MM-DD)" required>
    <input type="text" name="end_date" placeholder="End Date (YYYY-MM-DD)" required>
    <input type="email" name="user_email" placeholder="Your Email" required>
    <input type="text" name="car_id" placeholder="Car ID" required>
    <button type="submit">Book Car</button>
  </form>
</body>
</html>