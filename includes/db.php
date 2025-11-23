<?php
// Database connection file

function connectDB() {
    $servername = "localhost";
    $username = "root"; // Replace with your MySQL username
    $password = "root";     // Replace with your MySQL password
    $dbname = "notecore_db"; // Replace with your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Example usage:
// $conn = connectDB();
// if ($conn) {
//     echo "Connected successfully";
//     $conn->close();
// }
?>
