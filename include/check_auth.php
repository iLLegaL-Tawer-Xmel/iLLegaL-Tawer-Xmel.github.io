<?php

/*
 * Скрипт реализован для проверки авторизации пользователя
 */

session_start();

//Подключаемся к базе данных
require $_SERVER['DOCUMENT_ROOT'].'/include/db.php';

//Подключаем скрипт проверки ошибок
include $_SERVER['DOCUMENT_ROOT'].'/include/error.php';
$error = new errors();

//Получаем данные из сессии
$user_id = $_SESSION['user_id'];

//Устанавливаем стандартную переменную (пользователь не авторизован)
$auth = false;

//В случае отсуствия записи в сессии - ищем по куке
$token_cookie = $_COOKIE['token'];

//Ищем токен в базе данных
$check_token = $db->query("SELECT `user_id` FROM `tokens` WHERE `token`='$token_cookie'")->fetch_assoc()['user_id'];

//Записываем ID пользователя в сессию
if (!empty($check_token)){
    $user_id = $check_token;
    $_SESSION['user_id'] = $check_token;
}

//Проверям наличие пользователя в БД
if ($db->query("SELECT `id` FROM `users` WHERE `id`='$user_id'")->num_rows === 1){
    $auth = true;
}