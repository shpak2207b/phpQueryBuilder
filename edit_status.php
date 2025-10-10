<?php
session_start();
require_once "functions.php";

$selectedStatus = $_POST['status'];
$editingUserId = $_POST['id'];

setStatus($editingUserId, $selectedStatus);


setFlashMessage("success", "Статус обновлен");
redirectTo("status.php?id=" . urlencode($editingUserId));