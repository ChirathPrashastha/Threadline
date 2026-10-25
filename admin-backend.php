<?php
session_start();

include("db/connection.php");


if (!isset($_SESSION["USERNAME"])) {
    header("Location: index.php");
    exit();
}

$loggedUser = $_SESSION["USERNAME"];

$sqlAdmin = "SELECT * FROM users WHERE username='$loggedUser'";
$resultAdmin = $con->query($sqlAdmin);
$adminRow = $resultAdmin->fetch_assoc();

if (!$adminRow || $adminRow["role"] != "ADMIN") {
    header("Location: user-dashboard.php");
    exit();
}


$sql = "SELECT * FROM users WHERE role='USER'";
$result = $con->query($sql);
$totalUsers = $result->num_rows;


$sql = "SELECT * FROM threads WHERE status='ACTIVE'";
$result = $con->query($sql);
$totalThreads = $result->num_rows;


$sql = "SELECT * FROM reports WHERE status='OPEN'";
$result = $con->query($sql);
$totalReports = $result->num_rows;


$sql = "SELECT * FROM users WHERE role='USER' AND status='BANNED'";
$result = $con->query($sql);
$totalBanned = $result->num_rows;


$reportsSql = "SELECT reports.report_id, reports.reason, reports.created_at,
                      users.username
               FROM reports
               INNER JOIN users
               ON reports.reporter_id = users.user_id
               WHERE reports.status = 'OPEN'
               ORDER BY reports.created_at DESC";

$reportsResult = $con->query($reportsSql);
?>
