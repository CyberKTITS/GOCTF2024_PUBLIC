<?php
    require_once __DIR__.'/../utils/jwt.php';
    require_once __DIR__.'/../utils/db.php';

    if (is_authorized())
        header("Location: /");

    if (isset($_POST['username']) && isset($_POST['login']) && isset($_POST['password']))
    {
        $query = Database::connect()->prepare("select username from user where username=?;");
        $query->bind_param("s", $_POST['username']);

        if (!$query->execute())
            die('Ошибка в запросе');

        $result = $query->get_result();
        if ($result->num_rows > 0)
            die('Имя пользователя уже занято');

        if (User::create_user(Database::connect(), $_POST['username'], $_POST['login'], password_hash($_POST['password'], CRYPT_SHA256)))
            header("Location: /");
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(() => {
            $('form').on('sumbit', function (event) {
                event.preventDefault();

                var request = {
                    method: $( this ).attr('method'),
                    action: $( this ).attr('action'),
                    data: $( this ).serialize()
                };

                $.ajax(request);
            });
        });

        function redirectToLogin() {
            location.href = "/index.php";
        }
    </script>
</head>
<body>
    <form action="" method="POST">
        <table>
            <tr>
                <td><label for="username">Имя пользователя: </label></td>
                <td><input type="text" name="username" id="username" placeholder="Имя пользователя"></td>
            </tr>
            <tr>
                <td><label for="login">Логин: </label></td>
                <td><input type="text" name="login" id="login" placeholder="Логин"></td>
            </tr>
            <tr>
                <td><label for="password">Пароль: </label></td>
                <td><input type="text" name="password" id="password" placeholder="Пароль"></td>
            </tr>
            <tr>
                <td><input type="button" value="Войти" onclick="redirectToLogin();"></td>
                <td><input type="submit" value="Регистрация"></td>
            </tr>
        </table>
    </form>
</body>
</html>