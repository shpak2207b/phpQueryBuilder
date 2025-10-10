<?php
session_start();
require_once "functions.php";

$editingUserId = $_POST['id'];
$username = $_POST['username'];
$jobName = $_POST['job_name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

edit($editingUserId, $username, $jobName, $phone, $address);
setFlashMessage("success", "Профиль успешно обновлен");
redirectTo("users.php");