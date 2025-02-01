<?php
require_once 'dbconfig.php';

$dbConfig = new DBConfig();
$conn = $dbConfig->dbConnect();

$result = $conn->query("SELECT * FROM rooms");

if ($result->num_rows > 0) {
?>
<form action="" method="post">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
        <th>Room Type</th>
        <th>Room Count</th>
        <th>Price of Resort and Rooms</th>
        <th>Room Image</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?php echo $row['room_type']; ?></td>
          <td><?php echo $row['room_count']; ?></td>
          <td><?php echo '₱' . $row['price']; ?></td>
          <td><img src="images/<?php echo $row['room_image']; ?>" alt="Room Image" width="100"></td>
          <td>
            <a href="Aeditrooms.php?data=<?php echo $row['room_type']; ?>" class="btn btn-danger">Edit</a>
            <a href="Adeleteroom.php?data=<?php echo $row['room_type']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</form>
<?php
} else {
  echo "No rooms found.";
}

$conn->close();
?>

<a href="Aaddrooms.php" class="btn btn-primary">Add Rooms</a><br><br>
</body>
</html>
