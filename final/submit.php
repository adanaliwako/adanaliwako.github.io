<?php
// Database connection variables
$servername = "localhost";
$username = "root";  
$password = "";  
$dbname = "skyfield";  

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phone_number']); // Ensure this is treated as text
    $farmLocation = mysqli_real_escape_string($conn, $_POST['farm_location']);
    $farmSize = mysqli_real_escape_string($conn, $_POST['farm_size']);
    $messageSkyfield = mysqli_real_escape_string($conn, $_POST['message_skyfield']);
    $services = isset($_POST['services']) ? implode(", ", $_POST['services']) : '';

    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($phoneNumber)) {
        die("Error: Missing required fields.");
    }

    // SQL query to insert data into the database
    $sql = "INSERT INTO aerotech (first_name, last_name, phone_number, farm_location, farm_size, message_skyfield, services)
            VALUES ('$firstName', '$lastName', '$phoneNumber', '$farmLocation', '$farmSize', '$messageSkyfield', '$services')";

    // Execute the query and check for errors
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
