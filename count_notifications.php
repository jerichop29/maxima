<?php

require_once 'admin/dbconfig.php';

// Establish database connection
$dbConfig = new DBConfig();
$conn = $dbConfig->dbConnect();

// Retrieve the user's email from the session
$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (!empty($email)) {
    // Retrieve user_id based on email
    $stmt_user = $conn->prepare("SELECT user_id FROM user_form WHERE email = ?");
    
    if ($stmt_user) {
        $stmt_user->bind_param("s", $email);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        
        if ($result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            $user_id = $row_user['user_id'];

            // Retrieve the count of new notifications
            $stmt_count = $conn->prepare("SELECT COUNT(*) as new_notifications
                                          FROM notification
                                          WHERE user_id = ? AND timestamp IS NULL");

            if ($stmt_count) {
                $stmt_count->bind_param("s", $user_id);
                $stmt_count->execute();
                $result_count = $stmt_count->get_result();

                if ($result_count->num_rows > 0) {
                    $row_count = $result_count->fetch_assoc();
                    $new_notifications = $row_count['new_notifications'];

                    // Display the count if there are new notifications
                    if ($new_notifications > 0) {
                        echo '<span class="badge badge-danger">' . $new_notifications . '</span>';
                    }
                }

                $stmt_count->close();
            }
        }

        $stmt_user->close();
    }
}

// Close the database connection
$conn->close();
?>
