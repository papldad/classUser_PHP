<?php

/**
 * Класс 'ListUsers' расширенный от класса 'User'
 * Класс реализован для работы со списками пользователей в БД.
 *
 * Конструктор класса ведет поиск 'id' пользователей по всем полям БД и назначает массив с найденными 'id'.
 * Метод 'getList' возвращает массив с 'id' пользователей полученного в конструкторе.
 * Метод 'deleteUsers' удаляет пользователей из БД в соответствии с массивом, полученным в конструкторе.
 **/

class ListUsers extends User
{
    public $listId;

    public function __construct($value)
    {
        if ($this->db()) {
            // Если подключение к БД успешно, то...
            // Ведем поиск по всем полям.
            $sql = "
                SELECT * FROM " . TABLE_USER . " 
                WHERE 
                id LIKE '%{$value}%' 
                OR first_name LIKE '%{$value}%' 
                OR last_name LIKE '%{$value}%' 
                OR birthday LIKE '%{$value}%' 
                OR gender LIKE '%{$value}%' 
                OR city LIKE '%{$value}%'
                ";
            $query = mysqli_query($this->db_connection, $sql);

            // Назначаем массив с найденными 'id'.
            while ($row = mysqli_fetch_array($query)) {
                $this->listId[] = $row['id'];
            }
        }
    }

    public function getList()
    {
        return $this->listId;
    }

    public function deleteUsers()
    {
        if ($this->listId == null) {
            echo "List is clear.";
        } else {
            // Удаляем пользователей по 'id' с помощью метода 'delete' из родительского класса.
            foreach ($this->listId as $value) {
                $this->id = $value;
                $this->delete();
            }

            // "Деструктуризация" массива с 'id'.
            $this->listId = null;
        }
    }
}
