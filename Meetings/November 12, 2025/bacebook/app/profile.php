<?php
session_start();

// Ensure that user is logged in
$login = $_SESSION["login"] ?? false;
if (!$login) {
    http_response_code(401);
    require "401.php";
    exit();
}

$username = $_SESSION["username"];
$error = "";
$file_dir = "static/uploads/" . $username . "/";
if (!file_exists($file_dir)) mkdir($file_dir, recursive: true);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Status form handler
    if (array_key_exists("status", $_POST)) {
        $status = $_POST["status"];
        $db = new SQLite3("users.db");
        $stmt = $db->prepare('update users set status = :status where username = :username');
        $stmt->bindValue(':username', $username);
        $stmt->bindValue(':status', $status);
        $stmt->execute();
    }

    // File upload handler
    if (array_key_exists("file", $_FILES)) {
        $file = $_FILES["file"];

        // Files must be 4 KB or less
        if ($file["size"] > 1024 * 4) {
            $error = "file size too large";
        }
        else {
            // Only one file, so remove all other files
            $filenames = scandir($file_dir);
            for ($i = 2; $i < count($filenames); $i++) {
                unlink($file_dir . $filenames[$i]);
            }
            // basename to prevent directory traversal attacks
            $filename = basename($file["name"]);
            move_uploaded_file($file["tmp_name"], $file_dir . $filename);
        }
    }
}
?>

<?php $title = "profile"; require "partials/header.php"; ?>

<main>
    <h1><?= $username ?></h1>

    <form action="profile" method="post">
        <?php
        $db = new SQLite3("users.db");
        $stmt = $db->prepare('select status from users where username=:username');
        $stmt->bindValue(':username', $username);
        $result = $stmt->execute();
        $row = $result->fetchArray(SQLITE3_NUM);
        $status = $row[0];
        ?>
        <label for="status">my status:</label>
        <input name="status" id="status" type="text" class="status-input" value="<?= $status ?>">
        <button type="submit">submit</button>
    </form>

    <p><strong>free file hosting! only one file! friendly files only please :)</strong></p>

    <?php
    $filenames = scandir($file_dir);
    if (count($filenames) > 2) {
        $filepath = $file_dir . $filenames[2];
        echo "<p><a href=\"$filepath\">here is your file :)</a></p>\n";
    }
    else {
        echo "<p>you don't have a file...</p>\n";
    }
    ?>

    <form action="profile" method="post" enctype="multipart/form-data">
        <input name="file" id="file" type="file">
        <button type="submit">upload</button>
    </form>

    <?php
    if ($error) echo "<p class=\"error\">$error</p>";
    ?>
</main>

<?php require "partials/footer.php"; ?>
