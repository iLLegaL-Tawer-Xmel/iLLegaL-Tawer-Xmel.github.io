<?php

session_start();

//Подключаемся к базе данных
require $_SERVER['DOCUMENT_ROOT'].'/include/db.php';

//Подключаем скрипт проверки авторизации
require $_SERVER['DOCUMENT_ROOT'].'/include/check_auth.php';

//Подключаем скрипт проверки ошибок
include $_SERVER['DOCUMENT_ROOT'].'/include/error.php';
$error = new errors();

//Удаляем куку с токеном
setcookie('token','',time()+1,'/');

//Удаляем данные по сессии
$_SESSION['user_id'] = '';

//Перенаправляем на главную
header("Location: /");