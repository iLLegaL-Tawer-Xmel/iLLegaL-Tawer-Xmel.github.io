<?php

//Выполняем подключение к БД
$db = mysqli_connect('localhost','nikita2585_test','5AcRlqo&','nikita2585_test');

//Выводим сообщение об ошибке в случае неудачной авторизации
if ($db != 1){
    die('Неудачная попытка соединения с базой данных!');
};