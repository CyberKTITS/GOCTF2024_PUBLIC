<?php
    require_once './php/jwt.php';
    require_once './php/secret.php'
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="/js/main.js"></script>
</head>
<body>
    <?php
        
        if (is_authorized($secret))
        {
            $payload = get_jwt_payload($_COOKIE['token']);
            require './html/authorized.php';
        }
        else
            echo file_get_contents('./html/login.html');

    ?>
</body>
</html>