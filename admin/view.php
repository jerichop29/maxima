<!-- Include the header -->
<?php include 'Aheader.php'; ?>
    <br>
      <div class="back_re">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title">
                    <h2>View Record</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>

<?php
require_once 'dbconfig.php';

$dbConfig = new DBConfig();
$conn = $dbConfig->dbConnect();

// Check if the 'data' parameter is provided in the URL
if (isset($_GET['data'])) {
    // Sanitize the input to prevent SQL injection
    $recordId = mysqli_real_escape_string($conn, $_GET['data']);

    // Query to retrieve the specific record based on the provided ID
    $query = "SELECT r.ID, u.email, r.check_in, r.check_out, ro.room_type, r.time, r.recorded_at, r.status, r.payment_receipt
              FROM reservations r
              INNER JOIN user_form u ON r.user_id = u.user_id
              INNER JOIN rooms ro ON r.room_id = ro.room_id
              WHERE r.ID = '$recordId'";

    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Record not found.";
        exit; // Stop further execution if the record is not found
    }
} else {
    echo "Invalid request.";
    exit; // Stop further execution if 'data' parameter is not provided in the URL
}

$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>View Record</title>
    <!-- Add any necessary CSS and JavaScript here -->
    <style>
        /* Custom CSS to make the receipt image larger */
        .receipt-img {
            max-width: 100%;
            max-height: 700px;
        }
    </style>
</head>

<body>
    <h2>View Record</h2>
    <table class="table">
        <tr>
            <th>ID:</th>
            <td><?php echo $row['ID']; ?></td>
        </tr>
        <tr>
            <th>Email:</th>
            <td><?php echo $row['email']; ?></td>
        </tr>
        <tr>
            <th>Check in:</th>
            <td><?php echo $row['check_in']; ?></td>
        </tr>
        <tr>
            <th>Check out:</th>
            <td><?php echo $row['check_out']; ?></td>
        </tr>
        <tr>
            <th>Room:</th>
            <td><?php echo $row['room_type']; ?></td>
        </tr>
        <tr>
            <th>Time:</th>
            <td><?php echo $row['time']; ?></td>
        </tr>
        <tr>
            <th>Recorded At:</th>
            <td><?php echo $row['recorded_at']; ?></td>
        </tr>
        <tr>
            <th>Status:</th>
            <td><?php echo $row['status']; ?></td>
        </tr>
        <tr>
            <th>Receipt:</th>
            <td>
                <?php if (isset($row['payment_receipt']) && !empty($row['payment_receipt'])) : ?>
                    <?php if (is_file('images/' . $row['payment_receipt'])) : ?>
                        <img src="images/<?php echo $row['payment_receipt']; ?>" alt="Payment Image" class="receipt-img">
                    <?php else : ?>
                        <?php echo $row['payment_receipt']; ?>
                    <?php endif; ?>
                <?php else : ?>
                    No payment receipt
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <!-- Back button to go back to Arecord.php -->
    <a href="Arecord.php" class="btn btn-primary">Back</a>

    <?php include 'Afooter.php'; ?>
</body>

</html>
