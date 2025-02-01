<?php
include 'class.room.php';

$room_type = $_POST["room_type"];
$room_count = $_POST["room_count"];
$price = $_POST["price"];
$room_image = $_POST["room_image"];

// Create an instance of the RoomManager class
$room = new Room();
if ($room->updateRoom($room_type, $room_count, $price, $room_image)) {
    header("location: Aroom.php");
    exit();
}  
else{
    echo "Error: " . $dbConn->dbConnect()->error;
}
?>