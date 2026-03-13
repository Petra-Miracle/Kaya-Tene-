<?php
require 'config/Connection.php';
if($conn->query($sql) === TRUE) {
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}
?>
