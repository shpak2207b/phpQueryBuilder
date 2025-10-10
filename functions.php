<?php

//ВЗАИМОДЕЙСТВИЕ С ПОЛЬЗОВАТЕЛЕМ И БД
function connectDB()
{
    $host = "MySQL-8.0";
    $dbname = "users";
    return new PDO("mysql:host=$host;dbname=$dbname", "root", "");
}


function getUserByEmail($email) : array | bool {
    $pdo = connectDB();
    $sql = "SELECT * FROM users WHERE email = :email";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

function getUserById($id) : array | bool
{
    $pdo = connectDB();
    $sql = "SELECT * FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->execute(["id" => $id]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);
    return $user;
}

function addUser($email, $password) : int {
    $pdo = connectDB();
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $statement = $pdo->prepare($sql);
    $statement->execute(["email" => $email, "password" => password_hash($password, PASSWORD_DEFAULT)]);
    return (int)$pdo->lastInsertId();
}

function edit($id, $username, $job_name, $phone, $address) : void
{
    $pdo = connectDB();
    $sql = "UPDATE users SET username = :username, job_name = :job_name, phone = :phone, address = :address WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'username' => $username,
        'job_name' => $job_name,
        'phone' => $phone,
        'address' => $address,
        'id' => $id
    ]);
}

function editCredentials($userId, $email, $password)
{
    $pdo = connectDB();
    $sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email, "password" => password_hash($password, PASSWORD_DEFAULT), 'id' => $userId]);
}

function setStatus($id, $status)
{
    $pdo = connectDB();
    $sql = "UPDATE users SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'id' => $id,
        'status' => $status
    ]);
}

function uploadAvatar($avatar, $userId)
{
    if ($avatar['error'] !== UPLOAD_ERR_OK) {
        die("Ошибка загрузки аватарки!");
    }

    $allowed = ['image/jpeg', 'image/png'];
    if (!in_array($avatar['type'], $allowed)) {
        setFlashMessage("danger", "Аватарка должна быть в формате jpg/png");
        redirectTo("create_user.php");
        exit();
    }

    $uploadDir = "img/demo/avatars/";
    $newName = uniqid() . "-" . basename($avatar['name']);
    $target = $uploadDir . $newName;


    if (move_uploaded_file($avatar['tmp_name'], $target)) {
        $pdo = connectDB();
        $sql = "UPDATE USERS SET avatar_path = :avatar WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['avatar' => $newName, 'id' => $userId]);
        setFlashMessage("success", "Аватарка успешно загружена");

    } else {
        setFlashMessage("danger", "Ошибка загрузки аватара");
    }
}


function addSocialLinks($vk, $telegram, $instagram, $userId)
{
    $pdo = connectDB();
    $sql = "UPDATE USERS SET vk = :vk, telegram = :telegram, instagram = :instagram WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['vk' => $vk, 'telegram' => $telegram, 'instagram' => $instagram, 'id' => $userId]);

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

function isAuthor($loggedUserId, $editUserId) : bool
{
    if ($loggedUserId !== (int)$editUserId) {
        return false;
    }
    else return true;
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

//ВЗАИМОДЕЙСТВИЕ С СЕССИЕЙ


function setFlashMessage($key, $message) {
    $_SESSION[$key] = $message;
}

function displayFlashMessage($key) : void {
    if(isset($_SESSION[$key])) {
       echo '<div class="alert alert-' . $key .  ' text-dark" role="alert">' . $_SESSION[$key] . '</div>';
       unset($_SESSION[$key]);
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

