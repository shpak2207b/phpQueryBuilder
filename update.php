<?php
include "functions.php";
$db = include "database/start.php";

$id = $_GET['id'];
$db->update('posts', $_POST, $id);

header('Location: /index.php');