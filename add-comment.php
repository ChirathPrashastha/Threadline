<?php
session_start();

include("db/connection.php");

$userId = $_SESSION["USERID"];
$commentBody = $_POST["commentContent"];
$threadId = $_POST["threadId"];

if(!isset($_SESSION["USERNAME"])){
    header("Location: index.php");
    exit();
}

$insert = "INSERT INTO comments (user_id, thread_id, body) VALUES ('$userId', '$threadId', '$commentBody');";

if (mysqli_query($con, $insert)) {
    header("Location: user-dashboard.php");
    exit();
}
?>