<?php
session_start();
require "functions.php";

$email = $_POST['email'];
$password = $_POST['password'];



if (getUserByEmail($email)) {
    setFlashMessage("danger", "Пользователь с таким email уже существует");
    redirectTo("page_register.php");
}
else{
    addUser($email, $password);
    setFlashMessage("success", "Пользователь успешно зарегистрирован");
    redirectTo("page_login.php");
}
