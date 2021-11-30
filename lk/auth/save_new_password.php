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
$key = $db->real_escape_string($_POST['key']);
$new_password = $db->real_escape_string($_POST['new_password']);
$confirm_password = $db->real_escape_string($_POST['confirm_password']);

//Устанавливаем кодировку
header('Content-Type: text/html; charset=utf-8');

//Проверяем наличие ключа переданного
if (empty($key)){
    die('Не передан секретный ключ для восстанволения пароля!');
}

//Ищем пользователя для восстановления пароля
$user_reset_id = $db->query("SELECT `id` FROM `users` WHERE `reset_code`='$key'")->fetch_assoc()['id'];

//Проверяем корректность ключа
if (empty($user_reset_id)){
    die('Неверный секретный ключ для восстановления пароля!');
}

//Получаем URL
$url = 'http://'.$_SERVER['HTTP_HOST'];

//Проверяем заполнение полей с паролем
if (empty($new_password) || empty($confirm_password)){
    $error->new_error('danger','Ошибка восстановления пароля!','Пожалуйста, заполните поля "Новый пароль" и "Подтвердите новый пароль"!');
    header("Location: /lk/auth/reset_password_step2.php?key=$key");
    die();
}

//Проверяем совпадения полей "Новый пароль" и "Подтверждение нового пароля"
if ($new_password !== $confirm_password){
    $error->new_error('danger','Ошибка восстановления пароля!','Новый пароль и подтверждение нового пароля - не совпадают!');
    header("Location: /lk/auth/reset_password_step2.php?key=$key");
    die();
}

//Проверяем соблюдение требований к паролю
$check_password= preg_match('/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%.-]{6,50}$/', $new_password) ? true : false;

if ($check_password == false) {
    $error->new_error('danger','Ошибка восстановления пароя!','Пароль не соответствует шаблону! Буквы - только латинские, минимум 6 символов');
    header("Location: /lk/auth/reset_password_step2.php?key=$key");
    die();
};

//Обновляем пароль
$db->query("UPDATE `users` SET `password`='$new_password',`reset_code`='' WHERE `id`='$user_reset_id'");

//Удаляем попытки авторизации
$date = date('Y-m-d');
$ip = $_SERVER['REMOTE_ADDR'];
$db->query("DELETE FROM `trying` WHERE `ip`='$ip' AND `date`='$date'");

//Перенаправляем на главную
$error->new_error('success','Пароль успешно изменён!','Пожалуйста, авторизуйтесь с новым паролем');
header("Location: /?p=auth");