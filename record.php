<?php
session_start(); // Move session_start() to the top of the file
include 'admin/dbconfig.php';
?>
<!-- Include the header -->
<?php include 'header.php'; ?>
      <br>
      <div class="back_re">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title">
                    <h2>Reservation Record</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <script>
        // Check if the session variable 'registration_success' is set
        <?php
        if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
            // Unset the session variable to avoid displaying the pop-up on subsequent page loads
            unset($_SESSION['registration_success']);
        ?>
        // Show the pop-up notification
        window.onload = function() {
            alert('Reservation Request Successfully! Your reservation still pending, wait for the Admin to confirm your reservation.');
        };
        <?php
        }
        ?>
    </script>

    <?php
    require 'display_bookings.php'; 
    ?>
      <!-- Include the header -->
   <?php include 'footer.php'; ?>
   </body>
</html>







