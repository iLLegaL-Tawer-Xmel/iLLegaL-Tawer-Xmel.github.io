<?php

session_start();

//Подключаемся к базе данных
require $_SERVER['DOCUMENT_ROOT'].'/include/db.php';

//Подключаем скрипт проверки авторизации
require $_SERVER['DOCUMENT_ROOT'].'/include/check_auth.php';

//Подключаем скрипт проверки ошибок
include $_SERVER['DOCUMENT_ROOT'].'/include/error.php';
$error = new errors();

//Проверяем наличие авторизации
if ($auth !== true){
    header("Location: /");
    die();
}

//Получаем данные из формы
$fio = $db->real_escape_string($_POST['fio']);
$email = $db->real_escape_string($_POST['email']);
$old_password = $db->real_escape_string($_POST['old_password']);
$new_password = $db->real_escape_string($_POST['new_password']);
$confirm_password = $db->real_escape_string($_POST['confirm_password']);

//Проверяем заполненность всех полей
if (empty($fio) || empty($email)){
    $error->new_error('danger','Ошибка сохранения!','Заполните обязательные поля! (ФИО и EMail)');
    header("Location: /lk/profile/");
    die();
}

//Проверяем необходимость изменения пароля
if (!empty($old_password) || !empty($new_password) || !empty($confirm_password)){

    //Проверяем заполнение всех полей с паролем
    if (empty($old_password) || empty($new_password) || empty($confirm_password)){
        $error->new_error('danger','Ошибка сохранения!','Для изменения пароля необходимо заполнить все поля с паролем (Старый пароль, новый пароль и подтверждение нового пароля)!');
        header("Location: /lk/profile/");
        die();
    }

    //Проверяем правильность старого пароля
    if($old_password !== $db->query("SELECT `password` FROM `users` WHERE `id`='$user_id'")->fetch_assoc()['password']){
        $error->new_error('danger','Ошибка сохранения!','Неверный старый пароль!');
        header("Location: /lk/profile/");
        die();
    }

    //Проверяем совпадения полей "Новый пароль" и "Подтверждение нового пароля"
    if ($new_password !== $confirm_password){
        $error->new_error('danger','Ошибка сохранения!','Новый пароль и подтверждение нового пароля - не совпадают!');
        header("Location: /lk/profile/");
        die();
    }

    //Проверяем совпадение нового пароля со старым
    if ($new_password == $old_password){
        $error->new_error('danger','Ошибка сохранения!','Новый пароль не может совпадать со старым!');
        header("Location: /lk/profile/");
        die();
    }

    //Проверяем соблюдение требований к паролю
    $check_password= preg_match('/^(?=.*[A-Za-z])[0-9A-Za-z!@#$%.-]{6,50}$/', $new_password) ? true : false;

    if ($check_password == false) {
        $error->new_error('danger','Ошибка!','Пароль не соответствует шаблону! Буквы - только латинские, минимум 6 символов');
        header("Location: /lk/profile/");
        die();
    };

    $dop = ",`password`='$new_password'";
}

//Получаем URL
$url = 'http://'.$_SERVER['HTTP_HOST'];

//Получаем текущую аватарку пользователя
$url_avatar = $db->query("SELECT `avatar` FROM `users` WHERE `id`='$user_id'")->fetch_assoc()['avatar'];

if (!empty($_FILES["avatar"]["type"])) {

    //Проверяем формат загружаемой аватарки
    $file_type = $_FILES["avatar"]["type"];
    if (!empty($file_type) && $file_type !== 'image/gif' && $file_type !== 'image/png' && $file_type !== 'image/jpeg' && $file_type !== 'image/JPEG' && $file_type !== 'image/PNG' && $file_type !== 'image/PNG' && $file_type !== 'image/GIF' && $file_type !== 'image/JPG' && $file_type !== 'image/jpg') {
        $error->new_error('danger', 'Ошибка сохранения!', 'Данный формат изображения не поддерживается!');
        header("Location: /lk/profile/");
        die();
    }

    //Загружаем аватарку
    $target_dir = "../../upload/";
    $name_avatar = bin2hex(random_bytes(25));
    $target_file_type = $target_dir . basename($_FILES["avatar"]["name"]);
    $target_file = $target_dir . $name_avatar;
    $imageFileType = strtolower(pathinfo($target_file_type, PATHINFO_EXTENSION));
    $target_file = $target_dir . $name_avatar . '.' . $imageFileType;
    $url_avatar = $url . '/upload/' . $name_avatar . '.' . $imageFileType;
    $imageFileType = strtolower(pathinfo($target_file_type, PATHINFO_EXTENSION));
    move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file);

}

//Обновляем данные по пользователю
$db->query("UPDATE `users` SET `email`='$email',`fio`='$fio',`avatar`='$url_avatar'$dop WHERE `id`='$user_id'");

//Перенаправляем
$error->new_error('success','Успешно сохранено!','Данные обновлены!');
header("Location: /lk/profile/");