<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "quizsystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Delete all responses
$sql = "TRUNCATE TABLE responses";

if ($conn->query($sql) === TRUE) {
    echo "Responses reset successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

// Redirect back to responses.php
header("Location: responses.php");
exit();
?>
