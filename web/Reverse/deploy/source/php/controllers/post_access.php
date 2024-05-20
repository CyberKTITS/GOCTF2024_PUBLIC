<?php

require_once __DIR__.'/../utils/db.php';
require_once __DIR__.'/../utils/jwt.php';

if (!is_authorized())
    die(json_encode([
        'error' => false,
        'location' => '/'
    ]));

$jwt_payload = get_jwt_payload();
$access_token = $_POST['access_token'];
$user_id = $jwt_payload->user_id;
$post_id = $_POST['postid'];

$query = Database::connect()->prepare('select post.access_token as access_token, user.id as user_id from post join user on post.user_id = user.id where post.id = ?');
$query->bind_param("s", $post_id);

if (!$query->execute())
    die(json_encode([
        'error' => true,
        'message' => 'Ошибка в запросе'
    ]));

$result = $query->get_result();
if ($result->num_rows == 0)
    die(json_encode([
        'error' => true,
        'message' => 'Неверный номер поста'
    ]));

$result = $result->fetch_array(MYSQLI_ASSOC);
if ($result['user_id'] == $user_id)
    die (json_encode([
        'error' => false,
        'owner' => true
    ]));

if ($result['access_token'] == $access_token)
    die (json_encode([
        'error' => false,
        'owner' => false
    ]));

die(json_encode([
    'error' => true,
    'message' => 'Нет доступа'
]));