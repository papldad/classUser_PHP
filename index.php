<?php

/**
 * Автор: Павел Лычиц
 *
 * Дата реализации: 08.01.2022 16:00
 *
 * Дата изменения: 10.11.2022 12:00
 *
 * Утилита для работы с базой данных
 **/

// Подключение классов.
require("./classses/User.php");
require("./classses/ListUsers.php");

// Проверка на наличие класса 'User'. 
if (
    class_exists('ListUsers')
    && !(class_exists('User'))
) {
    // Выводим ошибку, что класс 'User' не объявлен.
    throw new Exception("Class 'User' undefined.");
}
