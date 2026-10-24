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
                            <td id="search-td">
                                <input type="text" placeholder="Search users or threads" id="search-field">
                            </td>
                            <td class="top-cell">
                                <div class="avatar" id="admin-avatar">
                                    <?php 
                                    session_start();
                                    $value = $_SESSION['NAME'];
                                    $result = strtoupper(substr($value,0,2));
                                    echo $result;
                                    ?>
                                </div>
                            </td>
                            <td class="top-cell" id="admin-name"></td>
                            <td class="top-cell">
                                <button class="btn" id="btn-logout">Log out</button>
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
                                <td class="nav-item" id="nav-active">Overview</td>
                            </tr>
                            <tr>
                                <td class="nav-item">Reports <span id="nav-count">4</span></td>
                            </tr>
                            <tr>
                                <td class="nav-item">Users</td>
                            </tr>
                            <tr>
                                <td class="nav-item">Threads</td>
                            </tr>
                        </table>
                    </div>
                </td>

                <td id="content-td">
                    <table id="stats-table">
                        <tr>
                            <td class="stat-td">
                                <div class="stat-card" id="stat-users">
                                    <div class="stat-number">128</div>
                                    <div class="stat-label">Registered users</div>
                                </div>
                            </td>
                            <td class="stat-td">
                                <div class="stat-card" id="stat-threads">
                                    <div class="stat-number">342</div>
                                    <div class="stat-label">Active threads</div>
                                </div>
                            </td>
                            <td class="stat-td">
                                <div class="stat-card" id="stat-reports">
                                    <div class="stat-number">4</div>
                                    <div class="stat-label">Open reports</div>
                                </div>
                            </td>
                            <td class="stat-td" id="stat-td-last">
                                <div class="stat-card" id="stat-banned">
                                    <div class="stat-number">3</div>
                                    <div class="stat-label">Banned users</div>
                                </div>
                            </td>
                        </tr>
                    </table>

                    
                    <table id="panels-table">
                        <tr>
                            <td id="reports-td">
                                <div class="panel">
                                    <h3 class="panel-title">Reports waiting for review</h3>

                                    <div class="report-card">
                                        <table class="report-table">
                                            <tr>
                                                <td>
                                                    <span class="tag tag-thread">Thread</span>
                                                    <span class="report-time">12 min ago</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="report-text">"Free marks for everyone, click here"</td>
                                            </tr>
                                            <tr>
                                                <td class="report-info">Reported user: tharindu_m. Reported by: kasun_r. Reason: spam.</td>
                                            </tr>
                                            <tr>
                                                <td class="report-buttons">
                                                    <button class="btn btn-white">View</button>
                                                    <button class="btn btn-lime">Remove thread</button>
                                                    <button class="btn btn-pink">Ban user</button>
                                                    <button class="btn btn-white">Dismiss</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="report-card">
                                        <table class="report-table">
                                            <tr>
                                                <td>
                                                    <span class="tag tag-comment">Comment</span>
                                                    <span class="report-time">1 hour ago</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="report-text">"You clearly did not read the question."</td>
                                            </tr>
                                            <tr>
                                                <td class="report-info">Reported user: ishara_w. Reported by: nimali_d. Reason: abusive language.</td>
                                            </tr>
                                            <tr>
                                                <td class="report-buttons">
                                                    <button class="btn btn-white">View</button>
                                                    <button class="btn btn-lime">Remove comment</button>
                                                    <button class="btn btn-pink">Ban user</button>
                                                    <button class="btn btn-white">Dismiss</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </td>

                            <td id="chart-td">
                                <div class="panel">
                                    <h3 class="panel-title">New threads this week</h3>
                                    <table id="chart-table">
                                        <tr>
                                            <td class="bar-td"><div class="bar" id="bar-mon"></div></td>
                                            <td class="bar-td"><div class="bar" id="bar-tue"></div></td>
                                            <td class="bar-td"><div class="bar" id="bar-wed"></div></td>
                                            <td class="bar-td"><div class="bar" id="bar-thu"></div></td>
                                            <td class="bar-td"><div class="bar" id="bar-fri"></div></td>
                                            <td class="bar-td"><div class="bar" id="bar-sat"></div></td>
                                            <td class="bar-td"><div class="bar" id="bar-sun"></div></td>
                                        </tr>
                                        <tr>
                                            <td class="day-label">Mon</td>
                                            <td class="day-label">Tue</td>
                                            <td class="day-label">Wed</td>
                                            <td class="day-label">Thu</td>
                                            <td class="day-label">Fri</td>
                                            <td class="day-label">Sat</td>
                                            <td class="day-label">Sun</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </section>
</body>

</html>
