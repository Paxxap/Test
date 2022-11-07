<?php 

/**
 * Автор: Павел Косинец
 *
 * Дата реализации: 06.11.2022 15:00
 */




if(class_exists('People_DB') == false) {
	die('Ошибка: отсутствует класс People_DB');
}


/**
 Класс для работы со списками людей из базы данных

 Конструктор класса ищет необходимые id из подключенной базы данных
 get_array_of_people - метод для получения всей информации о пользователях, id которых были получены при создании экземпляра класса 
 delete_people - метод для удаления всех пользователей с полученным id при создании экземпляра класса 
 bd_connect - метод для подключения к БД
 */


class WorkList
{
	private $people_array = array();

	function __construct($index = null, $sign = null)
	{
		$connection = $this->bd_connect();
		if($index === null || $sign === null) {
			$sql = "SELECT id FROM first_task";
		}
		else {
		 	switch($sign) {
		 		case 0:
		 			$sql = "SELECT id FROM first_task WHERE id < $index";
		 		case 1: 
		 			$sql = "SELECT id FROM first_task WHERE id > $index";
		 		case 2: 
		 			$sql = "SELECT id FROM first_task WHERE id != $index";	
		 	}
		}
		$result = $connection->query($sql);
		foreach($result as $row)
	    {
	    	$this->people_array[] = $row['id'];
	    }
	}

	public function get_array_of_people()
	{
		$array_of_people = array();
		$connection = $this->bd_connect();
		foreach($this->people_array as $key => $value)
		{
			$sql = "SELECT * FROM first_task WHERE id = $value";
			$result = $connection->query($sql);
			if($result->num_rows == 0) {
				echo('Ошибка: пользователь с таким id не найден');
				continue;
			}
			elseif($result = $connection->query($sql)) {
	    		foreach($result as $row)
	    		{
	        		$array_of_people[$value]['id'] = $row['id'];
	    		}
	    	}
		}
		$object_array = array();
		foreach($array_of_people as $value)
		{
			$object_array[] = new People_DB(intval($value['id']));
		}
		$connection->close();
		return $object_array;
	}

	public function delete_people()
	{
		$array_of_delete = array(); 
		$connection = $this->bd_connect();
		foreach($this->people_array as $value)
		{
			$new_person_delete = new People_DB(intval($value));
			$new_person_delete->delete($value);
		}
		$connection->close();
		echo('Пользователи удалены');
	}

	public function bd_connect()
	{ 
		$connection = new mysqli('localhost', 'root', 'root', 'test_bd');

		if($connection->connect_error) {
    		die('Ошибка: ' . $connection->connect_error);
		}
		
		return $connection;
	}
}




