<?php

session_start();
require_once "functions.php";

$selectedAvatar = $_FILES['avatar'];
$editingUserId = $_POST['id'];

uploadAvatar($selectedAvatar, $editingUserId);


setFlashMessage("success", "Аватар обновлен");
redirectTo("media.php?id=" . urlencode($editingUserId) . "&nocache=" . time());
