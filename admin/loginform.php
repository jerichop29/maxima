<?php
session_start(); // Move session_start() to the top of the file

require 'dbconfig.php';

if (isset($_POST['submit'])) {
    $dbConfig = new DBConfig();
    $conn = $dbConfig->dbConnect();

    $error = array();

    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($error)) {
        $select = "SELECT * FROM user_form WHERE email = '$email'";
        $result = mysqli_query($conn, $select);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_array($result);

            // Verify the password
            if (password_verify($password, $row['password'])) {
                $_SESSION['email'] = $email; // Store the email in a session variable
                if ($row['user_type'] == 'admin') {
                    header('location: Aindex.php');
                } elseif ($row['user_type'] == 'user') {
                    header('location: ../index.php');
                }
                exit();
            } else {
                $error[] = 'Incorrect email or password!';
            }
        } else {
            $error[] = 'Incorrect email or password!';
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="css/style2.css">
</head>

<body>
    <div class="form-container">

        <form action="" method="post">
            <h3>login now</h3>

            <?php
            if (isset($error)) {
                foreach ($error as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
            }
            ?>

            <input type="email" name="email" required placeholder="enter your email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>"><br>
            <input type="password" name="password" required placeholder="enter your password">
            <br>
            <input type="submit" name="submit" value="Login now" class="form-btn">
            <p>dont have an account? <a href="registerform.php">Sign up now</a></p>
        </form>

    </div>

    <script>
        // Check if the session variable 'registration_success' is set
        <?php
        if (isset($_SESSION['registration_success']) && $_SESSION['registration_success'] === true) {
            // Unset the session variable to avoid displaying the pop-up on subsequent page loads
            unset($_SESSION['registration_success']);
        ?>
        // Show the pop-up notification
        window.onload = function() {
            alert('Registration successful! You can Log in now.');
        };
        <?php
        }
        ?>
    </script>
</body>

</html>
