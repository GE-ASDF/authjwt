<?php

namespace app\database;
use PDO;
use PDOException;

class Connection
{
    public static function connect(){
        try{
            $db = new PDO("mysql:dbname=cronograma_estudos;host=localhost", "root", "");
            return $db;
        }catch(PDOException $e){
            var_dump($e->getMessage());
        }
    }
}