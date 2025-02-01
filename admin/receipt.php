<!-- receipt.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
</head>
<body>
    <h1>Receipt</h1>
    <?php
    // Check if the receipt image exists and is accessible
    if (isset($_GET['filename'])) {
        $filename = $_GET['filename'];
        $filepath = 'receipts/' . $filename;

        if (file_exists($filepath)) {
            // Display the receipt image
            echo '<img src="' . $filepath . '" alt="Receipt">';
        } else {
            // Image not found
            echo '<p>Receipt not found.</p>';
        }
    } else {
        // No receipt filename provided
        echo '<p>No receipt selected.</p>';
    }
    ?>
</body>
</html>
