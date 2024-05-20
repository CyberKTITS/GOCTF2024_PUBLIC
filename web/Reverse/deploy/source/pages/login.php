<?php

    require_once __DIR__.'/../php/utils/jwt.php';

    if (is_authorized())
        header('Location: /');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
        $(document).on('ready', () => {
            $( 'form' ).on('submit', function(e) {
                e.preventDefault();

                var request = {
                    url: $( this ).attr('action'),
                    method: $( this ).attr('method'),
                    data: $( this ).serialize(),
                    success: function(data) {
                        try {
                            var response = JSON.parse(data);

                            if (response.error)
                                alert(response.message);
                            else
                                location.href = response.location;
                        } catch {}
                    }
                };
                
                $.ajax(request);
            });
        });

        function redirectToRegister() {
            location.href = "/register";
        }
    </script>
</head>
<body>
    <form action="/controllers/login.php" method="POST">
        <table>
            <tr>
                <td><label for="login">Логин: </label></td>
                <td><input type="text" name="login" id="login" placeholder="Логин"></td>
            </tr>
            <tr>
                <td><label for="password">Пароль: </label></td>
                <td><input type="text" name="password" id="password" placeholder="Пароль"></td>
            </tr>
            <tr>
                <td><input type="submit" value="Войти"></td>
                <td><input type="button" value="Регистрация" onclick="redirectToRegister();"></td>
            </tr>
        </table>
    </form>
</body>
</html>