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
    $statement->execute(["email" => $email, "password" => $password]);
}

function setFlashMessage($key, $message) {
    $_SESSION[$key] = $message;
}

function displayFlashMessage($key) {
    if(isset($_SESSION[$key])) {
       echo '<div class="alert alert-' . $key .  ' text-dark" role="alert">' . $_SESSION[$key] . '</div>';
    }
}

function redirectTo($path) : void {
    header("Location: /" . $path);
    exit();
}