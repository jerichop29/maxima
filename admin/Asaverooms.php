<?php
require_once 'class.room.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form submission, process the data

    // Retrieve form data
    $room_type = $_POST["room_type"];
    $room_count = $_POST["room_count"];
    $price = $_POST["price"];
    $room_image = $_FILES["room_image"]["name"];

    // Create an instance of HotelReservation
    $reservation = new Room();

    // Call the addRoom method
    $reservation->addRoom($room_type, $room_count, $price, $room_image);
}
?>