<?php

/**
 * Класс 'User'
 * Класс реализован для работы с БД пользователей.
 *
 * Конструктор класса либо создает пользователя в БД с заданной информацией, либо берет информацию из БД по 'id'.
 * Метод 'db' подключается к БД.
 * Метод 'getUser' возвращает данные о пользователе, если в конструкторе задано поле 'id'.
 * Метод 'insert' сохраняет поля экземпляра класса в БД, метод имеет проверку на наличие такого же 'id' в БД.
 * Метод 'delete' удаляет пользователя из БД в соответствии с 'id' объекта, метод имеет проверку на наличие такого же 'id' в БД.
 * Метод 'hasId' проверяет наличие 'id' объекта в БД и возвращает 'true' или 'false'.
 * Статический метод 'getAge' принимает дату рождения и возвращает количество лет.
 * Статический метод 'getGenderText' принимает числа из двоичной системы и возвращает текст 'Man' или 'Woman'.
 * Метод 'formatingData' форматирует данные пользователя и возвращает новый экземпляр 'stdClass' со всеми полями.
 **/

// Константы для подключения к БД.
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "test_db");
define('TABLE_USER', 'user');

class User
{
    protected $db_connection;

    public $id;
    public $first_name;
    public $last_name;
    public $birthday;
    public $gender;
    public $city;

    public function __construct($data)
    {
        if ($this->db()) {
            // Если подключение к БД успешно, то...
            // Проверка: назначен ли 'id'. И соответсвенное назначение переменных экземпляра.
            if (isset($data['id'])) {
                $data = $this->getUser($data['id']);
                $this->id = $data['id'];
            }
            $this->first_name = $data['first_name'];
            $this->last_name = $data['last_name'];
            $this->birthday = $data['birthday'];
            $this->gender = $data['gender'];
            $this->city = $data['city'];
        }
    }

    protected function db()
    {
        $this->db_connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Проверка: успешное ли подключение к БД.
        if ($this->db_connection) {
            return true;
        }

        exit;
    }

    protected function getUser($id)
    {
        $sql = "SELECT * FROM " . TABLE_USER . " WHERE id='{$id}'";
        $result = mysqli_query($this->db_connection, $sql);
        $data = mysqli_fetch_array($result);

        return $data;
    }

    public function insert()
    {
        if ($this->hasId()) {
            $sql =  "
                    INSERT INTO " . TABLE_USER . " 
                    (id, first_name, last_name, birthday, gender, city) 
                    VALUES 
                    (NULL, '{$this->first_name}', '{$this->last_name}', '{$this->birthday}', '{$this->gender}', '{$this->city}')
                    ";

            mysqli_query($this->db_connection, $sql);
        } else {
            echo "DataBase: this id {$this->id} used.";
        }
    }

    public function delete()
    {
        if (!($this->hasId())) {
            $sql = "DELETE FROM " . TABLE_USER . " WHERE id = '{$this->id}'";

            mysqli_query($this->db_connection, $sql);
            echo "DataBase: id {$this->id} deleted.";
        } else {
            echo "DataBase: this id {$this->id} is undefined.";
        }
    }

    protected function hasId()
    {
        $sql = "SELECT * FROM " . TABLE_USER . " WHERE id='{$this->id}'";
        $query = mysqli_query($this->db_connection, $sql);

        if (mysqli_num_rows($query) == 0) {
            return true;
        }

        return false;
    }

    static function getAge($birthday)
    {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
            $age--;
        }
        return $age;
    }

    static function getGenderText($gender)
    {
        if ($gender == 0) {
            return "Man";
        } else if ($gender == 1) {
            return "Woman";
        }
    }

    public function formatingData()
    {
        // Создаем новый экземпляр 'stdClass'.
        $newData = new stdClass;

        // "Копируем" в новый экземпляр 'stdClass' поля изначального класса.
        foreach ($this as $key => $value) {
            $newData->$key = $value;
        }

        // Добавляем новые поля в новый экземпляр 'stdClass' и в форматируем их.
        $newData->age = self::getAge($this->birthday);
        $newData->gender_text = self::getGenderText($this->gender);
        // Возвращаем новый экземпляр 'stdClass'.
        return $newData;
    }
}
