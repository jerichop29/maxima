<?php

require_once 'dbconfig.php';

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

            // Retrieve the count of new contacts
            $stmt_count = $conn->prepare("SELECT COUNT(*) as new_contacts
                                          FROM contacts
                                          WHERE user_id = ? AND created_at IS NULL");

            if ($stmt_count) {
                $stmt_count->bind_param("i", $user_id); // Assuming user_id is an integer
                $stmt_count->execute();
                $result_count = $stmt_count->get_result();

                if ($result_count->num_rows > 0) {
                    $row_count = $result_count->fetch_assoc();
                    $new_contacts = $row_count['new_contacts'];

                    // Display the count if there are new contacts
                    if ($new_contacts > 0) {
                        echo '<span class="badge badge-danger">' . $new_contacts . '</span>';
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
