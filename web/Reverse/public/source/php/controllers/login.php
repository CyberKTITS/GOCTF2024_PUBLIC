<?php

    require_once __DIR__.'/../utils/jwt.php';
    require_once __DIR__.'/../utils/db.php';

    $login_data = [
        'login' => $_POST['login'],
        'password' => $_POST['password']
    ];

    if (isset($login_data['login']) && isset($login_data['password']))
    {
        $query = Database::connect()->prepare("select id, username, password from user where login=? limit 1;");
        $query->bind_param("s", $login_data['login']);
        if (!$query->execute())
            die(json_encode([
                "error" => true,
                "message" => "Ошибка в запросе"
            ]));

        $result = $query->get_result();
        if ($result->num_rows == 0)
            die(json_encode([
                "error" => true,
                "message" => "Неверный логин или пароль"
            ]));

        $result = $result->fetch_array();

        if (!password_verify($login_data['password'], $result['password']))
            die(json_encode([
                "error" => true,
                "message" => "Неверный логин или пароль"
            ]));

        $payload = [
            'user_id' => $result['id'],
            'username' => $result['username'],
            'exp' => time() + 60 * 60 * 24 * 7
        ];
        $jwt = generate_jwt(array('alg' => 'HS256'), $payload);

        setcookie('token', $jwt, $payload['exp'], '/');
        echo json_encode([
            "error" => false,
            "location" => "/"
        ]);
    }