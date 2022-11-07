<?php

/**
 * Автор: Павел Косинец
 *
 * Дата реализации: 06.11.2022 15:00
 */

/**
 Класс для работы с базой данных пользователя

 Конструктор класса либо находит пользователя по id и присваивает найденные значения в свои поля класса (передан 1 аргумент, или (передано 6 арументов) создает экземпляр класса с полученными данными при вызове)
 save - метод для добавления в БД нового пользователя на основе значений полей класса
 delete - метод для удаления пользователя по полученному id 
 bd_connect - метод для подключения к БД
 age - метод вычисления возраста по дате рождения 
 string_sex - метод для преобразования boolean значения пола в буквенный 
 formatting - метод возвращает экземпляр класса с измененным значением пола (string_sex), возраста (age)
six_validation, one_validation - валидация данных
 */

class People_DB
{ 
	private $id, $name, $surname, $birthday, $sex, $city_of_birth;

	function __construct($id, $name=null, $surname=null, $birthday=null, $sex=null, $city_of_birth=null)
	{

		$count_parameters = func_num_args();

		switch($count_parameters)
		{
			case 1: 
				if($this->one_validation($id)) {
					$connection = $this->bd_connect();
					$sql = "SELECT * FROM first_task WHERE id = $id";
					$result = $connection->query($sql);
					if($result->num_rows == 0) {
						die ('Ошибка: пользователь с таким id не найден');
					}
					elseif($result = $connection->query($sql)) {
	    				foreach($result as $row) {
	        				$this->id = $row['id'];
	        				$this->name = $row['name'];
	        				$this->surname = $row['surname'];
	        				$this->birthday = $row['date_of_birth'];
	        				$this->sex = $row['sex'];
	        				$this->city_of_birth = $row['city'];
	    				}
	    			}
					$connection->close();
					break;
				}
				else {
					die ('Ошибка: id введен неверно');
				}
			case 6:
				if($this->six_validation($id, $name, $surname, $birthday, $sex, $city_of_birth) ) {
					$this->id = $id; 
					$this->name = $name; 
					$this->surname = $surname; 
					$this->birthday = date('Y-m-d', mktime(0, 0, 0, $birthday[0], $birthday[1], $birthday[2]));
					$this->sex = $sex; 
					$this->city_of_birth = $city_of_birth; 	
					break;
				}
				else {
					die ('Ошибка: заданные поля некорректны');
				}		
			default:
				die('Ошибка: неверное количество параметров');
		}
	}


	public function save()
	{
		$connection = $this->bd_connect();

		$sql = "INSERT INTO first_task (id, name, surname, date_of_birth, sex, city) VALUES 
		($this->id, 
		'$this->name',
		'$this->surname',
		'$this->birthday',
	     $this->sex,
		'$this->city_of_birth')";

		if($connection->query($sql)) {
    		echo 'Данные успешно добавлены';
		} 
		else {
    		echo 'Ошибка: ' . $connection->error;
		}

		$connection->close();
	}

	public function delete($id)
	{
		$connection = $this->bd_connect();

		$sql = "DELETE FROM first_task WHERE id=$id";

		if($connection->query($sql)) {
    		echo 'Данные успешно удалены';
		} 
		else {
    		echo 'Ошибка: ' . $connection->error;
		}

		$connection->close();
	}

	public static function age($person)
	{
		$age = date('Ymd') - date('Ymd', strtotime($person->birthday));

		return substr($age, 0, 2);
	}

	public static function string_sex($person)
	{ 
		$string = ' ';
		if($person->sex) { 
			$string = 'man';
		}
		else { 
			$string = 'woman';
		}
		return $string;
	}

	public function formatting($person, $age = false, $string_sex = false)
	{
		if($age) {
			$this->birthday = People_DB::age($person);
		}
		if ($string_sex) {
			$this->sex = People_DB::string_sex($person);
		}
		$object_new = (object) array(
			'id'=>$this->id, 
			'name'=>$this->name, 
			'surname'=>$this->surname, 
			'birthday'=>$this->birthday, 
			'sex'=>$this->sex, 
			'city_of_birth'=>$this->city_of_birth,
		);
		return $object_new;
	}

	public function bd_connect()
	{ 
		$connection = new mysqli('localhost', 'root', 'root', 'test_bd');

		if($connection->connect_error) {
    		die('Ошибка: ' . $connection->connect_error);
		}
		
		return $connection;
	}

	public function six_validation($id, $name, $surname, $birthday, $sex, $city_of_birth)
	{
		if(isset($id, $name, $surname, $birthday, $sex, $city_of_birth)) {
			if(gettype($id) != 'integer' 
				|| gettype($name) != 'string'
				|| gettype($surname) != 'string'
				|| gettype($city_of_birth) != 'string'
				|| gettype($sex) != 'boolean' 
				|| gettype($birthday) != 'array'
				|| count($birthday) != 3 
				|| ctype_alpha($name) == false 
				|| ctype_alpha($surname) == false) {
				return false;
			}
			$true_date = checkdate($birthday[0], $birthday[1], $birthday[2]);
			if ($true_date == false) {
				return false;
			}
				else {
				return true;
			}
		}
	}

	public function one_validation($id)
	{
		if (isset($id) && gettype($id) == 'integer') {
			return true;
		}
		else {
			return false; 
		}
	}
} 


