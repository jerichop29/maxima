<!-- Include the required files -->
<?php
include 'admin/dbconfig.php';
include 'class.reservation.php';
session_start();

// Initialize the $email variable
$email = '';

// Check if the user is already logged in and set the email in the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $user_id = $_POST['user_id']; // Previously email, now user_id
    $checkIn = $_POST['check_in'];
    $checkOut = $_POST['check_out'];
    $time = $_POST['time'];
    $room_id = $_POST['room_id']; // Previously room_type, now room_id
    $payment_receipt = $_POST['payment_receipt'];

    // Create an instance of HotelReservation
    $hotelReservation = new HotelReservation();

    // Call the createReservation method
    $result = $hotelReservation->createReservation($user_id, $checkIn, $checkOut, $time, $room_id, $payment_receipt);
}
?>


<!-- Include the header -->
<?php include 'header.php'; ?>
<!-- include banner -->
<br>
<div class="back_re">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="title">
               <h2>Online Reservation</h2>
            </div>
         </div>
      </div>
   </div>
</div>

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
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Get the form data
                        $user_id = $_POST['user_id'];
                        $checkIn = $_POST['check_in'];
                        $checkOut = $_POST['check_out'];
                        $time = $_POST['time'];
                        $room_id = $_POST['room_id'];
                        $payment_receipt = $_POST['payment_receipt'];

                        // Create an instance of HotelReservation
                        $hotelReservation = new HotelReservation();

                        // Call the createReservation method
                        $errorMessage = $hotelReservation->createReservation($user_id, $checkIn, $checkOut, $time, $room_id, $payment_receipt);

                        // Check if there's an error
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
                                    require_once 'admin/dbconfig.php';

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

                                    document.getElementById("room_price").value = '₱' + price;
                                    document.getElementById("dp").value = '₱' + dp;
                                });
                            </script>

                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#paymentModal">
                                    Reserve
                                </button>
                            </div>

                        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
</section>
<!-- end banner -->

<!-- Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex align-items-center justify-content-center flex-column"> <!-- Updated: Added d-flex, align-items-center, justify-content-center, flex-column classes -->
                <!-- Display the QR code image -->
                <h4 style="color: red;">Reminder!</h4> <h6>Pay the exact down payment and give the proper proof of payment or else your reservation won't be approved.</h6>
                <h5>SCAN GCASH QR CODE</h5>

                <img src="images/payment1.jpg" alt="QR Code" class="img-fluid">

                <!-- Add the necessary fields for payment receipt -->
                <form action="" method="POST" id="paymentForm" enctype="multipart/form-data">
                    <div class="form-group">  
                        <label for="payment_receipt"><h6>Proof of payment:</h6></h5>
                        <input type="file" id="payment_receipt" name="payment_receipt" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary" onclick="submitReservation()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

      <!-- about -->
      <div class="about">
         <div class="container-fluid">
            <div class="row">
               <div class="col-md-5">
                  <div class="titlepage">
                  <h2>ABOUT US</h2>
                     <p class="margin_0">Maxima Resort warmly invites you to experience an exceptional and luxurious stay in the city. With meticulously designed guest rooms that provide a cozy and inviting atmosphere, you can relax and feel at home. The hotel offers a wide range of outstanding facilities tailored to meet your every need, including spa treatments, a modern gym, and a relaxing poolside experience. Hoteru Hotel is committed to exceeding your expectations, whether you're traveling for business or leisure. They look forward to hosting you and creating lasting memories during your stay in the splendid city. Welcome to Hoteru Hotel, your home away from home.</p>
                  </div>
               </div>
               <div class="col-md-7">
                  <div class="about_img">
                     <figure><img src="images/about.jpg" alt="#"/></figure>
                  </div>
               </div>
            </div>
         </div>
      </div>
 <!-- end about -->

   <!-- Offers -->
   <div class="blog">
   <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="titlepage">
                           <h2>OFFERS</h2>
                        </div>
                     </div>
                  </div>
            <div class="row">
               <div class="col-md-4">
                  <div class="blog_box">
                     <div class="blog_img">
                        <figure><img src="images/mainblog1.jpg" alt="#"/></figure>
                     </div>
                     <div class="blog_room">
                        <h3>Maxima Dining</h3>
                        <span>Dining Area</span>
                        <p>From the subtly-stylish furniture right down to the delicious international dishes, you can get a taste of “The Rich Life” with us. Dining with us becomes a new experience, thanks to the list of mouth-watering a la carte meals and a bevy of distinct flavors from the outstanding buffet.</p>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="blog_box">
                     <div class="blog_img">
                        <figure><img src="images/mainblog2.jpg" alt="#"/></figure>
                     </div>
                     <div class="blog_room">
                        <h3>Maxima Billiards</h3>
                        <span>Carrom tabletop game</span>
                        <p>Come for the burger, stay for the beer! The Pool Snack Bar's latest dining promotion is here. Relax with us with your choice of burger and get a complimentary bottle of Beer.</p>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="blog_box">
                     <div class="blog_img">
                        <figure><img src="images/mainblog3.jpg" alt="#"/></figure>
                     </div>
                     <div class="blog_room">
                        <h3>Maxima Karaoke</h3>
                        <span>Karaoke area</span>
                        <p>Start your fitness journey with a wide range of cardiovascular and strength-training equipment which include a full range of free weight-training dumbbells. The professional and qualified gym instructors are ready to render sound fitness advice and also design personal exercise programs.</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         </div>
      </div>
      <!-- end offers -->

 <!-- rooms -->


<div class="rooms">
<div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="titlepage">
                           <h2>Rooms Available</h2>
                        </div>
                     </div>
                  </div>
        <div class="row">
            <?php
            require_once 'admin/dbconfig.php';

            $dbConfig = new DBConfig();
            $conn = $dbConfig->dbConnect();

            $result = $conn->query("SELECT * FROM rooms");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>
                        <div class="rooms_box">
                            <div class="rooms_img">
                                <?php if (!empty($row['room_image']) && file_exists('admin/images/' . $row['room_image'])): ?>
                                    <figure><img src="admin/images/<?php echo $row['room_image']; ?>" alt="Room Image"/></figure>
                                <?php else: ?>
                                    <figure><img src="admin/images/default-room-image.jpg" alt="Default Room Image"/></figure>
                                <?php endif; ?>
                            </div>
                            <div class="rooms_room">
                                <h3><?php echo $row['room_type']; ?></h3>
                                <p><?php echo 'Available:' . $row['room_count']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No rooms found.</p>";
            }

            $conn->close();
            ?>
        </div>
    </div>
</div>

<!-- end rooms -->
<!--  contact -->
<div class="contact">
               <div class="container">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="titlepage">
                           <h2>Contact Us</h2>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <form id="request" class="main_form" action="contact_process.php" method="POST">
                           <div class="row">
                           <?php if (isset($email)): ?>
                                 <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                           <?php endif; ?>
                              <div class="col-md-12 ">
                                 <input class="contactus" placeholder="Name" type="text" name="name"> 
                              </div>
                              <div class="col-md-12">
                                 <input class="contactus" placeholder="Phone Number" type="tel" name="phone_number">                          
                              </div>
                              <div class="col-md-12">
                                 <textarea class="textarea" placeholder="Message" type="text" name="message"></textarea>
                              </div>
                              <div class="col-md-12">
                                 <button type="submit" class="send_btn">Send</button>
                              </div>
                           </div>
                        </form>
                     </div>
                     <div class="col-md-6">
                        <div class="map_main">
                           <div class="map-responsive">
                              <iframe src="https://www.google.com/maps/embed/v1/search?q=astro+hotel+calamba&key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8" width="600" height="400" frameborder="0" style="border:0; width: 100%;" allowfullscreen=""></iframe>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- end contact -->



<!-- Include the footer -->
<?php include 'footer.php'; ?>


<script>
    function submitReservation() {
        // Validate and submit the reservation form
        // You may want to add validation logic here

        // Submit the main reservation form
        document.getElementById('paymentForm').submit();
    }
</script>
<script>
        // This script will show the modal with the ID 'autoModal' when the page loads
        $(document).ready(function(){
            $('#autoModal').modal('show');
        });
    </script>