<?php

require '../vendor/autoload.php';

//use App\QueryBuilder;
//$db = new QueryBuilder();

//if($_SERVER['REQUEST_URI'] == '/home') {
//    require '../app/controllers/homepage.php';
//}
//
//exit();

// Create new Plates instance
$templates = new League\Plates\Engine('../app/views');
// Render a template
echo $templates->render('about', ['title' => 'Jonathan']);