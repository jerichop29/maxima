<?php 

class Room
{
    private $conn;

    public function __construct()
    {
        // Initialize the database connection
        require_once 'dbconfig.php';
        $dbConfig = new DBConfig();
        $this->conn = $dbConfig->dbConnect();
    }

    public function addRoom($room_type, $room_count, $price, $room_image)
    {
        // Prepare the SQL statement
        $stmt = $this->conn->prepare("INSERT INTO rooms (room_type, room_count, price, room_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siis", $room_type, $room_count, $price, $room_image);
    
        // Execute the SQL statement
        if ($stmt->execute()) {
            // Room added successfully, redirect to room listing page
            header("location: Aroom.php");
            exit();
        } else {
            // Error occurred while adding the room
            echo "Error: " . $stmt->error;
        }
    
        $stmt->close();
    }
    

    public function updateRoom($room_type, $room_count, $price, $room_image)
    {
        $stmt = $this->conn->prepare("UPDATE rooms SET room_count = ?, price = ?, room_image = ? WHERE room_type = ?");
        $stmt->bind_param("iiss", $room_count, $price, $room_image, $room_type);

        if ($stmt->execute()) {
            header("Location: Aroom.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    public function deleteRoom($room_type)
    {
        $stmt = $this->conn->prepare("DELETE FROM rooms WHERE room_type = ?");
        $stmt->bind_param("s", $room_type);

        if ($stmt->execute()) {
            return true;
        } else {
            echo "Error: " . $stmt->error;
            return false;
        }
    }

    public function searchRooms($searchQuery)
    {
        $searchQuery = mysqli_real_escape_string($this->conn, $searchQuery);
        $sql = "SELECT * FROM rooms WHERE room_type LIKE '%$searchQuery%'";
        $result = mysqli_query($this->conn, $sql);

        if (!$result) {
            echo "Error: " . mysqli_error($this->conn);
            return null;
        }

        $rooms = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rooms[] = $row;
        }

        return $rooms;
    }
  }
?>