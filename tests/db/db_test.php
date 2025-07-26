<?php
$servername = "db"; // The service name of your MySQL container
$username = "gemini"; // The user defined in docker-compose.yml
$password = "sql_password"; // The password defined in docker-compose.yml
$dbname = "quiz_db"; // The database name defined in docker-compose.yml

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully to MySQL!";

// Close connection
$conn->close();
?>