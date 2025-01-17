<?php
// Include your database connection configuration here
$host = "localhost";
$username = "root";
$password = "";
$database = "blockchain";

// Create a database connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}