<?php

function getUserByEmail($email) : array | bool {
    $host = "MySQL-8.0";
    $dbname = "users";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", "root", "");
    $sql = "SELECT * FROM users WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

function addUser($email, $password) : void {
    $host = "MySQL-8.0";
    $dbname = "users";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", "root", "");
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email, "password" => password_hash($password, PASSWORD_DEFAULT)]);
}

function setFlashMessage($key, $message) {
    $_SESSION[$key] = $message;
}

function displayFlashMessage($key) : void {
    if(isset($_SESSION[$key])) {
       echo '<div class="alert alert-' . $key .  ' text-dark" role="alert">' . $_SESSION[$key] . '</div>';
    }
}

function redirectTo($path) : void {
    header("Location: /" . $path);
    exit();
}

function login($email, $password) : bool
{
    $user = getUserByEmail($email);
    if(!$user || !password_verify($password, $user['password'])) {
        setFlashMessage("danger" , "Неверный логин или пароль");
        redirectTo("page_login.php");
        return false;
    }
    else {
        $_SESSION['user'] = getUserByEmail($email);
        redirectTo("users.php");
        return true;
    }
}

function getCurrentUser()
{
    return $_SESSION['user'];
}

function isAdmin()
{
    if (getCurrentUser()['role'] === "admin"){
        return true;
    }
    else return false;
}

function getAllUsers()
{
    $host = "MySQL-8.0";
    $dbname = "users";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", "root", "");
    $sql = "SELECT * FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}