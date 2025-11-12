<?php
require '../vendor/autoload.php';

use Delight\Auth\Auth;
use League\Plates\Engine;
use DI\ContainerBuilder;
use App\Controllers\Class2;
use Faker\Factory;
use Aura\SqlQuery\QueryFactory;
use JasonGrimes\Paginator;
use App\exceptions\AccountIsBlockedException;
use App\exceptions\NotEnoughMoneyException;
use Tamtamchik\SimpleFlash\Flash;
use function Tamtamchik\SimpleFlash\flash;
if (!session_id()) @session_start();

$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
        Engine::class => function () {
            return new Engine('../app/views');
        },
        \PDO::class => function () {
            $driver = "mysql";
            $host = "MySQL-8.0";
            $database_name = "app2";
            $username = "root";
            $password = "";

            return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
        },
        Auth::class => function ($container) {
            return new Delight\Auth\Auth($container->get('PDO'));
        }
    ]
);

$container = $containerBuilder->build();
$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', ['App\Controllers\HomeController', 'index']);
    $r->addRoute('GET', '/about', ['App\Controllers\HomeController', 'about']);
    $r->addRoute('GET', '/verification', ['App\Controllers\HomeController', 'emailVerification']);
    $r->addRoute('GET', '/login', ['App\Controllers\HomeController', 'login']);
    $r->addRoute('GET', '/user/{id:\d+}', ['App\Controllers\HomeController', 'emailVerification']);
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        $container->call($routeInfo[1], $routeInfo[2]);
        $controller = new $handler[0];
        call_user_func([$controller, $handler[1]], $vars);

        break;
}


//$pdo = new \PDO("mysql:host=MySQL-8.0;dbname=app2;", "root", "");
//$queryFactory = new QueryFactory('mysql');

//$faker = Factory::create();
//
//// Insert one row at a time in a loop
//for ($i = 0; $i < 30; $i++) {
//    $insert = $queryFactory->newInsert();
//    $insert->into('posts')
//        ->cols([
//            'title' => $faker->words(3, true),
//            'content' => $faker->text
//        ]);
//
//    $sth = $pdo->prepare($insert->getStatement());
//    $sth->execute($insert->getBindValues());
//}
//
//echo "30 posts inserted successfully!";

//$select = $queryFactory->newSelect();
//$select
//    ->cols(['*'])
//    ->from('posts')
//    ->setPaging(3)
//    ->page($_GET['page'] ?? 1);
//
//$sth = $pdo->prepare($select->getStatement());
////var_dump(($sth));
//$sth->execute($select->getBindValues());
//
//$totalItems = $sth->fetchAll(PDO::FETCH_ASSOC);
//
//$itemsPerPage = 3;
//$currentPage = $_GET['page'] ?? 1;
//$urlPattern = '?page=(:num)';
//
//$paginator = new \JasonGrimes\Paginator(60, $itemsPerPage, $currentPage, $urlPattern);
//foreach ($totalItems as $item) {
//    echo $item['id'] . PHP_EOL . $item['title'] . '<br>';
//}
//?>
<!--<html>-->
<!--<head>-->
<!--    <!-- The default, built-in template supports the Twitter Bootstrap pagination styles. -->-->
<!--    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">-->
<!--</head>-->
<!--<body>-->
<!---->
<?php
//// Example of rendering the pagination control with the built-in template.
//// See below for information about using other templates or custom rendering.
//
//echo $paginator;
//?>
<!---->
<!--</body>-->
<!--</html>-->
<!--<!--////}-->-->
<!---->
<?php


?>