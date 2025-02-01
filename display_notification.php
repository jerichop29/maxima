<?php
require_once 'admin/dbconfig.php';

$dbConfig = new DBConfig();
$conn = $dbConfig->dbConnect();

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (!empty($email)) {
    // Update existing records
    $sql = "UPDATE notification SET timestamp = NOW() WHERE timestamp IS NULL";
    $conn->query($sql);

    // Retrieve user_id based on email
    $stmt_user = $conn->prepare("SELECT user_id FROM user_form WHERE email = ?");
    
    // Check if the statement for user_id was prepared successfully
    if ($stmt_user) {
        // Bind the email parameter
        $stmt_user->bind_param("s", $email);
        
        // Execute the prepared statement
        $stmt_user->execute();
        
        // Get the result set
        $result_user = $stmt_user->get_result();
        
        if ($result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            $user_id = $row_user['user_id'];

            // Retrieve notifications with user_id
            $stmt = $conn->prepare("SELECT notification_id, user_id, message, timestamp
                                    FROM notification
                                    WHERE user_id = ?
                                    ORDER BY timestamp ASC");

            // Check if the statement for notifications was prepared successfully
            if ($stmt) {
                // Bind the user_id parameter
                $stmt->bind_param("s", $user_id);

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
                                <th>Message:</th>
                                <th>Timestamp:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['message']; ?></td>
                                    <td><?php echo $row['timestamp']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "No notifications found for this account.";
                }

                // Close the prepared statement for notifications
                $stmt->close();
            } else {
                // Handle the case when the statement preparation for notifications failed
                echo "Error in preparing the notification statement.";
            }
        } else {
            echo "User not found with the given email.";
        }

        // Close the prepared statement for user_id
        $stmt_user->close();
    } else {
        // Handle the case when the statement preparation for user_id failed
        echo "Error in preparing the user_id statement.";
    }
} else {
    echo "Please log in to view notifications.";
}

$conn->close();
?>
