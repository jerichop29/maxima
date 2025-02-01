<?php
require_once 'class.reservation.php';

$ID = $_GET['data'];

// Create an instance of HotelReservation
$reservation = new HotelReservation();

// Call the deleteReservation method
if ($reservation->deleteReservation($ID)) {
    header("Location: Arecord.php");
    exit();
} else {
    echo "<script>alert('Error deleting reservation.')</script>";
    echo "<script>window.location.href = 'Arecord.php'</script>";
    exit();
}
?>