<?php
session_start();
require_once "functions.php";
$avatar = $_FILES['avatar'];

$user_data = $_POST;
//var_dump($_POST);

if (getUserByEmail($user_data['email'])){
    setFlashMessage("danger", "Пользователь с такой почтой уже существует");
    redirectTo("create_user.php");
}

try {
    $added_user_id = addUser($user_data['email'], $user_data['password']);

    edit(
        $added_user_id,
        $user_data['username'],
        $user_data['job_name'],
        $user_data['phone'],
        $user_data['address']
    );

    uploadAvatar($avatar, $added_user_id);
    setStatus($added_user_id, $user_data['status']);
    addSocialLinks($user_data['vk'], $user_data['telegram'], $user_data['instagram'], $added_user_id);

    setFlashMessage("success", "Пользователь успешно добавлен.");
    redirectTo("users.php");

} catch (PDOException $e) {
    echo "Ошибка базы данных: " . $e->getMessage();

} catch (Exception $e) {
    echo "Что-то пошло не так: " . $e->getMessage();
}