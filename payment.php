<!-- Include the header -->
<?php include 'header.php'; ?>

      <div class="back_re">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title">
                    <h2>Payment</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>

<!-- Include the required files -->
<?php
include 'admin/dbconfig.php';
include 'class.reservation.php';
session_start();

// Check if the user is already logged in and set the email in the session
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
} else {
    // Redirect to index.php if the user is not logged in
    header("Location: index.php");
    exit();
}

// Check if the form was submitted
if (isset($_POST['submit'])) {
    // Process the payment
    // ... Add payment processing logic here ...

    // After successful payment, redirect to record.php
    header("Location: record.php");
    exit();
}
?>

<!-- Include the header -->
<?php include 'header.php'; ?>
<!-- Include banner and payment form -->
<!-- ... -->

<div class="col-md-12">
    <a href="calendar.php" class="btn btn-primary">View Available</a>
    <input type="submit" name="submit" value="Reserve" class="btn btn-primary" onclick="return confirm('Are you sure you want to reserve?');">
</div>

</form>
</div>
</div>
</div>
</div>
</section>
<!-- end banner -->
<!-- Include the footer -->
<?php include 'footer.php'; ?>
