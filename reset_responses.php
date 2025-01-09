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

// Redirect back to responses.php
header("Location: responses.php");
exit();
?>
