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

//Получаем дату и IP
$date = date('Y-m-d');
$ip = $_SERVER['REMOTE_ADDR'];

//Получаем переданный логин и пароль
$login = $db->real_escape_string($_POST['login']);
$password = $db->real_escape_string($_POST['password']);

//Проверчем наличие попыток авторизации
$try_num = $db->query("SELECT `id` FROM `trying` WHERE `date`='$date' AND `ip`='$ip'")->num_rows;
if ($try_num > 20){
    $error->new_error('danger','Ошибка авторизации','Вы ввели неверный логин или пароль уже 20 раз! Попыток не осталось. Пожалуйста, попробуйте авторизоваться завтра или восстановите пароль');
    header("Location: /?p=auth");
    die();
}

//Выполняем поиск пользователя в базе данных
$user = $db->query("SELECT `id` FROM `users` WHERE (`login`='$login' || `email`='$login') AND `password`='$password'")->fetch_assoc()['id'];

//Проверяем правильность логина и пароля
if (empty($user)){

    //Считаем оставшиеся попытки на отправку
    $try = 20-$try_num;

    $error->new_error('danger','Ошибка авторизации','Неверный логин или пароль! Осталось попыток авторизации на сегодня: '.$try);

    //Добавляем попытку автризации
    $db->query("INSERT INTO `trying`(`date`, `ip`) VALUES ('$date','$ip')");

    header("Location: /?p=auth");
    die();
}

//Генерируем случайную строку для токена
$token = bin2hex(random_bytes(25));

//Сохраняем токен
$db->query("INSERT INTO `tokens`(`user_id`, `token`) VALUES ('$user','$token')");

//Сохраняем куку сесии
setcookie('token',$token,time()+60*60*24*365,'/');

//Удаляем попытки авторизации
$db->query("DELETE FROM `trying` WHERE `ip`='$ip' AND `date`='$date'");

//Сохраняем сессию
$_SESSION['user_id'] = $user;

//Перенаправляем в закрытый раздел сайта
header("Location: /lk/");