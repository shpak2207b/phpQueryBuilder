<?php
session_start();
require_once "functions.php";

$deletingUserId = $_GET['id'];

$currentUser = getCurrentUser();
if (!$currentUser) {
    setFlashMessage("danger", "Необходимо авторизоваться в системе!");
    redirectTo("page_login.php");

}
if (!isAdmin() && !isAuthor($currentUser['id'], $deletingUserId)) {
    setFlashMessage("danger", "Вы не можете редактировать чужую карточку");
    redirectTo("users.php");
}

if (isset($deletingUserId)) {
    deleteUser($deletingUserId);
    if ((int)$deletingUserId === (int)$currentUser['id']) {
        redirectTo("page_login.php");
        session_destroy();
    }
    setFlashMessage("success", "Пользователь удален");
    redirectTo("users.php");
}
