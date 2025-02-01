<?php
class HotelReservation
{
    public $conn; // Change from private to protected

    public function __construct()
    {
        // Initialize the database connection
        require_once 'dbconfig.php';
        $dbConfig = new DBConfig();
        $this->conn = $dbConfig->dbConnect();
    }
    

    public function createReservation($email, $checkIn, $checkOut, $time, $room_id)
    {
        // Validate the form data (you can add more validation if needed)
        if (empty($email) || empty($checkIn) || empty($checkOut) || empty($time) || empty($room_id)) {
            return "Please fill in all the required fields.";
        }
    
        // Check if the check-in date is greater than the check-out date
        if ($checkIn > $checkOut) {
            return "Check-in cannot be greater than the check-out.";
        }
    
        // Check if the check-in date is in the past or today
        $currentDate = strtotime('today'); // Get the timestamp for the beginning of today
        $checkInTimestamp = strtotime($checkIn);
        if ($checkInTimestamp < $currentDate) {
            return "You can't reserve the past dates.";
        }
        
        // Check if the check-in date is the current date
        if ($checkIn === date('Y-m-d', $currentDate)) {
            return "You can't reserve the current date.";
        }

        // Check if the selected time slot is valid
        if ($time !== "day(8 AM to 5 PM)" && $time !== "night(8 PM to 6 AM)") {
            return "Invalid time slot selected.";
        }
    
        // Check if the selected dates are valid based on the time (day or night)
        if ($time === "day(8 AM to 5 PM)") {
            if ($checkIn !== $checkOut) {
                return "For day reservations, check-in and check-out dates should be the same.";
            }
        } elseif ($time === "night(8 PM to 6 AM)") {
            $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($checkIn)));
            if ($checkOut !== $nextDay) {
                return "For night reservations, check-out date should be one day greater than the check-in date.";
            }
        }
    
        // Check if any of the days within the check-in and check-out range are fully booked for the selected time slot
        $endDate = strtotime($checkOut);
        $fullyBookedDays = array(); // Store the fully booked days in this array
        $date = date('Y-m-d', $checkInTimestamp);
        while ($checkInTimestamp <= $endDate) {
            // Query to check the availability for the given time, date, and room_id
            $availabilityQuery = "SELECT COUNT(*) AS reserved_count FROM reservations WHERE check_in = '$date' AND check_out >= '$date' AND time = '$time' AND room_id = '$room_id'";
            $availabilityResult = $this->conn->query($availabilityQuery);
            if ($availabilityResult && $availabilityResult->num_rows > 0) {
                $row = $availabilityResult->fetch_assoc();
                $reservedCount = $row['reserved_count'];
                if ($reservedCount > 0) {
                    $fullyBookedDays[] = $date;
                }
            }

            $checkInTimestamp = strtotime('+1 day', $checkInTimestamp);
            $date = date('Y-m-d', $checkInTimestamp);
        }

        // Now, check if there are any gaps in the fully booked days
        $startDate = strtotime($checkIn);
        $endDate = strtotime($checkOut);
        $allDaysFullyBooked = true;
        $date = date('Y-m-d', $startDate);
        while ($startDate <= $endDate) {
            if (!in_array($date, $fullyBookedDays)) {
                $allDaysFullyBooked = false;
                break;
            }

            $startDate = strtotime('+1 day', $startDate);
            $date = date('Y-m-d', $startDate);
        }

        if ($allDaysFullyBooked) {
            return "Sorry, the selected time on all days within the selected range is already reserved. Please choose different dates or time.";
        } else {
            // Get the user_id from the users table based on the provided email
            $user_id = $this->getUserIdByEmail($email);
            
            // Prepare the SQL statement
            $stmt = $this->conn->prepare("INSERT INTO reservations (user_id, check_in, check_out, time, room_id, status, payment_receipt) VALUES (?, ?, ?, ?, ?, 'confirm', 'Manual Payment')");
            $stmt->bind_param("sssss", $user_id, $checkIn, $checkOut, $time, $room_id);
            
            // Execute the statement
            if ($stmt->execute()) {
                header("location: Arecord.php");
                exit;
            } else {
                return "Error occurred while processing the booking.";
            }
            $stmt->close();
        }
    }
    
    
    // Add a method to get user_id based on email
    public function getUserIdByEmail($email)
    {
        $email = mysqli_real_escape_string($this->conn, $email);
        $query = "SELECT user_id FROM user_form WHERE email = '$email'";
        $result = $this->conn->query($query);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['user_id'];
        } else {
            return null;
        }
    }
    
    
    
    public function searchReservations($searchQuery)
    {
        // Escape user input to prevent SQL injection
        $searchQuery = mysqli_real_escape_string($this->conn, $searchQuery);
    
        // Construct the search query with JOINs
        $sql = "SELECT r.ID, u.email, r.check_in, r.check_out, ro.room_type, r.time, r.payment_receipt, r.recorded_at, r.status
                FROM reservations r
                INNER JOIN user_form u ON r.user_id = u.user_id
                INNER JOIN rooms ro ON r.room_id = ro.room_id
                WHERE r.ID LIKE '%$searchQuery%'
                OR u.email LIKE '%$searchQuery%'
                OR r.check_in LIKE '%$searchQuery%'
                OR r.check_out LIKE '%$searchQuery%'
                OR ro.room_type LIKE '%$searchQuery%'
                OR r.time LIKE '%$searchQuery%'
                OR r.payment_receipt LIKE '%$searchQuery%'
                OR r.recorded_at LIKE '%$searchQuery%'
                OR r.status LIKE '%$searchQuery%'";
    
        // Execute the search query
        $result = mysqli_query($this->conn, $sql);
    
        if ($result) {
            $reservations = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $reservations[] = $row;
            }
            return $reservations;
        } else {
            return null;
        }
    }
    

    public function updateReservation($reservationId, $checkIn, $checkOut, $time, $room_id)
    {
        // Perform any additional validation if needed
        if (empty($checkIn) || empty($checkOut) || empty($time) || empty($room_id)) {
            return "Please fill in all the required fields.";
        }
    
        // Check if the provided dates are in the past
        $today = date("Y-m-d");
        if ($checkIn < $today || $checkOut < $today) {
            return "Cannot update reservation with past dates.";
        }
    
        // Check if the selected dates are valid based on the time (day or night)
        if ($time === "day(8 AM to 5 PM)") {
            if ($checkIn !== $checkOut) {
                return "For day reservations, check-in and check-out dates should be the same.";
            }
        } elseif ($time === "night(8 PM to 6 AM)") {
            $nextDay = date("Y-m-d", strtotime("+1 day", strtotime($checkIn)));
            if ($checkOut !== $nextDay) {
                return "For night reservations, check-out date should be one day greater than the check-in date.";
            }
        }
    
        if ($checkIn > $checkOut) {
            return "Check-in cannot be greater than the check-out.";
        }
    
        // Check if the selected dates are fully booked
        $sqlCheckAvailability = "SELECT * FROM reservations WHERE ID != '$reservationId' AND room_id = '$room_id' AND (
            (check_in >= '$checkIn' AND check_in < '$checkOut') OR
            (check_out > '$checkIn' AND check_out <= '$checkOut') OR
            (check_in <= '$checkIn' AND check_out >= '$checkOut'))";
    
        $resultCheckAvailability = $this->conn->query($sqlCheckAvailability);
    
        if ($resultCheckAvailability->num_rows > 0) {
            return "One or more selected dates are fully booked. Please choose different dates.";
        }
    
       // Update the reservation record
        $sql = "UPDATE reservations SET check_in = '$checkIn', check_out = '$checkOut', time = '$time', room_id = '$room_id' WHERE ID = '$reservationId'";

        $result = $this->conn->query($sql);

        if ($result === TRUE) {
            // Get user_id for notification
            $user_id = $this->getUserIdByReservationId($reservationId);
            
            // Insert a notification for the update
            $notificationMessage = "Your reservation has been updated.";
            $this->insertNotification($user_id, $notificationMessage);

            return true;
        } else {
            return "Error updating record. Please try again.";
        }
    }
    

    
    // Add a method to get room_type based on room_id
    public function getRoomTypeById($room_id)
    {
        $query = "SELECT room_type FROM rooms WHERE room_id = '$room_id'";
        $result = $this->conn->query($query);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['room_type'];
        } else {
            return null;
        }
    }
    
    public function deleteReservation($reservationId)
    {
        // Get user_id for notification
        $user_id = $this->getUserIdByReservationId($reservationId);
    
        $stmt = $this->conn->prepare("DELETE FROM reservations WHERE ID = ?");
        $stmt->bind_param("s", $reservationId);
        
        if ($stmt->execute()) {
            $stmt->close();
            
            // Insert a notification for the deletion
            $notificationMessage = "Your reservation has been deleted.";
            $this->insertNotification($user_id, $notificationMessage);
    
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    
    private function insertNotification($email, $message)
    {
        $timestamp = date("Y-m-d H:i:s");
    
        // Prepare the SQL statement to insert into the table
        $stmt = $this->conn->prepare("INSERT INTO notification (user_id, message, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $message, $timestamp);
    
        // Execute the statement
        $stmt->execute();
        $stmt->close();
    }

    public function updateStatus($reservationId)
    {
        // Get user_id and current status
        $user_id = $this->getUserIdByReservationId($reservationId);
        $currentStatus = $this->getStatusByReservationId($reservationId);
    
        // Prepare the SQL statement to update the status
        $stmt = $this->conn->prepare("UPDATE reservations SET status = 'confirm' WHERE ID = ?");
        $stmt->bind_param("s", $reservationId);
    
        if ($stmt->execute()) {
            $stmt->close();
    
            // Check if the status was actually updated
            if ($this->getStatusByReservationId($reservationId) !== $currentStatus) {
                // Insert a notification for the status update
                $notificationMessage = "Your reservation status has been updated to confirmed.";
                $this->insertNotification($user_id, $notificationMessage);
            }
    
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
    
    // Add a method to get email based on reservation_id
    private function getUserIdByReservationId($reservationId)
    {
        $query = "SELECT user_id FROM reservations WHERE ID = '$reservationId'";
        $result = $this->conn->query($query);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['user_id'];
        } else {
            return null;
        }
    }
    
    
    // Add a method to get status based on reservation_id
    private function getStatusByReservationId($reservationId)
    {
        $query = "SELECT status FROM reservations WHERE ID = '$reservationId'";
        $result = $this->conn->query($query);
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['status'];
        } else {
            return null;
        }
    }

}
?>