<?php $title = "home"; require "partials/header.php"; ?>

<main>
    <div class="cards">
        <?php
        $users = new SQLite3("users.db");
        $res = $users->query("select username, status from users");
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $username = $row["username"];
            $status = $row["status"];
            echo "<div class=\"card\">\n";
            echo "<div><strong>$username</strong></div>\n";
            echo "<div>$status</div>\n";
            echo "</div>\n";
        }
        ?>
    </div>
</main>

<?php require "partials/footer.php"; ?>
