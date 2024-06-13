<?php
session_start();
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo '<h2>Oops...</h2><span class="aside"><i>Please fill in all fields.</i></span>';
        header("refresh:3;url=index.html");
        exit;
    }

    $query = "SELECT * FROM login WHERE UserId = ?";
    if ($stmt = $db_conn->prepare($query)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['PW'])) {
            $_SESSION["username"] = $username;
            session_write_close();
            header('location: main.php');
            exit;
        } else {
            echo '<h2>Oops...</h2><span class="aside"><i>Incorrect username or password.</i></span>';
            header("refresh:3;url=index.html");
            exit;
        }

        $stmt->close();
    } else {
        echo '<p>Error preparing statement!<br>' . htmlspecialchars($db_conn->error) . '</p>';
        header("refresh:3;url=index.html");
        exit;
    }

    $db_conn->close();
} else {
    echo '<h2>Oops...</h2><span class="aside"><i>Invalid request.</i></span>';
    header("refresh:3;url=index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Login Error | PreBook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" type="image/png" href="img/logo.png">
</head>
<body>
<nav>
    <div class="logo">
        <img src="img/logo.png">
        <h1>PreBook</h1>
        <span><i>Cheap, Reliable, Instant</i></span>
    </div>
</nav>
<main>
    <div class="bar">
        <h2>Oops...</h2>
        <span class="aside"><i>Incorrect username or password.</i></span>
    </div>
    <i class="fas fa-exclamation-triangle full-icon"></i>
</main>
</body>
</html>
