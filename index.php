<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadline Login</title>

    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@200..800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="assets/CSS/index.css">
</head>

<body>
    <section id="login-section">
        <table id="table-main">
            <tr>
                <td colspan="2">
                    <div id="threadline-logo">
                        Threadline
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <h2 id="headline">Stuck? Ask <br>
                        the class</h2>
                </td>
                <td rowspan="2" id="form-line">
                    <form action="check-credentials.php" method="post">
                        <div class="login-container">
                            <table id="table-form">
                                <tr>
                                    <td id="form-title-td">
                                        <h3 id="form-title">Welcome back</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="field-name">Username</td>
                                </tr>
                                <tr>
                                    <td class="input-field-td"><input type="text" name="txt-username"
                                            placeholder="your_username" class="input-field"></td>
                                </tr>
                                <tr>
                                    <td class="field-name">Password</td>
                                </tr>
                                <tr>
                                    <td class="input-field-td"><input type="password" name="txt-password"
                                            placeholder="Your_password" class="input-field"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="error-line">
                                        <?php
                                            $error = $_GET["error"] ?? "";
                                            if ($error == "empty") {
                                                echo "Please enter the fields";
                                            }else if ($error == "invalid") {
                                                echo "Invalid username or password!";
                                            }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" id="btn-row"><input type="submit" value="Sign in"
                                            id="btn-signIn"></td>
                                </tr>
                                <tr>
                                    <td id="link-line">New here? <a href="/sign-in-page.html">Create an account</a></td>
                                </tr>
                                <tr>
                                    <td id="admin-line">Admins sign in here too.</td>
                                </tr>
                            </table>
                        </div>
                    </form>
                </td>
            </tr>
            <tr>
                <td>
                    <div id="quiz-container">
                        <div id="qcard1" class="qcard">
                            <table class="qtable">
                                <tr>
                                    <td class="avatar-line">
                                        <div class="avatar" id="q-avatar">AV</div>
                                    </td>
                                    <td class="user-line">nimali_d</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Why does my PDO query return <br>
                                        nothing?</td>
                                </tr>
                            </table>
                        </div>
                        <div id="qcard2" class="qcard">
                            <table class="qtable">
                                <tr>
                                    <td class="avatar-line">
                                        <div class="avatar" id="a-avatar">KR</div>
                                    </td>
                                    <td class="user-line">kasun_r</td>
                                </tr>
                                <tr>
                                    <td colspan="2">Bind :email before you call execute().</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </section>
</body>

</html>