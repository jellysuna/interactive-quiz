<?php
require 'config.php';

// Delete all responses
$sql = "TRUNCATE TABLE responses";

if ($conn->query($sql) === TRUE) {
    echo "Responses reset successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

// Redirect back to index.php
header("Location: index.php");
exit();
?>
