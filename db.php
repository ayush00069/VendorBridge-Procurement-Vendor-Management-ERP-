<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "user_auth";
//Jatin
$conn = mysqli_connect($host, $user, $pass, $dbname);
// Test GitHub update
if(!$conn){
    die("Connection Failed: ".mysqli_connect_error());
}
?>