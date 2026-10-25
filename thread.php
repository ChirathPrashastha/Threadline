<?php
session_start();
$userId = $_SESSION["USERID"];

include("db/connection.php");

$title = $_POST["txtTitle"] ?? "";
$content = $_POST["txtContent"] ?? "";

if ($title == "" || $content == "") {
    header("Location: user-dashboard.php?error=empty");
    exit();
} else {
    $insert = "INSERT INTO threads (user_id, title, content) VALUES ('$userId', '$title', '$content');";

    if (mysqli_query($con, $insert)) {
        header("Location: user-dashboard.php");
        exit;
    } else {
        header("Location: user-dashboard.php?error=failed");
        exit();
    }
}

?>