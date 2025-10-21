<?php
include __DIR__ . "/../functions.php";

$routes = [
    "/home" => __DIR__ . '/../homepage.php',
    "/about" => __DIR__ . '/../about.php'
];
$route = $_SERVER['REQUEST_URI'];
if(array_key_exists($route, $routes)) {
    include($routes[$route]); exit();
}
else {
    dd(404);
}

?>
