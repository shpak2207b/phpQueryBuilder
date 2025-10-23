<?php
include __DIR__ . "/../functions.php";

$routes = [
    "/" => 'homepage.php',
    "/about" =>  'about.php'
];
$route = $_SERVER['REQUEST_URI'];
if(array_key_exists($route, $routes)) {
    include($routes[$route]); exit();
}
else {
    dd(404);
}

?>
