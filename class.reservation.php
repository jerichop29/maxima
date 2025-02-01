<?php
class HotelReservation
{
    public $conn; // Change from private to protected

    public function __construct()
    {
        // Initialize the database connection
        require_once 'admin/dbconfig.php';
        $dbConfig = new DBConfig();
        $this->conn = $dbConfig->dbConnect();
    }
    

    public function createReservation($email, $checkIn, $checkOut, $time, $room_id, $payment_receipt)
    {
        // Validate the form data (you can add more validation if needed)
        if (empty($email) || empty($checkIn) || empty($checkOut) || empty($time) || empty($room_id) || empty($payment_receipt)) {
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
            $stmt = $this->conn->prepare("INSERT INTO reservations (user_id, check_in, check_out, time, room_id, payment_receipt) VALUES (?, ?, ?, ?, ?,?)");
            $stmt->bind_param("ssssss", $user_id, $checkIn, $checkOut, $time, $room_id, $payment_receipt);
            // Execute the statement
            if ($stmt->execute()) {
                $_SESSION['registration_success'] = true;
                header('location: record.php');
                exit();
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
    
    

}
?>