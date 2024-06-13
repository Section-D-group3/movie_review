<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Create an Account | PreBook</title>
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
        <?php
        require('db.php');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['desiredusername'];
            $password = $_POST['desiredpassword'];

            // Input validation
            if (empty($username) || empty($password)) {
                echo '<h2>Oops...</h2><span class="aside"><i>Please fill in all fields.</i></span>';
                header("refresh:3;url=createaccount.html");
                exit;
            }

            // Check if the username already exists
            $query = "SELECT * FROM login WHERE UserId = ?";
            if ($stmt = $db_conn->prepare($query)) {
                $stmt->bind_param('s', $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<h2>Oops...</h2><span class="aside"><i>Account already exists, use another username.</i></span>';
                    header("refresh:3;url=createaccount.html");
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Insert the new user
                    $insert_query = "INSERT INTO login (UserId, PW) VALUES (?, ?)";
                    if ($insert_stmt = $db_conn->prepare($insert_query)) {
                        $insert_stmt->bind_param('ss', $username, $hashed_password);

                        if ($insert_stmt->execute()) {
                            echo '<h2>Yaaay!</h2><span class="aside"><i>You\'ve registered. You\'ll be redirected to login, where you can use these details.</i></span>';
                            header("refresh:3;url=index.html");
                        } else {
                            echo '<p>Error inserting data!<br>' . htmlspecialchars($insert_stmt->error) . '</p>';
                        }
                        $insert_stmt->close();
                    } else {
                        echo '<p>Error preparing statement!<br>' . htmlspecialchars($db_conn->error) . '</p>';
                    }
                }
                $stmt->close();
            } else {
                echo '<p>Error preparing statement!<br>' . htmlspecialchars($db_conn->error) . '</p>';
            }

            $db_conn->close();
        } else {
            echo '<h2>Oops...</h2><span class="aside"><i>Invalid request.</i></span>';
            header("refresh:3;url=createaccount.html");
        }
        ?>
    </div>
    <i class="fas fa-exclamation-triangle full-icon"></i>
</main>
</body>
</html>
