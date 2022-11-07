<?php

/**
 * Автор: Павел Косинец
 *
 * Дата реализации: 06.11.2022 15:00
 */



include 'people_class.php';
include 'people_worklist.php';

$connection = new mysqli('localhost', 'root', 'root');

if($connection->connect_error){
    die('Ошибка: ' . $connection->connect_error);
}

echo 'Подключение успешно установлено';

$connection->close();


$id = 3;
$name = 'Tom';
$surname = 'Drow';
$birthday = array(12, 4, 1971); 
$sex = true; 
$city = 'Denver';


$person = new People_DB($id, $name, $surname, $birthday, $sex, $city);
$person-> save();
$person-> delete(2);
$person_age = People_DB::age($person);
$person_sex = People_DB::string_sex($person);
$new_object = $person->formatting($person, true, true);


$worklist = new WorkList(3, 2);
$arr = $worklist->get_array_of_people();
$worklist->delete_people();










