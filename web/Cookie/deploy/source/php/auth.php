<?php
require_once './jwt.php';
require_once './secret.php';

$headers = array(
    "alg" => "HS256"
);

$login_data = array(
    'login' => $_POST['login'],
    'password'=> hash('sha256', $_POST['password']),
);

if (!isset($login_data['login']) || !isset($login_data['password']))
    die('Ошибка входа');

require_once './db.php';

$link = new mysqli($host, $user, $password, $database) or die($link->connect_error);

$result = $link->query("select username, role from user where login='". $login_data['login'] ."' and password='". $login_data['password'] ."' limit 1;");

if (!$result)
    die(json_encode([
        "error" => true,
        "message" => "Неверный логин или пароль"
    ]));

if ($result->num_rows == 0)
    die(json_encode([
        "error" => true,
        "message" => "Неверный логин или пароль"
    ]));

$result = $result->fetch_array();

$payload = array(
    'username' => $result['username'],
    'role' => $result['role'],
    'exp' => time() + 60*60
);

$jwt = generate_jwt($headers, $payload, $secret);
setcookie('token', $jwt, time() + 60 * 60,'/');

echo json_encode([
    "error" => false,
    "location" => "/"
]);