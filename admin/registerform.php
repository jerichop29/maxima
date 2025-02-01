<?php
session_start(); // Move session_start() to the top of the file

require 'dbconfig.php';

if (isset($_POST['submit'])) {
    $dbConfig = new DBConfig();
    $conn = $dbConfig->dbConnect();

    $error = array();

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $user_type = $_POST['user_type'];

    // Validate the form inputs
    if (empty($name) || empty($email) || empty($password) || empty($cpassword)) {
        $error[] = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = 'Invalid email format.';
    } elseif ($password != $cpassword) {
        $error[] = 'Passwords do not match.';
    } elseif (preg_match('/\d/', $name)) {
        $error[] = 'Name should not contain numbers.';
    }

    // If no errors, proceed to register the user
    if (empty($error)) {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insert = "INSERT INTO user_form (name, email, password, user_type) VALUES ('$name','$email','$hashedPassword','$user_type')";
        mysqli_query($conn, $insert);

        // Set a session variable to indicate successful registration
        $_SESSION['registration_success'] = true;

        header('location: loginform.php');
        exit();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <link rel="stylesheet" href="css/style2.css">
</head>
<body>
    <div class="form-container">
    
    <form action="" method="post">
        <h3>Register Now</h3>
        
        <?php
        if (isset($error)) {
            foreach ($error as $errorMsg) {
                echo '<span class="error-msg">' . $errorMsg . '</span>';
            }
        }
        ?>

        <input type="text" name="name" required placeholder="Enter your name"><br>
        <input type="email" name="email" required placeholder="Enter your email"><br>
        <input type="password" name="password" required placeholder="Enter your password"><br>
        <input type="password" name="cpassword" required placeholder="Confirm your password"><br>

        <input type="hidden" name="user_type" value="user">

        <input type="submit" name="submit" value="Register Now" class="form-btn">
        <p>Already have an account? <a href="loginform.php">Login Now</a></p>
    </form>

    </div>

</body>
</html>