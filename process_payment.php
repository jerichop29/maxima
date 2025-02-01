<?php
// process_payment.php

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the user input from the form
    $gcashNumber = $_POST['gcash_number'];
    $amount = $_POST['amount'];

    // Handle the receipt image file upload
    $targetDir = 'receipts/';
    $targetFile = $targetDir . basename($_FILES['receipt_image']['name']);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the uploaded file is an image
    if (isset($_POST['submit'])) {
        $check = getimagesize($_FILES['receipt_image']['tmp_name']);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if the file already exists (you might want to handle this differently)
    if (file_exists($targetFile)) {
        echo "Sorry, the file already exists.";
        $uploadOk = 0;
    }

    // Check file size (you can set a maximum file size)
    if ($_FILES['receipt_image']['size'] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only specific image file formats (you can add more formats as needed)
    if ($imageFileType !== "jpg" && $imageFileType !== "png" && $imageFileType !== "jpeg") {
        echo "Sorry, only JPG, JPEG, and PNG files are allowed.";
        $uploadOk = 0;
    }

    // If $uploadOk is set to 0, there was an error in file upload
    if ($uploadOk === 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $targetFile)) {
            echo "The file " . basename($_FILES['receipt_image']['name']) . " has been uploaded successfully.";

            // Save the payment details and receipt image path in your database or perform other actions
            // ...
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Display the payment statement to the user
    echo "<h2>Payment Statement</h2>";
    echo "<p>You have successfully made a payment of $amount PHP to GCash Number: $gcashNumber.</p>";
    echo "<p>Receipt Image: <img src='$targetFile' alt='Receipt Image' width='300'></p>";
} else {
    echo "Invalid request method.";
}
?>
