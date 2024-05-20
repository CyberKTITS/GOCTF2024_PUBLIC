<?php

class Post {
    public $id, $title, $user_id, $username, $description, $created_at, $token;

    public function __construct($id, $username, $title, $user_id, $description, $created_at, $token) {
        $this->id = $id;
        $this->title = $title;
        $this->user_id = $user_id;
        $this->username = $username;
        $this->description = $description;
        $this->created_at = $created_at;
        $this->token = $token;
    }

    public static function create_post(mysqli $link, $title, $description, $user_id, $created_at, $token) {
        $query = $link->prepare("insert into post(user_id, title, description, access_token, created_at) values(?, ?, ?, ?, ?)");
        $query->bind_param("sssss", $user_id, $title, $description, $token, $created_at);

        return $query->execute();
    }

    public static function find_post(mysqli $link, string $id) {
        $query = $link->prepare(
            "select 
                title, 
                user_id, 
                user.username as username, 
                description, 
                created_at, 
                access_token 
            from 
                post join user on post.user_id = user.id 
            where
                post.id=? limit 1");
        $query->bind_param("s", $id);

        if ($query->execute())
        {
            $result = $query->get_result()->fetch_array(MYSQLI_ASSOC);
            return new Post(
                $id,
                $result['username'],
                $result['title'],
                $result['user_id'],
                $result['description'],
                $result['created_at'],
                $result['access_token']
            );
        }

        return false;
    }

    public static function delete_post(mysqli $link, string $id) {
        $query = $link->prepare("delete from post where id=? limit 1;");
        $query->bind_param("i", $id);

        return $query->execute();
    }
}