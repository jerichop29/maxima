<?php
require_once 'class.reservation.php';

// Create an instance of HotelReservation
$reservation = new HotelReservation();

// Check if the form is submitted for updating
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ID = $_POST["ID"];
    $check_in = $_POST["check_in"];
    $check_out = $_POST["check_out"];
    $time = $_POST["time"];
    $room_id = $_POST["room_id"]; // Update: Retrieve room_id from the form

    // Call the updateReservation method
    $result = $reservation->updateReservation($ID, $check_in, $check_out, $time, $room_id);

    // Check the result of the update
    if ($result === true) {
        echo "<script>alert('Reservation updated successfully.')</script>";
        echo "<script>window.location.href = 'Arecord.php'</script>";
        exit();
    } else {
        // Show the error message using JavaScript alert
        echo "<script>alert('$result')</script>";
        echo "<script>window.location.href = 'AeditRecord.php?data=$ID'</script>"; // Redirect back to edit page with data
        exit();
    }
}
?>
