<?php
require 'Storage.php';

$carStorage = new Storage(new JsonIO(__DIR__ . '/data/cars.json'));
$userStorage = new Storage(new JsonIO(__DIR__ . '/data/users.json'));
$bookingStorage = new Storage(new JsonIO(__DIR__ . '/data/bookings.json'));