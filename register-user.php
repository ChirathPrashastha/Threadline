<?php

include("db/connection.php");

$username = $_POST["txt-username"];
$email = $_POST["txt-email"];
$password =$_POST["txt-password"];
$name = $_POST["txt-name"];

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$insert = "INSERT INTO users (full_name, email, username, password) VALUES ('$name', '$email', '$username', '$hashedPassword');";

if(mysqli_query($con, $insert)){
    header("Location: index.php");
    exit();
}

$con->close();

?>