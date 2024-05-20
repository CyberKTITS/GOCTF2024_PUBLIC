<?php

require_once __DIR__.'/../utils/jwt.php';

if (!is_authorized())
    die(json_encode([
        'error' => true,
        'message' => 'Вы не авторизованы'
    ]));

setcookie('token', '', -1, '/');
echo json_encode([
    'error' => false,
    'location' => '/login'
]);