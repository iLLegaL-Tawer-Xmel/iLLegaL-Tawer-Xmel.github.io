<?php

session_start();

//Подключаемся к базе данных
require $_SERVER['DOCUMENT_ROOT'].'/include/db.php';

//Подключаем скрипт проверки авторизации
require $_SERVER['DOCUMENT_ROOT'].'/include/check_auth.php';

//Подключаем скрипт проверки ошибок
include $_SERVER['DOCUMENT_ROOT'].'/include/error.php';
$error = new errors();

//Проверяем авторизован ли уже пользователь
if ($auth === true){
    header("Location: /lk/");
    die();
}

//Получаем данные из формы
$login = $db->real_escape_string($_POST['login']);

//Проверяем существование пользователя
$check_user = $db->query("SELECT `id` FROM `users` WHERE `login`='$login' || `email`='$login'")->num_rows;
if ($check_user !== 1){
    $error->new_error('danger','Ошибка восстановления пароля','Пользователь с данным логином или EMail не найден в системе');
    header("Location: /?p=reset_password");
    die();
}

//Получаем ID пользователя для восстановления пароля
$user_reset_id = $db->query("SELECT `id` FROM `users` WHERE `login`='$login' || `email`='$login'")->fetch_assoc()['id'];

//Генерируем код для восстановления
$code = bin2hex(random_bytes(25));

//Сохраняем код для восстановления
$db->query("UPDATE `users` SET `reset_code`='$code' WHERE `id`='$user_reset_id'");

//Получаем URL
$url = 'http://'.$_SERVER['HTTP_HOST'];

//Устаналиваем верную кодировку
header('Content-Type: text/html; charset=utf-8');

//Выводим ссылку
die('Ссылка для восстановления пароля сформирована: <a href="'.$url.'/lk/auth/reset_password_step2.php?key='.$code.'">'.$url.'/lk/auth/reset_password_step2.php?key='.$code.'</a>');