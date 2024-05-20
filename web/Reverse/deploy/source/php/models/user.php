<?php

class User {
    public $id, $username;

    public function __construct(int $id, string $username) {
        $this->id = $id;
        $this->username = $username;
    }

    public static function create_user(mysqli $link, string $username, string $login, string $password) {
        $query = $link->prepare("insert into user(username, login, password) values(?, ?, ?);");
        $query->bind_param("sss", $username, $login, $password);
        return $query->execute();
    }

    public static function find_user(mysqli $link, string $login, string $password) {
        $query = $link->prepare("select id, username from user where login=? and password=? limit 1;");

        $password_hash = password_hash($password, CRYPT_SHA256);

        $query->bind_param("ss", $login, $password_hash);
        if ($query->execute() && $query->num_rows > 0)
        {
            $result = $query->get_result()->fetch_array();
            return new User($result['id'], $result['username']);
        }

        return false;
    }
}