<?php
$servername = "localhost";
$username = "root";
$password = "2003";
$dbname = "bookstore";

// Connect to MySQL server only (no DB yet)
$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $sql)) {
    // echo "Database '$dbname' is ready.<br>";
} else {
    die("Error creating database: " . mysqli_error($conn));
}

// Select the database
mysqli_select_db($conn, $dbname);

// echo "✅ Connected successfully to database '$dbname'";
?>
