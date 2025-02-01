<?php
require_once 'class.room.php';

$room_type = $_GET['data'];

// Create an instance of the RoomManager class
$room = new Room();
if ($room->deleteRoom($room_type)) {
    header("Location: Aroom.php");
    exit();
} 
?>