<?php
$routes = [
    "/" => "home.php",
    "/home" => "home.php",
    "/login" => "login.php",
    "/profile" => "profile.php",
    "/logout" => "logout.php"
];

$uri = parse_url($_SERVER["REQUEST_URI"]);
$path = $uri["path"];

if (array_key_exists($path, $routes)) {
    require $routes[$path];
}
else {
    http_response_code(404);
    require "404.php";
    die();
}
?>
