<?php
$servername = "127.0.0.1";
$username = "root";
$password = "123456";
$dbname = "pgt";


// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    echo "DB Connection filed!";
}

?>