<?php

// Function to sanitize form input
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fullName = sanitizeInput($_POST["fullName"]);
    $email = sanitizeInput($_POST["email"]);
    $phoneNumber = sanitizeInput($_POST["phoneNumber"]);
    $purchaseDate = sanitizeInput($_POST["purchaseDate"]);
    $productModel = sanitizeInput($_POST["productModel"]);
    $serialNumber = sanitizeInput($_POST["serialNumber"]);


    // Initialize an array to store validation errors
    $errors = array();

    // Validate Full Name
    if (empty($fullName)) {
        $errors[] = "Full Name is required.";
    } elseif (strlen($fullName) < 3 || strlen($fullName) > 50) {
        $errors[] = "Full Name should be between 3 and 50 characters.";
    }

    // Validate Email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Database Connection
    $conn = new mysqli('localhost', 'root', '', 'warranty registration');


    if ($conn->connect_error) {
        die('Connection Failed : ' . $conn->connect_error);
    } else {
        if (count($errors) > 0) {
            // Display validation errors to the user
            echo "<ul>";
            foreach ($errors as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
        } else {
            // Prepare and execute SQL statement
            $stmt = $conn->prepare("INSERT INTO registration (fullName, email, phoneNumber, purchaseDate, productModel, serialNo) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $fullName, $email, $phoneNumber, $purchaseDate, $productModel, $serialNumber);

            if ($stmt->execute()) {
                echo "Registration Successfully...";
                // Redirect to the warranty registration file
                header("Location: index.html");
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close statement and connection
            $stmt->close();
            $conn->close();
        }
    }
} else {
    // If the form is not submitted, redirect to the form page
    header("Location: your_form_page.html");
    exit;
}




?>
