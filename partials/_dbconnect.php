<?php

// Connecting to the Database
$servername = "localhost";
$username = "root";
$password = "";
// $database = "webtoon_world";
$database = "webtoon_world2";

// Create a connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Die if connection was not successful
if (!$conn) {
    die("Sorry we failed to connect: " . mysqli_connect_error());
} else {
    // echo "Connection was successful<br>";
}