<?php
session_start();

if(!isset($_SESSION["USERNAME"])){
    header("Location: index.php");
    exit();
}

$userId = $_SESSION["USERID"];

include("db/connection.php");

$title = $_POST["txtTitle"] ?? "";
$content = $_POST["txtContent"] ?? "";

$insert = "INSERT INTO threads (user_id, title, content) VALUES ('$userId', '$title', '$content');";

if (mysqli_query($con, $insert)) {
    header("Location: user-dashboard.php");
    exit;
} else {
    header("Location: user-dashboard.php?error=failed");
    exit();
}

?>