<?php
    require_once __DIR__.'/php/utils/db.php';
    require_once __DIR__.'/php/utils/jwt.php';

    if (!is_authorized())
        header("Location: /login");

    $username = get_jwt_payload()->username;
    
    $query = Database::connect()->query('select post.id as post_id, post.title as title, post.created_at as created_at, user.id as user_id, user.username as username from post join user on post.user_id = user.id;');
    $posts = $query->fetch_all(MYSQLI_ASSOC);
    
    Database::close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Посты</title>
    <link rel="stylesheet" href="/style/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="/style/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(() => {
            function openDialog(title, url, data) {
                $( '#dialog_container:not(.open)' ).addClass('open');
                $.ajax({
                    url: url,
                    method: 'get',
                    data: data,
                    success: function(data) {
                        $( '#dialog_container .dialog_title').html(title);
                        $( '#dialog_container #dialog_body' ).html(data);
                    }
                });
            }

            function closeDialog() {
                $( '#dialog_container.open' ).removeClass('open');
            }

            $( '.dialog_close' ).on('click', function(event) {
                closeDialog();
            });

            $( '.dialog_area' ).on('click', function(event) {
                closeDialog();
            });

            $( '.post' ).on('click', function(event) {
                var post_id = $( this ).data('postid');

                $.ajax({
                    url: '/controllers/post_access.php',
                    method: 'post',
                    data: { postid: post_id },
                    success: function(data) {
                        try {
                            var response = JSON.parse(data);
                            
                            if (response.owner === true)
                                location.href = `/post/${post_id}`;
                            else
                                openDialog('Ключ доступа', '/templates/access_token_dialog.php', { post_id: post_id });
                        } catch {}
                    }
                });

                //location.href = `/post/${$( this ).data('postid')}`;
            });

            $( '.logout_button' ).on('click', function (event) {
                $.ajax({
                    url: '/controllers/logout.php',
                    method: 'post',
                    success: function(data) {
                        try {
                            var response = JSON.parse(data);

                            if (response.error)
                                alert(response.message);
                            else
                                location.href = response.location;
                        } catch {}

                    }
                });
            });

            $( '#create_post' ).on('click', function(event) {
                openDialog('Создание поста', '/templates/create_post_dialog.php', {});
            });

            $( '#all_posts' ).on('click', function(event) {
                location.href = '/';
            });
        });
    </script>
</head>
<body>
    <header>
        <nav>
            <div class="nav-item" id="all_posts">Все посты</div>
            <div class="nav-item" id="create_post">Создать пост</div>
        </nav>
        <div class="profile">
            <span class="username"><?php echo $username ?></span>
            <div class="icon">
                <span class="fa fa-user fa-solid"></span>
            </div>
            <div class="logout_button">
                <span class="fa fa-right-from-bracket fa-lg"></span>
            </div>
        </div>
    </header>
    <div class="post_container">
        <?php foreach ($posts as $post): ?>
            <div class="post" data-postid="<?= $post['post_id'] ?>">
                <div class="post_title"><?= $post['title'] ?></div>
                <div class="post_header">
                    <span class="post_username"><?= $post['username'] ?></span>
                    <span class="post_created_at"><?= date('d.m.Y H:i:s', $post['created_at']) ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div id="dialog_container">
        <div id="dialog_header">
            <span class="dialog_title"></span>
            <div class="icon dialog_close"><span class="fa fa-xmark"></span></div>
        </div>
        <div id="dialog_body"></div>
    </div>
    <div class="dialog_area"></div>
</body>
</html>