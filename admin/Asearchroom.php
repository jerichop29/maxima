<?php include 'Aheader.php'; ?>
<br>
<div class="back_re">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="title">
                    <h2>Search Rooms</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'class.room.php';

if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];

    // Create an instance of RoomManager
    $room = new Room();

    // Perform the search using the searchRooms method
    $rooms = $room->searchRooms($searchQuery);

    if ($rooms !== null) {
        // Display the search results
        foreach ($rooms as $room) {
            echo "<h1>Result: </h1><br>";
            echo "Room Name: " . $room['room_type'] . "<br>";
            echo "Room Count: " . $room['room_count'] . "<br>";
            echo "Room Price: " . $room['price'] . "<br>";
            echo "Room Image: <img src='images/" . $room['room_image'] . "' alt='Room Image' width='100'><br>";
            echo "<hr>";
        }
    }
}
?>

<a href="Aroom.php" class="btn btn-danger">Go back</a>
<!-- Include the footer -->
<?php include 'Afooter.php'; ?>
</body>
</html>


