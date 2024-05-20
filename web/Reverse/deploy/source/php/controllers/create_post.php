<?php

require_once __DIR__.'/../utils/jwt.php';
require_once __DIR__.'/../models/token.php';
require_once __DIR__.'/../utils/db.php';
require_once __DIR__.'/../models/post.php';

if (!is_authorized())
    die(json_encode([
        'error' => true,
        'message' => 'Вы не авторизованы',
        'location' => '/login'
    ]));

$jwt_payload = get_jwt_payload();

$title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
$created_at = time();
$user_id = $jwt_payload->user_id;

$token = new Token($created_at, rand(0, 10000));

if (Post::create_post(Database::connect(), $title, $description, $user_id, $created_at, $token->create_token()))
    die(json_encode([
        'created' => true
    ]));
die(json_encode([
    'created' => false
]));