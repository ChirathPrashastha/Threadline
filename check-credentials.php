<?php

include("db/connection.php");

$username = $_POST["txt-username"];
$password = $_POST["txt-password"];

session_start();

$sql = "SELECT * FROM users WHERE username='$username'";

$result = $con->query($sql);

$count = $result->num_rows;

if ($count > 0) {

    $row = $result->fetch_assoc();

    $name = $row["full_name"];
    $role = $row["role"];
    $id = $row["user_id"];
    $hashedPassword = $row["password"];

    if (password_verify($password, $hashedPassword)) {
        if ($role == "ADMIN") {
            $_SESSION["USERNAME"] = $username;
            $_SESSION["NAME"] = $name;
            header("Location: admin-dashboard.php");

            exit();
        } else if ($role == "USER") {
            $_SESSION["USERNAME"] = $username;
            $_SESSION["NAME"] = $name;
            $_SESSION["USERID"] = $id;
            header("Location: user-dashboard.php");

            exit();
        }
    } else {
        $_SESSION["ERROR"] = "invalid";
        header("Location: index.php");
        exit();
    }

} else {
    $_SESSION["ERROR"] = "invalid";
    header("Location: index.php");
    exit();
}


?>