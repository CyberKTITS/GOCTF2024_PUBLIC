<?php

require_once __DIR__.'/../php/utils/jwt.php';

if (!is_authorized())
    header("Location: /");

$post = Post::find_post(Database::connect(), $params['postid']) or die('Неверный postid');
$jwt_payload = get_jwt_payload();
if ($jwt_payload->user_id != $post->user_id && (!isset($params['access_token']) || $params['access_token'] != $post->token))
    die('Нет доступа');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/style/post.css">
    <title><?php echo $post->title ?></title>
</head>
<body>
    <div class="post_container">
        <div class="post_header">
            <h1 class="title"><?php echo $post->title ?></h1>
            <div class="post_info">
                <span class="username"><?php echo $post->username ?></span>
                <span class="created_at"><?php echo date('d.m.Y H:i:s', $post->created_at) ?></span>
            </div>
        </div>
        <div class="post_description">
            <?php foreach(explode("\n", $post->description) as $paragraph): ?>
                <p><?= $paragraph ?></p>
            <?php endforeach; ?>
        </div>

        <?php if ($post->user_id == $jwt_payload->user_id): ?>
            <div class="access_token_container">
                <label for="access_token">Ключ доступа:</label>
                <input name="access_token" class="text_input" readonly value="<?= $post->token ?>"></input>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>