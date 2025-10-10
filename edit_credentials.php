<?php
session_start();
require_once "functions.php";

$credentials = $_POST;
$editingUserId = $credentials['id'];

if(getUserByEmail($credentials['email'])) {
    if (getUserByEmail($credentials['email'])['id'] !== getCurrentUser()['id']) {
        setFlashMessage("danger", "Текущая почта занята");
        redirectTo("security.php?id=" . urlencode($editingUserId));
    }
}

editCredentials($editingUserId, $credentials['email'], $credentials['password']);
setFlashMessage("success", "Входные данные успешно обновлены");
redirectTo("users.php");