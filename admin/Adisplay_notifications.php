<?php
require_once 'dbconfig.php';

$dbConfig = new DBConfig();
$conn = $dbConfig->dbConnect();

$email = isset($_SESSION['email']) ? $_SESSION['email'] : '';

if (!empty($email)) {
    // Retrieve user_id based on email
    $stmt_user = $conn->prepare("SELECT user_id, name FROM user_form WHERE email = ?");
    
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
            $user_name = $row_user['name'];

            // Retrieve messages for all users
            $stmt_messages = $conn->prepare("SELECT 'message' as type, contacts.contact_id, contacts.phone_number, user_form.email, user_form.name, contacts.message, contacts.created_at
                                    FROM contacts
                                    INNER JOIN user_form ON contacts.user_id = user_form.user_id
                                    ORDER BY created_at ASC");

            // Check if the statement for messages was prepared successfully
            if ($stmt_messages) {
                // Execute the prepared statement
                $stmt_messages->execute();

                // Get the result set
                $result_messages = $stmt_messages->get_result();

                if ($result_messages->num_rows > 0) {
                    // Display the table
                    ?>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Name:</th>
                                <th>Phone:</th>
                                <th>Message:</th>
                                <th>Timestamp:</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result_messages->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['name'];  ?></td>
                                    <td><?php echo $row['phone_number']; ?></td>
                                    <td><?php echo $row['message']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php
                } else {
                    echo "No messages found for any user.";
                }

                // Close the prepared statement for messages
                $stmt_messages->close();
            } else {
                // Handle the case when the statement preparation for messages failed
                echo "Error in preparing the messages statement.";
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
    echo "Please log in to view messages.";
}

$conn->close();
?>
