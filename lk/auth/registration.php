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
$password = $db->real_escape_string($_POST['password']);
$confirm_password = $db->real_escape_string($_POST['confirm_password']);
$fio = $db->real_escape_string($_POST['fio']);
$email = $db->real_escape_string($_POST['email']);

//Проверяем заполненность всех полей
if (empty($login) || empty($password) || empty($confirm_password) || empty($fio) || empty($email) || empty($_FILES["avatar"]["name"])){
    $error->new_error('danger','Ошибка!','Пожалуйста, заполните все обязательные поля для регистрации!');
    header("Location: /?p=registration");
    die();
}

//Проверяем свободен ли Логин
if ($db->query("SELECT `id` FROM `users` WHERE `login`='$login'")->num_rows > 0){
    $error->new_error('danger','Ошибка!','Данный логин уже занят!');
    header("Location: /?p=registration");
    die();
}

//Проверяем существует ли уже пользователь с такой почтой
if ($db->query("SELECT `id` FROM `users` WHERE `email`='$email'")->num_rows > 0){
    $error->new_error('danger','Ошибка!','Пользователь с данной электронной почтой уже зарегистрирован в системе!');
    header("Location: /?p=registration");
    die();
}

//Проверяем соблюдение требований к паролю
$check_password= preg_match('/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%.-]{6,50}$/', $password) ? true : false;

if ($check_password == false) {
    $error = new errors();
    $error->new_error('danger','Ошибка!','Пароль не соответствует шаблону! Буквы - только латинские, минимум 6 символов');
    header("Location: /?p=registration");
    die();
};

//Получаем URL
$url = 'http://'.$_SERVER['HTTP_HOST'];

//Проверяем формат загружаемой аватарки
$file_type = $_FILES["avatar"]["type"];
if (!empty($file_type) && $file_type !== 'image/gif' && $file_type !== 'image/png' && $file_type !== 'image/jpeg' && $file_type !== 'image/JPEG' && $file_type !== 'image/PNG' && $file_type !== 'image/PNG' && $file_type !== 'image/GIF' && $file_type !== 'image/JPG' && $file_type !== 'image/jpg'){
    $error->new_error('danger','Ошибка регистрации!','Данный формат изображения не поддерживается!');
    header("Location: /?p=registration");
    die();
}

//Загружаем аватарку
$target_dir = "../../upload/";
$name_avatar = bin2hex(random_bytes(25));
$target_file_type = $target_dir . basename($_FILES["avatar"]["name"]);
$target_file = $target_dir . $name_avatar;
$imageFileType = strtolower(pathinfo($target_file_type, PATHINFO_EXTENSION));
$target_file = $target_dir . $name_avatar . '.' . $imageFileType;
$url_avatar = $url.'/upload/'.$name_avatar.'.'.$imageFileType;
$imageFileType = strtolower(pathinfo($target_file_type, PATHINFO_EXTENSION));
move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);

//Регистрируем пользователя
$db->query("INSERT INTO `users`(`login`, `email`, `password`, `fio`, `avatar`) VALUES ('$login','$email','$password','$fio','$url_avatar')");
$user_id = $db->insert_id;

//Записываем пользователя в сессию
$_SESSION['user_id'] = $user_id;

//Генерируем случайную строку для токена
$token = bin2hex(random_bytes(25));

//Сохраняем токен
$db->query("INSERT INTO `tokens`(`user_id`, `token`) VALUES ('$user_id','$token')");

//Удаляем попытки авторизации
$date = date('Y-m-d');
$ip = $_SERVER['REMOTE_ADDR'];
$db->query("DELETE FROM `trying` WHERE `ip`='$ip' AND `date`='$date'");

//Сохраняем куку сесии
setcookie('token',$token,time()+60*60*24*365,'/');

//Перенаправляем в закрытый раздел сайта
header("Location: /lk/");