<?php
session_start();

$login = $_SESSION["login"] ?? false;
if ($login) {
    header("Location: /profile");
    exit();
}

$error = "";

function log_in($username) {
    $_SESSION["login"] = true;
    $_SESSION["username"] = $username;
    header("Location: /profile");
    exit();
}

// Login form handler
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST parameters
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    // Validate username chars
    // Letters, digits, dash, period
    $is_match = preg_match("/^[\w\.-]+$/", $username);
    if (!$is_match) {
        $error = "invalid username";
    }
    else {
        // Check if username already exists
        $db = new SQLite3("users.db");
        $stmt = $db->prepare('select count(*) from users where username=:username');
        $stmt->bindValue(':username', $username);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_NUM);
        $user_count = $row[0];

        if ($user_count === 0) {
            // If user doesn't exist, register
            $pw_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare('insert into users values (:username, :pw_hash, :status)');
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':pw_hash', $pw_hash);
            $stmt->bindValue(':status', "my first status!");
            $stmt->execute();
            log_in($username);
        }
        else {
            // If user exists, check password and log in
            $stmt = $db->prepare('select password_hash from users where username=:username');
            $stmt->bindValue(':username', $username);
            $result = $stmt->execute();
            $row = $result->fetchArray(SQLITE3_NUM);
            $pw_hash = $row[0];

            $is_pw_correct = password_verify($password, $pw_hash);
            if ($is_pw_correct) {
                log_in($username);
            }
            else {
                $error = "incorrect password";
            }
        }
    }
}
?>

<?php $title = "log in"; require "partials/header.php"; ?>

<main>
    <h1>log in or register</h1>
    <form action="login" method="post">
        <div>
            <label for="username">username: </label>
            <input name="username" id="username" type="text">
        </div>
        <div>
            <label for="password">password: </label>
            <input name="password" id="password" type="password">
        </div>
        <button type="submit">submit</button>
        <?php
        if ($error) echo "<p class=\"error\">$error</p>\n";
        ?>
    </form>
</main>

<?php require "partials/footer.php"; ?>
