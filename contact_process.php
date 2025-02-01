<?php
// Include necessary files and start session
include 'admin/dbconfig.php';
session_start();

// Function to preprocess text
function preprocess($text) {
    // Remove punctuation, convert to lowercase, and tokenize
    return explode(' ', strtolower(preg_replace('/[^\w\s]/', '', $text)));
}

// Function to train Naive Bayes classifier
function trainNaiveBayes($messages, $labels) {
    $spamMessages = [];
    $hamMessages = [];
    $spamWordCounts = [];
    $hamWordCounts = [];
    $spamTotalWords = 0;
    $hamTotalWords = 0;

    foreach ($messages as $index => $message) {
        $tokens = preprocess($message);
        foreach ($tokens as $token) {
            if ($labels[$index] === 'spam') {
                $spamMessages[] = $token;
                $spamWordCounts[$token] = ($spamWordCounts[$token] ?? 0) + 1;
                $spamTotalWords++;
            } else {
                $hamMessages[] = $token;
                $hamWordCounts[$token] = ($hamWordCounts[$token] ?? 0) + 1;
                $hamTotalWords++;
            }
        }
    }

    // Calculate prior probabilities
    $spamPrior = count($spamMessages) / count($messages);
    $hamPrior = count($hamMessages) / count($messages);

    // Calculate likelihoods of each word occurring in each class
    $spamWordProbabilities = [];
    $hamWordProbabilities = [];
    foreach (array_unique(array_merge($spamMessages, $hamMessages)) as $word) {
        $spamWordProbabilities[$word] = ($spamWordCounts[$word] ?? 0) / $spamTotalWords;
        $hamWordProbabilities[$word] = ($hamWordCounts[$word] ?? 0) / $hamTotalWords;
    }

    return [
        'spamPrior' => $spamPrior,
        'hamPrior' => $hamPrior,
        'spamWordProbabilities' => $spamWordProbabilities,
        'hamWordProbabilities' => $hamWordProbabilities
    ];
}

// Function to classify a message as spam or ham
function classifyMessage($message, $model) {
    $tokens = preprocess($message);
    $spamScore = log($model['spamPrior']);
    $hamScore = log($model['hamPrior']);

    foreach ($tokens as $token) {
        $spamScore += log($model['spamWordProbabilities'][$token] ?? 1); // Laplace smoothing
        $hamScore += log($model['hamWordProbabilities'][$token] ?? 1); // Laplace smoothing
    }

    return $spamScore > $hamScore ? 'spam' : 'ham';
}

// Load and preprocess the dataset
$csv = array_map('str_getcsv', file('spamtext.csv'));
$labels = array_column($csv, 0); // First column contains labels (spam or ham)
$messages = array_column($csv, 1); // Second column contains messages

// Train the Naive Bayes classifier
$naiveBayesModel = trainNaiveBayes($messages, $labels);

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $message = $_POST['message'];

    // Classify the message
    $classification = classifyMessage($message, $naiveBayesModel);

    // Insert into database only if the message is not classified as spam
    if ($classification !== 'spam') {
        // Initialize the $user_id variable
        $user_id = '';

        // Check if the user is already logged in and set the user_id in the session
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];

            // Query the user_id based on the email
            $dbConfig = new DBConfig();
            $conn = $dbConfig->dbConnect();

            $stmt = $conn->prepare("SELECT user_id FROM user_form WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $user_id = $row['user_id'];
            }

            $stmt->close();
            $conn->close();
        }

        // Insert into database
        $dbConfig = new DBConfig();
        $conn = $dbConfig->dbConnect();

        // Prepare and bind statement
        $stmt = $conn->prepare("INSERT INTO contacts (user_id, name, phone_number, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $name, $phone_number, $message);

        // Execute the statement
        if ($stmt->execute()) {
            // Success message
            echo '<script>alert("Message sent successfully!");</script>';
            
            // Redirect to index.php after successful insertion
            echo '<script>window.location.href = "index.php";</script>';
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    } else {
        // Message classified as spam, display warning
        echo '<script>alert("Your message is classified as spam and not sent. Please remove any spam-like content.");</script>';
        echo '<script>window.location.href = "index.php";</script>';
        exit();
    }
}
?>
