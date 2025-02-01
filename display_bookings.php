<?php
require_once 'admin/dbconfig.php';

$dbConfig = new DBConfig();
$conn = $dbConfig->dbConnect();

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (!empty($email)) {
    // Update existing records
    $sql = "UPDATE reservations SET recorded_at = NOW() WHERE recorded_at IS NULL";
    $conn->query($sql);

    // Retrieve reservations with user email and room type
    $stmt = $conn->prepare("SELECT r.ID, u.email, r.check_in, r.check_out, ro.room_type, r.time, r.recorded_at, r.status
                        FROM reservations r
                            INNER JOIN user_form u ON r.user_id = u.user_id
                            INNER JOIN rooms ro ON r.room_id = ro.room_id
                            WHERE u.email = ?
                            ORDER BY r.recorded_at ASC");

    // Check if the statement was prepared successfully
    if ($stmt) {
        // Bind the email parameter
        $stmt->bind_param("s", $email);

        // Execute the prepared statement
        $stmt->execute();

        // Get the result set
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Display the table
            ?>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Email:</th>
                        <th>Check in:</th>
                        <th>Check out:</th>
                        <th>Room:</th>
                        <th>Time:</th>
                        <th>Recorded At:</th>
                        <th>Status:</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $row['ID']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['check_in']; ?></td>
                            <td><?php echo $row['check_out']; ?></td>
                            <td><?php echo $row['room_type']; ?></td>
                            <td><?php echo $row['time']; ?></td>
                            <td><?php echo $row['recorded_at']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <?php
        } else {
            echo "No reservations found for this account.";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // Handle the case when the statement preparation failed
        echo "Error in preparing the statement.";
    }
} else {
    echo "Please log in to view reservations.";
}

$conn->close();
?>
<a href="index.php" class="btn btn-primary">Reserve Again</a><br><br>
