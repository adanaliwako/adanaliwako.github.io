<?php
// Database connection variables
$servername = "localhost";
$username = "root";  // Replace with your actual database username
$password = "";  // Leave empty if no password is set for 'root' in XAMPP
$dbname = "skyfield";  // The database you're working with

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted via GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Retrieve and sanitize form data
    $firstName = isset($_GET['first_name']) ? mysqli_real_escape_string($conn, $_GET['first_name']) : '';
    $lastName = isset($_GET['last_name']) ? mysqli_real_escape_string($conn, $_GET['last_name']) : '';
    $phoneNumber = isset($_GET['phone_number']) ? mysqli_real_escape_string($conn, $_GET['phone_number']) : '';
    $farmLocation = isset($_GET['farm_location']) ? mysqli_real_escape_string($conn, $_GET['farm_location']) : '';
    $farmSize = isset($_GET['farm_size']) ? mysqli_real_escape_string($conn, $_GET['farm_size']) : '';
    $messageSkyfield = isset($_GET['message_skyfield']) ? mysqli_real_escape_string($conn, $_GET['message_skyfield']) : '';
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($phoneNumber)) {
        die("Error: Missing required fields.");
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO aerotech (first_name, last_name, phone_number, farm_location, farm_size, message_skyfield)
            VALUES ('$firstName', '$lastName', '$phoneNumber', '$farmLocation', '$farmSize', '$messageSkyfield')";

    if ($conn->query($sql) === TRUE) {
        // Prepare email content manually without special encoding
        $subject = "New Form Submission from $firstName $lastName";

        // Manually create the body of the email with clean text
        $body = "First Name: $firstName\nLast Name: $lastName\nPhone Number: $phoneNumber\nFarm Location: $farmLocation\nFarm Size: $farmSize\nMessage: $messageSkyfield";

        // Construct the mailto URL with properly encoded values
        $toEmail = "skyfield.kenya@gmail.com";  // Your company email address
        $mailto = "mailto:$toEmail?subject=" . urlencode($subject) . "&body=" . urlencode($body);

        // Open the email client
        echo "<script type='text/javascript'>
                window.location.href = '$mailto';
                // After a delay, redirect to index.html for fresh inputs
                setTimeout(function() {
                    window.location.href = 'index.html';
                }, 5000); // 5 seconds delay
              </script>";

        // Close the connection
        $conn->close();
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
