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

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $farmLocation = mysqli_real_escape_string($conn, $_POST['farm_location']);
    $farmSize = mysqli_real_escape_string($conn, $_POST['farm_size']);
    $services = isset($_POST['services']) ? implode(", ", $_POST['services']) : '';

    // SQL query to insert data into the database
    $sql = "INSERT INTO aerotech (first_name, last_name, phone_number, farm_location, farm_size, services)
            VALUES ('$firstName', '$lastName', '$phoneNumber', '$farmLocation', '$farmSize', '$services')";

    if ($conn->query($sql) === TRUE) {
        // Prepare email content manually without special encoding
        $subject = "New Form Submission from $firstName $lastName";

        // Manually create the body of the email with clean text
        $body = "First Name: $firstName\nLast Name: $lastName\nPhone Number: $phoneNumber\nFarm Location: $farmLocation\nFarm Size: $farmSize\nServices: $services";

        // Construct the mailto URL with properly encoded values
        $toEmail = "skyfield.kenya@gmail.com";  // Your company email address
        $mailto = "mailto:$toEmail?subject=" . urlencode($subject) . "&body=" . urlencode($body);

        // Prevent immediate redirection to index.html
        echo "<script type='text/javascript'>
                // Open the email client
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
