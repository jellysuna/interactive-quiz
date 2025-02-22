<?php
require 'config.php';

$sql = "TRUNCATE TABLE responses";

if ($conn->query($sql) === TRUE) {
    echo "Responses reset successfully";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

header("Location: index.php");
exit();
?>