<!-- Include the required files -->
<?php
include 'dbconfig.php';
include 'class.reservation.php';
session_start();

// Initialize the $email variable
$email = '';

// Check if the user is already logged in and set the email in the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Get the form data
    $user_id = $_POST['user_id']; // Previously email, now user_id
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $time = $_POST['time'];
    $room_id = $_POST['room_id']; // Previously room_type, now room_id

    // Create an instance of HotelReservation
    $hotelReservation = new HotelReservation();

    // Call the createReservation method
    $result = $hotelReservation->createReservation($user_id, $checkIn, $checkOut, $time, $room_id);
}
?>

<!-- Include the header -->
<?php include 'Aheader.php'; ?>
<br>
<div class="back_re">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="title">
               <h2>Manual Reservation</h2>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- include banner -->
<?php include 'banner.php'; ?>
<div class="booking_ocline">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <div class="book_room">
                    <h1>Resort Reservation</h1>
                    <?php
                    require_once 'class.reservation.php';

                    // Check if the form was submitted
                    if (isset($_POST['submit'])) {
                        // Get the form data
                        $email = $_POST['user_id'];
                        $checkIn = $_POST['check_in'];
                        $checkOut = $_POST['check_out'];
                        $time = $_POST['time'];
                        $room_id = $_POST['room_id'];

                        // Create an instance of HotelReservation
                        $hotelReservation = new HotelReservation();

                        // Call the createReservation method
                        $errorMessage = $hotelReservation->createReservation($email, $checkIn, $checkOut, $time, $room_id);
                        if (is_string($errorMessage)) {
                            // Error occurred, display the error message and return to the form
                            echo '<p style="color: red;">' . $errorMessage . '</p>';
                        } else {
                            // Successful reservation, handle as needed
                        }
                    }
                    ?>
                    <form action="" method="POST">
                        <div class="row">
                            <div class="col-md-12">
                            <?php
                                if (isset($email)) {
                                    echo '<input type="hidden" id="user_id" name="user_id" value="' . $email . '">';
                                } else {
                                    echo '<input type="hidden" id="user_id" name="user_id" value="' . (isset($_GET['email']) ? $_GET['email'] : '') . '">';
                                }
                                ?>

                            </div>
                            <div class="col-md-12">
                                <span>Check In</span>
                                <input type="date" name="check_in" value="">
                            </div>
                            <div class="col-md-12">
                                <span>Check Out</span>
                                <input type="date" name="check_out" value="">
                            </div>
                            <div class="col-md-12">
                                <label for="time">Select time:</label>
                                <select name="time">
                                    <option value="day(8 AM to 5 PM)">Day (8 AM to 5 PM)</option>
                                    <option value="night(8 PM to 6 AM)">Night (8 PM to 6 AM)</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label for="room">Rooms:</label>
                                <select name="room_id" id="room_id_select">
                                    <option value="" selected disabled>Select a room</option>
                                    <?php
                                    require_once 'dbconfig.php';

                                    $dbConfig = new DBConfig();
                                    $conn = $dbConfig->dbConnect();

                                    $result = $conn->query("SELECT * FROM rooms");

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $room_id = $row['room_id'];
                                            $room_type = $row['room_type'];
                                            $price = $row['price'];

                                            // Display the room type as the option value
                                            echo "<option value='$room_id' data-price='$price'>$room_type</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="room_price">Price:</label>
                                <input type="text" id="room_price" name="room_price" value="" readonly>
                            </div>

                            <div class="col-md-12">
                                <label for="dp">Down Payment:</label>
                                <input type="text" id="dp" name="dp" value="" readonly>
                            </div>

                            <script>
                                document.getElementById("room_id_select").addEventListener("change", function() {
                                    var selectedOption = this.options[this.selectedIndex];
                                    var price = selectedOption.getAttribute("data-price");
                                    var dp = price / 2;

                                    document.getElementById("room_price").value = '$' + price;
                                    document.getElementById("dp").value = '₱' + dp;
                                });
                            </script>

                            <div class="col-md-12">
                                <input type="submit" name="submit" value="Reserve" class="btn btn-primary" onclick="return confirm('Are you sure you want to reserve?');">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- end banner -->
<!-- Include the footer -->
<?php include 'Afooter.php'; ?>


