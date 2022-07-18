<?php

require '../vendor/autoload.php';
use app\database\Connection;
use Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$senha = strip_tags($_POST["senha"]);

$pdo = Connection::connect();
$prepare = $pdo->prepare("SELECT * FROM usuarios WHERE email= :email");
$prepare->bindValue(":email", $email);
$prepare->execute();
$userFound = $prepare->fetch(\PDO::FETCH_OBJ);

if(!$userFound){
    http_response_code(401);
}

if(!password_verify($senha, $userFound->senha)){
    http_response_code(401);
}

$payload = [
    "exp" => time() + 10,
    "iat" => time(),
    "email"=> $email
];
$encode = JWT::encode($payload, $_ENV['KEY'], 'HS256');