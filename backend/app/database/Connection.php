<?php

namespace app\database;
use PDO;
use PDOException;

class Connection
{
    public static function connect(){
        try{
            return new PDO("mysql:dbname=cronograma_estudos;host=localhost", "root", "");
        }catch(PDOException $e){
            var_dump($e->getMessage());
        }
    }
}