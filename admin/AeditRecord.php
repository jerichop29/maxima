<!-- Include the header -->
<?php include 'Aheader.php'; ?>
<br>
<div class="back_re">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h2>Edit Reservation Record</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
/*
get the data based on the email
*/

$dbConn = new DBConfig();

$ID = $_GET['data'];
$check_in = "";
$check_out = "";
$room_id = "";
$time = "";

$sql = "SELECT * FROM reservations WHERE ID = '$ID'";
$result = $dbConn->dbConnect()->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $check_in = $row["check_in"];
        $check_out = $row["check_out"];
        $room_id = $row["room_id"];
        $time = $row["time"];
    }
}
?>

<form method="POST" action="AupdateRecord.php">
    ID: <input type="text" class="form-control" value="<?php echo $ID; ?>" name="ID" readonly><br>
    Check in: <input type="date" class="form-control" name="check_in" value="<?php echo $check_in; ?>"><br>
    Check out: <input type="date" class="form-control" name="check_out" value="<?php echo $check_out; ?>"><br>
    
    <label for="time">Select time:</label>
     <select name="time" class="form-control">
        <option value="day(8 AM to 5 PM)">Day (8 AM to 5 PM)</option>
        <option value="night(8 PM to 6 AM)">Night (8 PM to 6 AM)</option>
     </select>
    
     <label for="room">Select Room:</label>
    <select name="room_id" class="form-control">
        <?php
        require_once 'dbconfig.php';

        $dbConfig = new DBConfig();
        $conn = $dbConfig->dbConnect();

        $result = $conn->query("SELECT * FROM rooms");

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $selected = ($room_id == $row['room_id']) ? "selected" : "";
                echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_type'] . "</option>";
            }
        }
        ?>
    </select>
    <button type="submit" name="update" class="btn btn-primary">Update</button>
    <a href="Arecord.php" class="btn btn-danger">Go back</a>
</form>

<!-- Include the footer -->
<?php include 'Afooter.php'; ?>
