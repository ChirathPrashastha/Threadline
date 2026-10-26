<?php
    session_start();

    if(!isset($_SESSION["USERNAME"])){
        header("Location: index.php");
        exit();
    }

    include("db/connection.php");

    $loggedUser = $con->real_escape_string($_SESSION["USERNAME"]);
    

    $userCountSql = "SELECT COUNT(user_id) AS user_count FROM users WHERE status='ACTIVE';";
    $resultUserCount = $con->query($userCountSql);
    $userCountRow = $resultUserCount->fetch_assoc();
    $userCount = $userCountRow["user_count"];

    $threadCountSql = "SELECT COUNT(thread_id) AS thread_count FROM threads WHERE status != 'REMOVED';";
    $resultThreadCount = $con->query($threadCountSql);
    $threadCountRow = $resultThreadCount->fetch_assoc();
    $threadCount = $threadCountRow["thread_count"];

    $reportCountSql = "SELECT COUNT(report_id) AS report_count FROM reports WHERE status='OPEN';";
    $resultReportCount = $con->query($reportCountSql);
    $reportCountRow = $resultReportCount->fetch_assoc();
    $reportCount = $reportCountRow["report_count"];

    $bannedCountSql = "SELECT COUNT(user_id) AS banned_count FROM users WHERE status='BANNED';";
    $resultBannedCount = $con->query($bannedCountSql);
    $bannedCountRow = $resultBannedCount->fetch_assoc();
    $bannedCount = $bannedCountRow["banned_count"];


    $view = $_GET["view"] ?? "overview";
    if ($view != "overview" && $view != "reports" && $view != "users" && $view != "threads") {
        $view = "overview";
    }


    $msg = "";
    $msgKey = $_GET["msg"] ?? "";
    if ($msgKey == "dismissed") {
        $msg = "Report dismissed.";
    } else if ($msgKey == "thread_removed") {
        $msg = "Thread removed.";
    } else if ($msgKey == "comment_removed") {
        $msg = "Comment removed.";
    } else if ($msgKey == "banned") {
        $msg = "User banned.";
    } else if ($msgKey == "failed") {
        $msg = "Something went wrong. Please try again.";
    }

    if ($view == "overview" || $view == "reports") {
        $reportWhere = "";
        if ($view == "overview") {
            $reportWhere = "WHERE r.status = 'OPEN'";
        }

        $reportsSql = "SELECT r.report_id, r.thread_id, r.comment_id, r.status, r.created_at,
                              IF(r.reason = '', 'not given', r.reason) AS reason_text,
                              LEFT(COALESCE(t.title, c.body), 100) AS reported_text,
                              rep.username AS reporter_name,
                              tu.user_id AS target_id,
                              tu.username AS target_name,
                              tu.role AS target_role,
                              tu.status AS target_status
                       FROM reports r
                       JOIN users rep ON rep.user_id = r.reporter_id
                       LEFT JOIN threads t ON t.thread_id = r.thread_id
                       LEFT JOIN comments c ON c.comment_id = r.comment_id
                       LEFT JOIN users tu ON tu.user_id = COALESCE(r.reported_user, t.user_id, c.user_id)
                       $reportWhere
                       ORDER BY r.created_at DESC;";
        $reportsResult = $con->query($reportsSql);
    }

    if ($view == "users") {
        $usersResult = $con->query("SELECT user_id, full_name, email, username, role, status FROM users;");
    }

    if ($view == "threads") {
        $threadsResult = $con->query("SELECT t.thread_id, t.title, t.content, t.status, t.created_at,
                                             u.username, u.full_name
                                      FROM threads t
                                      JOIN users u ON u.user_id = t.user_id
                                      ORDER BY t.created_at DESC;");
        $openThread = (int) ($_GET["open"] ?? 0);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadline Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@200..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="assets/CSS/admin-dashboard.css">
</head>

<body>
    <section id="dashboard-section">
        <table id="dash-main">
            <tr>
                <td colspan="2" id="topbar-td">
                    <table id="topbar">
                        <tr>
                            <td class="top-cell">
                                <div id="threadline-logo">
                                    Threadline
                                </div>
                            </td>
                            <td class="top-cell">
                                <div id="admin-badge">Admin</div>
                            </td>
                            <td></td>
                            <td class="top-cell">
                                <div class="avatar" id="admin-avatar">
                                    <?php
                                    echo strtoupper(substr($_SESSION["NAME"], 0, 2));
                                    ?>
                                </div>
                            </td>
                            <td class="top-cell" id="admin-name">
                                <?php
                                echo htmlspecialchars($_SESSION["USERNAME"]);
                                ?>
                            </td>
                            <td class="top-cell">
                                <a href="logout.php" class="btn" id="btn-logout">Log out</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td id="sidebar-td">
                    <div id="sidebar">
                        <table id="nav-table">
                            <tr>
                                <td class="nav-item <?php if ($view == "overview") { echo "nav-active"; } ?>">
                                    <a href="admin-dashboard.php?view=overview">Overview</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="nav-item <?php if ($view == "reports") { echo "nav-active"; } ?>">
                                    <a href="admin-dashboard.php?view=reports">Reports <span
                                            id="nav-count"><?php echo $reportCount; ?></span></a>
                                </td>
                            </tr>
                            <tr>
                                <td class="nav-item <?php if ($view == "users") { echo "nav-active"; } ?>">
                                    <a href="admin-dashboard.php?view=users">Users</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="nav-item <?php if ($view == "threads") { echo "nav-active"; } ?>">
                                    <a href="admin-dashboard.php?view=threads">Threads</a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>

                <td id="content-td">

                    <?php if ($msg != "") { ?>
                        <div id="flash" <?php if ($msgKey == "failed") { echo 'class="flash-error"'; } ?>>
                            <?php echo $msg; ?>
                        </div>
                    <?php } ?>


                    <?php if ($view == "overview") { ?>

                        <table id="stats-table">
                            <tr>
                                <td class="stat-td">
                                    <div class="stat-card" id="stat-users">
                                        <div class="stat-number"><?php echo $userCount ?></div>
                                        <div class="stat-label">Registered users</div>
                                    </div>
                                </td>
                                <td class="stat-td">
                                    <div class="stat-card" id="stat-threads">
                                        <div class="stat-number"><?php echo $threadCount ?></div>
                                        <div class="stat-label">Active threads</div>
                                    </div>
                                </td>
                                <td class="stat-td">
                                    <div class="stat-card" id="stat-reports">
                                        <div class="stat-number"><?php echo $reportCount ?></div>
                                        <div class="stat-label">Open reports</div>
                                    </div>
                                </td>
                                <td class="stat-td" id="stat-td-last">
                                    <div class="stat-card" id="stat-banned">
                                        <div class="stat-number"><?php echo $bannedCount ?></div>
                                        <div class="stat-label">Banned users</div>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <div class="panel">
                            <h3 class="panel-title">Reports waiting for review</h3>

                            <?php if ($reportsResult->num_rows == 0) { ?>
                                <p class="empty-note">No reports are waiting for review.</p>
                            <?php } ?>

                            <?php while ($r = $reportsResult->fetch_assoc()) { ?>
                                <div class="report-card">
                                    <table class="report-table">
                                        <tr>
                                            <td>
                                                <?php if ($r["thread_id"]) { ?>
                                                    <span class="tag tag-thread">Thread</span>
                                                <?php } else { ?>
                                                    <span class="tag tag-comment">Comment</span>
                                                <?php } ?>
                                                <span class="report-time">
                                                    <?php echo date("Y-m-d H:i", strtotime($r["created_at"])); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="report-text">"<?php echo htmlspecialchars($r["reported_text"]); ?>"</td>
                                        </tr>
                                        <tr>
                                            <td class="report-info">
                                                Reported user: <?php echo htmlspecialchars($r["target_name"]); ?>.
                                                Reported by: <?php echo htmlspecialchars($r["reporter_name"]); ?>.
                                                Reason: <?php echo htmlspecialchars($r["reason_text"]); ?>.
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="report-buttons">
                                                <?php include("report-buttons.php"); ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            <?php } ?>
                        </div>

                    <?php } ?>


                    <?php if ($view == "reports") { ?>

                        <div class="panel">
                            <h3 class="panel-title">Reports</h3>

                            <?php if ($reportsResult->num_rows == 0) { ?>
                                <p class="empty-note">The reports table is empty.</p>
                            <?php } else { ?>
                                <table class="data-table">
                                    <colgroup>
                                        <col width="5%">
                                        <col width="11%">
                                        <col width="17%">
                                        <col width="11%">
                                        <col width="10%">
                                        <col width="9%">
                                        <col width="9%">
                                        <col width="11%">
                                        <col width="17%">
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Reported content</th>
                                            <th>Reported user</th>
                                            <th>Reported by</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($r = $reportsResult->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $r["report_id"]; ?></td>
                                                <td>
                                                    <?php if ($r["thread_id"]) { ?>
                                                        <span class="tag tag-thread">Thread</span>
                                                    <?php } else { ?>
                                                        <span class="tag tag-comment">Comment</span>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($r["reported_text"]); ?></td>
                                                <td><?php echo htmlspecialchars($r["target_name"]); ?></td>
                                                <td><?php echo htmlspecialchars($r["reporter_name"]); ?></td>
                                                <td><?php echo htmlspecialchars($r["reason_text"]); ?></td>
                                                <td>
                                                    <span class="tag tag-<?php echo strtolower($r["status"]); ?>"><?php echo $r["status"]; ?></span>
                                                </td>
                                                <td><?php echo date("Y-m-d H:i", strtotime($r["created_at"])); ?></td>
                                                <td>
                                                    <?php include("report-buttons.php"); ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>

                    <?php } ?>


                    <?php if ($view == "users") { ?>

                        <div class="panel">
                            <table class="panel-head">
                                <tr>
                                    <td>
                                        <h3 class="panel-title">Users</h3>
                                    </td>
                                    <td align="right">
                                        <input type="text" class="panel-search" id="search-users"
                                            placeholder="Search users">
                                    </td>
                                </tr>
                            </table>

                            <table class="data-table" id="users-table">
                                <colgroup>
                                    <col width="7%">
                                    <col width="24%">
                                    <col width="30%">
                                    <col width="17%">
                                    <col width="10%">
                                    <col width="12%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Full name</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($u = $usersResult->fetch_assoc()) { ?>
                                        <tr>
                                            <td><?php echo $u["user_id"]; ?></td>
                                            <td><?php echo htmlspecialchars($u["full_name"]); ?></td>
                                            <td><?php echo htmlspecialchars($u["email"]); ?></td>
                                            <td><?php echo htmlspecialchars($u["username"]); ?></td>
                                            <td>
                                                <span class="tag tag-<?php echo strtolower($u["role"]); ?>"><?php echo $u["role"]; ?></span>
                                            </td>
                                            <td>
                                                <span class="tag tag-<?php echo strtolower($u["status"]); ?>"><?php echo $u["status"]; ?></span>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>

                    <?php } ?>


                    <?php if ($view == "threads") { ?>

                        <table class="panel-head">
                            <tr>
                                <td>
                                    <h3 class="panel-title">All threads</h3>
                                </td>
                                <td align="right">
                                    <input type="text" class="panel-search" id="search-threads"
                                        placeholder="Search threads">
                                </td>
                            </tr>
                        </table>

                        <table class="thread-list">
                            <?php while ($row = $threadsResult->fetch_assoc()) { ?>
                                <?php
                                $threadId = $row["thread_id"];
                                $comments = $con->query("SELECT c.comment_id, c.body, c.created_at, u.username, u.full_name
                                                         FROM comments c
                                                         JOIN users u ON u.user_id = c.user_id
                                                         WHERE c.thread_id = $threadId
                                                         ORDER BY c.created_at;");
                                ?>
                                <tr>
                                    <td class="card <?php if ($openThread == $threadId) { echo "open"; } ?>"
                                        data-search="<?php echo htmlspecialchars(strtolower($row["title"] . " " . $row["content"] . " " . $row["username"] . " " . $row["full_name"])); ?>">
                                        <div class="thread-card">
                                            <table class="thread">
                                                <tr>
                                                    <td class="thread-avatar-td">
                                                        <div class="thread-avatar">
                                                            <?php
                                                            echo strtoupper(substr($row["full_name"], 0, 2));
                                                            ?>
                                                        </div>
                                                    </td>
                                                    <td class="thread-username">
                                                        <?php
                                                        echo htmlspecialchars($row["username"]);
                                                        ?>
                                                    </td>
                                                    <td class="thread-time">
                                                        <?php
                                                        echo date("Y-m-d", strtotime($row["created_at"]));
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="thread-status status-<?php echo $row["status"]; ?>">
                                                            <?php
                                                            echo $row["status"];
                                                            ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="thread-question" colspan="4">
                                                        <?php
                                                        echo htmlspecialchars($row["title"]);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="thread-content" colspan="4">
                                                        <?php
                                                        echo htmlspecialchars($row["content"]);
                                                        ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="thread-btn-td" colspan="4">
                                                        <button type="button" class="thread-btn comment-toggle">💭
                                                            <?php echo $comments->num_rows; ?></button>
                                                    </td>
                                                </tr>
                                                <tr class="comments-row">
                                                    <td colspan="4">
                                                        <div class="comments">
                                                            <h4><?php echo $comments->num_rows; ?> answers</h4>

                                                            <?php if ($comments->num_rows == 0) { ?>
                                                                <p class="no-comment">No answers on this thread.</p>
                                                            <?php } ?>

                                                            <?php while ($c = $comments->fetch_assoc()) { ?>
                                                                <div class="comment">
                                                                    <table class="comment-head">
                                                                        <tr>
                                                                            <td class="comment-avatar-td">
                                                                                <div class="comment-avatar">
                                                                                    <?php echo strtoupper(substr($c["full_name"], 0, 2)); ?>
                                                                                </div>
                                                                            </td>
                                                                            <td class="comment-user">
                                                                                <?php echo htmlspecialchars($c["username"]); ?>
                                                                            </td>
                                                                            <td class="comment-time">
                                                                                <?php echo date("Y-m-d", strtotime($c["created_at"])); ?>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                    <p class="comment-text">
                                                                        <?php echo nl2br(htmlspecialchars($c["body"])); ?>
                                                                    </p>
                                                                    <form method="post" action="admin-actions.php" class="action-form">
                                                                        <input type="hidden" name="action" value="remove_comment">
                                                                        <input type="hidden" name="view" value="threads">
                                                                        <input type="hidden" name="comment_id" value="<?php echo $c["comment_id"]; ?>">
                                                                        <input type="hidden" name="open" value="<?php echo $threadId; ?>">
                                                                        <button type="submit" class="btn btn-small btn-pink"
                                                                            onclick="return confirm('Delete this comment?')">Delete comment</button>
                                                                    </form>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>

                    <?php } ?>

                </td>
            </tr>

        </table>
    </section>

    <?php if ($view == "users") { ?>
        <script>

            var searchUsers = document.getElementById("search-users");
            searchUsers.addEventListener("input", function () {
                var text = searchUsers.value.toLowerCase();
                var rows = document.querySelectorAll("#users-table tbody tr");
                rows.forEach(function (row) {
                    row.style.display = row.textContent.toLowerCase().includes(text) ? "" : "none";
                });
            });
        </script>
    <?php } ?>

    <?php if ($view == "threads") { ?>
        <script>

            var searchThreads = document.getElementById("search-threads");
            searchThreads.addEventListener("input", function () {
                var text = searchThreads.value.toLowerCase();
                var cards = document.querySelectorAll(".thread-list .card");
                cards.forEach(function (card) {
                    var show = card.getAttribute("data-search").includes(text);
                    card.parentElement.style.display = show ? "" : "none";
                });
            });


            var buttons = document.querySelectorAll(".comment-toggle");
            buttons.forEach(function (btn) {
                btn.addEventListener("click", function () {
                    btn.closest(".card").classList.toggle("open");
                });
            });
        </script>
    <?php } ?>
</body>

</html>
