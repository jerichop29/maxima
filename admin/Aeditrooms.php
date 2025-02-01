<!-- Include the header -->
<?php include 'Aheader.php'; ?>
<br>
      <div class="back_re">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title">
                    <h2>Edit Rooms</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>


   
<?php 
/*
get the data based on the username 
*/

$dbConn = new DBConfig();

$room_type= $_GET["data"]; 
$room_count = "";
$price = "";
$room_image = "";


$sql = "SELECT * FROM rooms WHERE room_type = '$room_type'";
$result = $dbConn->dbConnect()->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) 
    {
      $room_count = $row["room_count"];
      $price = $row["price"];
      $room_image = $row["room_image"];
    }
}
?>
<form method="POST" action="Aupdaterooms.php">
Room Name: <input type="text" class="form-control" value="<?php echo $room_type;?>" name="room_type" readonly><br>
Room Count: <input type="number" class="form-control" name="room_count"><br>
Price w/ resort: <input type="text" class="form-control" name="price"><br>
Room Image: <input type="file" class="form-control" name="room_image"><br>
<button type="submit" name="update" class="btn btn-primary" onclick="return confirm('Are you sure you want to update?');">Update</button>
<a href="Aroom.php" class="btn btn-danger">Go back</a>
</form>

   <!-- Include the footer -->
   <?php include 'Afooter.php'; ?>

   </body>
</html>
