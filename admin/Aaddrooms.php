<!-- Include the header -->
<?php include 'Aheader.php'; ?>
   <br>
      <div class="back_re">
         <div class="container">
            <div class="row">
               <div class="col-md-12">
                  <div class="title">
                    <h2>Add Rooms</h2>
                  </div>
               </div>
            </div>
         </div>
      </div>


      <!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content -->
</head>
<body>
    <!-- Form for adding a room -->
    <form method="POST" action="Asaverooms.php" enctype="multipart/form-data">
        <label for="room_type">Room Type:</label>
        <input type="text" class="form-control" name="room_type" required>
        <br>
        <label for="room_count">Room Count:</label>
        <input type="number" class="form-control" name="room_count" required>
        <br>
        <label for="price">Price:</label>
        <input type="text" class="form-control" name="price" required>
        <br>
        <label for="room_image">Room Image:</label>
        <input type="file" class="form-control" name="room_image" required>
        <br>
        <input type="submit" name="submit" class="btn btn-primary" value="Add Room">
    </form>

     <!-- Include the footer -->
   <?php include 'Afooter.php'; ?>
</body>
</html>