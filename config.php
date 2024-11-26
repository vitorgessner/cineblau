<?php
function getPDO()
{
    $host = 'localhost';
    $db = 'cinema';
    $user = 'root';
    $password = 'VGGvgg2003****';

    $dsn = "mysql:host=$host;dbname=$db;charset=UTF8";
    $pdo = new PDO($dsn, $user, $password);
    return $pdo;
}