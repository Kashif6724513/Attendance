<?php
$host = 'localhost';
$user = 'root';
$password = '';
$db = 'office_attendence';
$conn = mysqli_connect($host, $user, $password, $db);

if ($conn->connect_error) {
    die("connection failed" . $conn->connect_error);
}
