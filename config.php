<?php
$conn = mysqli_connect("localhost", "root", "", "lab_sample.db");

if (!$conn) {
    die ("connection failed: " . mysqli_connect_error());
}

echo "database connect naguyud";
?>