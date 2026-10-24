<?php

include("db/connection.php");

session_start();

$username = $_POST["txt-username"];
$password = $_POST["txt-password"];



if ($username == "" || $password == "") {
    header("Location: index.php?error=empty");
    exit();
} else {
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";

    $result = $con->query($sql);

    $count = $result->num_rows;

    if ($count > 0) {

        $row = $result->fetch_assoc();

        $name = $row["full_name"];
        $role = $row["role"];
        $id = $row["user_id"];

        session_regenerate_id(true); // avoids the session fixation attacks

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
        header("Location: index.php?error=invalid");
        exit();
    }
}

?>