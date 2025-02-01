<!-- Include the header -->
<?php include 'Aheader.php'; ?>
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

<div class="container">
    <h2>Search</h2>
    <form method="GET" action="">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Enter search keyword...">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Search</button>
            </span>
        </div>
    </form>
</div>
<br><br>

<?php
require_once 'class.reservation.php';

// Create an instance of HotelReservation
$reservation = new HotelReservation();

// Check if a search query has been submitted
if (isset($_GET['search'])) {
    // Get the search query from the form
    $searchQuery = $_GET['search'];

    // Perform the search and get the results
    $searchResults = $reservation->searchReservations($searchQuery);

    echo "<h1>Result: </h1>";
    if (!empty($searchResults)) {
        // Display the search results in a table
        echo '<table class="table">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>ID</th>';
        echo '<th>Email</th>';
        echo '<th>Check-in</th>';
        echo '<th>Check-out</th>';
        echo '<th>Room Type</th>';
        echo '<th>Time</th>';
        echo '<th>Payment Receipt</th>';
        echo '<th>Recorded At</th>';
        echo '<th>Status</th>';
        echo '<th>Action:</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        foreach ($searchResults as $row) {
            echo '<tr>';
            echo '<td>' . $row['ID'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td>' . $row['check_in'] . '</td>';
            echo '<td>' . $row['check_out'] . '</td>';
            echo '<td>' . $row['room_type'] . '</td>';
            echo '<td>' . $row['time'] . '</td>';
            
            // Display the Payment Receipt
            $paymentReceipt = $row['payment_receipt'];
            $imagePath = 'images/' . $paymentReceipt;
            if (file_exists($imagePath) && is_file($imagePath) && getimagesize($imagePath)) {
                echo '<td><img src="' . $imagePath . '" alt="Payment Receipt" width="100px"></td>';
            } else {
                echo '<td>' . htmlspecialchars($paymentReceipt) . '</td>';
            }

            echo '<td>' . $row['recorded_at'] . '</td>';
            echo '<td>' . $row['status'] . '</td>';
            echo '<td>';
            if ($row['status'] === 'pending') {
                echo '<a href="update_status.php?data=' . $row['ID'] . '" class="btn btn-success confirm-btn" onclick="return confirm(\'Are you sure you want to Confirm this record?\')">Confirm</a>';
            } else {
                echo '<a href="AeditRecord.php?data=' . $row['ID'] . '" class="btn btn-danger">Edit</a>';
            }
            echo '<a href="AdeleteRecord.php?data=' . $row['ID'] . '" class="btn btn-danger" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';
            echo '<a href="view.php?data=' . $row['ID'] . '" class="btn btn-danger">View</a>';
            echo '</td>';  
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo "No information found for the given search query.";
    }
}
?>

<a href="Arecord.php" class="btn btn-danger">Go back</a>
<!-- Include the footer -->
<?php include 'Afooter.php'; ?>
</body>

</html>




