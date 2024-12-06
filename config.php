<?php
function getPDO()
{
    $host = 'localhost';
    $db = 'cinema';
    $user = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
    $pdo = new PDO($dsn, $user, $password);
    return $pdo;
}