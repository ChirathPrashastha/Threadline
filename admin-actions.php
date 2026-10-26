<?php

session_start();
include("db/connection.php");

if (!isset($_SESSION["USERNAME"]) || $_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

$loggedUser = $con->real_escape_string($_SESSION["USERNAME"]);
$adminRst = $con->query("SELECT role FROM users WHERE username='$loggedUser'");
$adminRow = $adminRst->fetch_assoc();

if (!$adminRow || $adminRow["role"] != "ADMIN") {
    header("Location: user-dashboard.php");
    exit();
}

$action = $_POST["action"] ?? "";
$view = $_POST["view"] ?? "overview";
$reportId = (int) ($_POST["report_id"] ?? 0);
$threadId = (int) ($_POST["thread_id"] ?? 0);
$commentId = (int) ($_POST["comment_id"] ?? 0);
$userId = (int) ($_POST["user_id"] ?? 0);
$open = (int) ($_POST["open"] ?? 0);

if ($view != "overview" && $view != "reports" && $view != "users" && $view != "threads") {
    $view = "overview";
}

$back = "Location: admin-dashboard.php?view=$view";


if ($action == "dismiss_report" && $reportId > 0) {

    $con->query("DELETE FROM reports WHERE report_id = $reportId");

    header($back . "&msg=dismissed");
    exit();
}

if ($action == "remove_thread" && $threadId > 0) {

    $con->query("UPDATE threads SET status = 'REMOVED' WHERE thread_id = $threadId");
    $con->query("UPDATE reports SET status = 'RESOLVED' WHERE thread_id = $threadId");

    header($back . "&msg=thread_removed");
    exit();
}

if ($action == "remove_comment" && $commentId > 0) {

    $con->query("DELETE FROM reports WHERE comment_id = $commentId");
    $con->query("DELETE FROM comments WHERE comment_id = $commentId");

    header($back . "&msg=comment_removed&open=$open");
    exit();
}

if ($action == "ban_user" && $userId > 0) {

    $con->query("UPDATE users SET status = 'BANNED' WHERE user_id = $userId AND role = 'USER'");
    $con->query("UPDATE reports SET status = 'RESOLVED' WHERE report_id = $reportId");

    header($back . "&msg=banned");
    exit();
}

header($back . "&msg=failed");
exit();
?>
