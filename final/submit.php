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
    // Retrieve and sanitize form data
    $firstName = isset($_POST['first_name']) ? mysqli_real_escape_string($conn, $_POST['first_name']) : '';
    $lastName = isset($_POST['last_name']) ? mysqli_real_escape_string($conn, $_POST['last_name']) : '';
    $phoneNumber = isset($_POST['phone_number']) ? mysqli_real_escape_string($conn, $_POST['phone_number']) : '';
    $farmLocation = isset($_POST['farm_location']) ? mysqli_real_escape_string($conn, $_POST['farm_location']) : '';
    $farmSize = isset($_POST['farm_size']) ? mysqli_real_escape_string($conn, $_POST['farm_size']) : '';
    $messageSkyfield = isset($_POST['message_skyfield']) ? mysqli_real_escape_string($conn, $_POST['message_skyfield']) : '';
    $services = isset($_POST['services']) && is_array($_POST['services']) ? implode(", ", $_POST['services']) : '';

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($phoneNumber)) {
        die("Error: Missing required fields.");
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO aerotech (first_name, last_name, phone_number, farm_location, farm_size, message_skyfield, services)
            VALUES ('$firstName', '$lastName', '$phoneNumber', '$farmLocation', '$farmSize', '$messageSkyfield', '$services')";

    // Execute the query and check for errors
    if ($conn->query($sql) === TRUE) {
        // Prepare email content manually without special encoding
        $subject = "New Form Submission from $firstName $lastName";
        $body = "First Name: $firstName\nLast Name: $lastName\nPhone Number: $phoneNumber\nFarm Location: $farmLocation\nFarm Size: $farmSize\nMessage: $messageSkyfield\nServices: $services";
        $toEmail = "skyfield.kenya@gmail.com";  // Your company email address
        $mailto = "mailto:$toEmail?subject=" . urlencode($subject) . "&body=" . urlencode($body);

        // Open the email client and redirect to index.html after a delay
        echo "<script type='text/javascript'>
                window.location.href = '$mailto';
                setTimeout(function() {
                    window.location.href = 'index.html';
                }, 5000); // 5 seconds delay
              </script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
