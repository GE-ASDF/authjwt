<?php

require '../vendor/autoload.php';
use app\database\Connection;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

$email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
$senha = filter_input(INPUT_POST, "senha");


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

$key = "adafjlçkj3423";
$payload = [
    "exp" => time() + 10,
    "iat" => time(),
    "email"=> $email,
];

$encode = JWT::encode($payload, $key, 'HS256');
echo json_encode($encode);

if(isset($_SERVER["HTTP_AUTHORIZATION"])){

$authorization = $_SERVER["HTTP_AUTHORIZATION"];
$token = str_replace('Bearer ', '', $authorization);
die($authorization);
try{
    $decoded = JWT::decode($encode, new Key($token, 'HS256'));
    echo json_encode($decoded);
}catch(Throwable $e){
    if($e->getMessage() == "Expired token"){
        http_response_code(401);
        die($e->getMessage());
    }
    echo json_encode($e->getMessage());
}

}