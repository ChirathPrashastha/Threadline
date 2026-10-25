<?php
session_start();

if (!isset($_SESSION["USERNAME"])) {
    header("Location: index.php");
    exit();
}

include("db/connection.php"); 

$reporter = $_SESSION["USERID"];

if($_POST["type"] == "THREAD") {
    $reportedThreadId = $_POST["id"];
    
    $insertThread = "INSERT INTO reports (reporter_id, thread_id) VALUES ('$reporter', '$reportedThreadId')";

    if(mysqli_query($con, $insertThread)) {
        header("Location: user-dashboard.php?msg=reported");
    }else{
        header("Location: user-dashboard.php?msg=failed");
    }
    exit();
}else if($_POST["type"] == "COMMENT") {
    $reportedCommentId = $_POST["id"];

    $insertCom = "INSERT INTO reports (reporter_id, comment_id) VALUES ('$reporter', '$reportedCommentId')";

    if(mysqli_query($con, $insertCom)) {
        header("Location: user-dashboard.php?msg=reported");
    }else{
        header("Location: user-dashboard.php?msg=failed");
    }
    exit();
}

?>