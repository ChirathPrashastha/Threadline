<?php
session_start();

if (!isset($_SESSION["USERNAME"])) {
    header("Location: index.php");
    exit();
}

include("db/connection.php"); 

$threadId = $_POST["thread-id"];
$userId = $_SESSION["USERID"];

$insert = "INSERT INTO likes (user_id, thread_id) VALUES ('$userId', '$threadId');";

if (mysqli_query($con, $insert)) {
    header("Location: user-dashboard.php");
    exit();
}

?>