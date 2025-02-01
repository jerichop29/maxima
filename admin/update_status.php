<?php
require_once 'class.reservation.php';

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['data'])) {
    $reservationId = $_GET['data'];

    // Create an instance of HotelReservation
    $reservation = new HotelReservation();

    // Call the updateStatus method to update the status to "confirm"
    if ($reservation->updateStatus($reservationId)) {
        // Redirect back to Arecord.php after updating the status
        header("Location: Arecord.php");
        exit();
    } else {
        // Failed to update status, display an error message or redirect back to Arecord.php with an error flag
        header("Location: Arecord.php?error=1");
        exit();
    }
} else {
    // Invalid request, redirect back to Arecord.php with an error flag
    header("Location: Arecord.php?error=1");
    exit();
}
?>
