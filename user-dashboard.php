<?php

session_start();

$messages = [
    "reported" => "Reported successfully. An admin will review it.",
    "failed"   => "Something went wrong. Please try again."
];
$msg = $messages[$_GET["msg"] ?? ""] ?? "";

$loggedUser = $_SESSION["USERNAME"];
$loggedUserName = $_SESSION["NAME"];

include("db/connection.php");
$select = "SELECT * FROM threads WHERE status != 'REMOVED';";

$result = $con->query($select);

$topUsersSql = "SELECT 
    u.user_id,
    u.full_name,
    u.username,
    COUNT(t.thread_id) AS thread_count
FROM 
    users u
JOIN 
    threads t ON u.user_id = t.user_id
WHERE 
    YEAR(t.created_at) = YEAR(CURRENT_DATE())
    AND MONTH(t.created_at) = MONTH(CURRENT_DATE())
GROUP BY 
    u.user_id, 
    u.full_name, 
    u.username
ORDER BY 
    thread_count DESC
LIMIT 3;";

$rstTopUsers = $con->query($topUsersSql);


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadline User Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@200..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="assets/CSS/user-dashboard.css">
</head>

<body>
    <section id="user-dashboard-section">

        <table id="table-main">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <div id="threadline-logo">
                                    Threadline
                                </div>
                            </td>

                            <td id="search-td">
                                <input type="text" placeholder="Search threads or authors" id="txtSearchAuthors">
                            </td>

                            <td id="avatar-td">
                                <div class="avatar" id="user-avatar">
                                    <?php
                                    echo strtoupper(substr($loggedUserName, 0, 2));
                                    ?>
                                </div>
                            </td>

                            <td id="username-td">
                                <?php
                                echo $loggedUser;
                                ?>
                            </td>

                            <td id="logout-td"><button class="btn" id="btn-logout">Log out</button></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td id="second-row">
                    <table>
                        <tr>
                            <td>
                                <div id="user-box" class="card-box">
                                    <table>
                                        <tr>
                                            <td align="center" id="bigavatar-td">
                                                <div id="UserAvaterBig" class="avatar">
                                                    <?php
                                                    echo strtoupper(substr($loggedUserName, 0, 2));
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" id="box-username-td">
                                                <?php
                                                echo $loggedUser;
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" id="box-time-td">
                                                <?php
                                                echo "Welcome back $loggedUserName";
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>

                            <td id="thread-td">
                                <div id="thread-box">
                                    <form method="post" action="thread.php">
                                        <table>
                                            <tr>
                                                <td id="qtitle-td" colspan="2">What are you stuck on?</td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" id="quiz-td"><input type="text" name="txtTitle"
                                                        class="txtQuestion"
                                                        placeholder="Write your question as a title"></td>
                                            </tr>
                                            <tr>
                                                <td colspan="2" id="quiz-td"><input type="text" name="txtContent"
                                                        class="txtQuestion" placeholder="Explain your question"></td>
                                            </tr>
                                            <tr>
                                                <td id="error-td">
                                                    <?php
                                                    $error = $_GET["error"] ?? "";
                                                    if ($error == "empty") {
                                                        echo "Please enter the fields!";
                                                    } else if ($error == "failed") {
                                                        echo "Failed to post the thread!";
                                                    }
                                                    ?>
                                                </td>
                                                <td align="right" id="post-td">
                                                    <button class="btn" id="btn-post-thread" type="submit">Post
                                                        thread
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </td>

                            <td id="help-td">
                                <div id="helpers-box" class="card-box">
                                    <table>
                                        <tr>
                                            <td id="helper-title-td" align="center">Top users this month</td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table>
                                                    <?php while ($rowTop = $rstTopUsers->fetch_assoc()) { ?>
                                                        <tr>
                                                            <td class="helper-row">
                                                                <div class="helper-avatar" id="helper1">
                                                                    <?php
                                                                    echo strtoupper(substr($rowTop["full_name"], 0, 2));
                                                                    ?>
                                                                </div>
                                                            </td>
                                                            <td class="helper-td">
                                                                <?php
                                                                echo $rowTop["username"];
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table>
                        <tr>
                            <td id="menu-box-td">
                                <div id="menu-box" class="card-box">
                                    <table>
                                        <tr>
                                            <td class="menu-td"><button class="btn-menu" id="btn-all-threads">All
                                                    threads</button></td>
                                        </tr>
                                        <tr>
                                            <td class="menu-td"><button class="btn-menu" id="btn-my-threads">My
                                                    threads</button></td>
                                        </tr>
                                    </table>
                                </div>
                            </td>

                            <td id="filter-td">
                                <table>
                                    <tr>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td><button class="btn-filters">Solved</button></td>
                                                    <td><button class="btn-filters">Active</button></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <table class="thread-list">
                                                <?php while ($row = $result->fetch_assoc()) { ?>
                                                    <tr>
                                                        <td class="card">
                                                            <div class="thread-card">
                                                                <table class="thread">
                                                                    <tr>
                                                                        <td class="thread-avatar-td">
                                                                            <div class="thread-avatar">
                                                                                <?php
                                                                                $id = $row["user_id"];
                                                                                $sqlName = "SELECT * FROM users WHERE user_id = $id";
                                                                                $rstUser = $con->query($sqlName);
                                                                                $nameRow = $rstUser->fetch_assoc();
                                                                                $name = $nameRow["full_name"];
                                                                                echo strtoupper(substr($name, 0, 2));
                                                                                ?>
                                                                            </div>
                                                                        </td>
                                                                        <td class="thread-username">
                                                                            <?php
                                                                            echo $nameRow["username"];
                                                                            ?>
                                                                        </td>
                                                                        <td class="thread-time">
                                                                            <?php
                                                                            echo date("Y-m-d", strtotime($row['created_at']));
                                                                            ?>
                                                                        </td>
                                                                        <td>
                                                                            <div class="thread-status">
                                                                                <?php
                                                                                echo $row["status"];
                                                                                ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="thread-question" colspan="4">
                                                                            <?php
                                                                            echo $row["title"];
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="thread-content" colspan="4">
                                                                            <?php
                                                                            echo $row["content"];
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="thread-btn-td"><button id="thread-like"
                                                                                class="thread-btn">👍</button></td>
                                                                        <td class="thread-btn-td" id="comment-btn-td">
                                                                            <button type="button" id="thread-comment"
                                                                                class="thread-btn comment-toggle">💭</button>
                                                                        </td>
                                                                        <td colspan="2" align="right" class="thread-btn-td">
                                                                            <form method="POST" action="report.php">
                                                                                <input type="hidden" name="type"
                                                                                    value="THREAD">
                                                                                <input type="hidden" name="id"
                                                                                    value="<?php echo $row["thread_id"]; ?>">
                                                                                <button type="submit"
                                                                                    class="report-btn">Report</button>
                                                                            </form>
                                                                        </td>
                                                                    </tr>
                                                                    <tr class="comments-row">
                                                                        <td colspan="4">
                                                                            <div class="comments">
                                                                                <h4>
                                                                                    <?php
                                                                                    $threadId = $row["thread_id"];
                                                                                    $comments = $con->query("SELECT * FROM comments WHERE thread_id='$threadId'");
                                                                                    echo $comments->num_rows;
                                                                                    ?>
                                                                                    answers
                                                                                </h4>

                                                                                <?php if ($comments->num_rows == 0) { ?>
                                                                                    <p class="no-comment">No answers yet. Be the
                                                                                        first to help!
                                                                                    </p>
                                                                                <?php } ?>

                                                                                <?php while ($c = $comments->fetch_assoc()) { ?>
                                                                                    <div class="comment" id="comment-box">
                                                                                        <div class="comment-head">
                                                                                            <?php
                                                                                            $commenterId = $c["user_id"];
                                                                                            $commentUserRst = $con->query("SELECT * FROM users WHERE user_id='$commenterId'");
                                                                                            $commentUser = $commentUserRst->fetch_assoc();
                                                                                            ?>
                                                                                            <table class="comment-head">
                                                                                                <tr>
                                                                                                    <td
                                                                                                        class="comment-avatar-td">
                                                                                                        <div
                                                                                                            class="comment-avatar">
                                                                                                            <?php echo strtoupper(substr($commentUser["full_name"], 0, 2)); ?>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                    <td class="comment-user">
                                                                                                        <?php echo htmlspecialchars($commentUser["username"]); ?>
                                                                                                    </td>
                                                                                                    <td class="comment-time">
                                                                                                        <?php echo date("Y-m-d", strtotime($c["created_at"])); ?>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            </table>
                                                                                        </div>
                                                                                        <p class="comment-text">
                                                                                            <?php echo nl2br(htmlspecialchars($c["body"])); ?>
                                                                                        </p>
                                                                                        <form method="POST" action="report.php">
                                                                                            <input type="hidden" name="type"
                                                                                                value="COMMENT">
                                                                                            <input type="hidden" name="id"
                                                                                                value="<?php echo $c["comment_id"]; ?>">
                                                                                            <button type="submit"
                                                                                                class="report-btn">Report</button>
                                                                                        </form>
                                                                                    </div>
                                                                                <?php } ?>

                                                                                <form action="add-comment.php"
                                                                                    method="post">
                                                                                    <input type="hidden" name="threadId"
                                                                                        value="
                                                                                    <?php echo $threadId; ?>">
                                                                                    <textarea name="commentContent"
                                                                                        maxlength="1000" required
                                                                                        placeholder="Write your answer."
                                                                                        id="txt-comment"></textarea>
                                                                                    <button type="submit" class="post-btn"
                                                                                        id="comment-send-btn">Post
                                                                                        answer</button>
                                                                                </form>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>

    </section>


    <script>
        document.querySelectorAll(".comment-toggle").forEach(function (btn) {
            btn.addEventListener("click", function () {
                btn.closest(".card").classList.toggle("open");
            });
        });
    </script>

    <?php if ($msg != "") { ?>
        <dialog id="msg-dialog">
            <p>
                <?php echo $msg; ?>
            </p>
            <button type="button" id="msg-ok" class="dialog-send">OK</button>
        </dialog>

        <script>
            var msgDialog = document.getElementById("msg-dialog");
            msgDialog.showModal();

            document.getElementById("msg-ok").addEventListener("click", function () {
                msgDialog.close();
            });

            setTimeout(function () { msgDialog.close(); }, 3000);

            history.replaceState(null, "", "user-dashboard.php");
        </script>
    <?php } ?>
</body>

</html>