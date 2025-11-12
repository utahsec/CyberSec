<?php
if (session_id() == "") session_start();
$login = $_SESSION["login"] ?? false;
?>

<!doctype html>

<head>
    <meta charset="utf-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="/static/main.css">
</head>

<body>
<div class="page">
<nav>
    <a href="/">bacebook</a>
    <div class="nav-links">
        <?php
        if ($login) {
            echo "<a href=\"/profile\">profile</a>\n";
            echo "<a href=\"/logout\">logout</a>\n";
        }
        else {
            echo "<a href=\"/login\">login</a>\n";
        }
        ?>
    </div>
</nav>
