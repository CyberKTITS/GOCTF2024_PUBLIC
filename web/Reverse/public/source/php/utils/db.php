<?php

class Database {
    public static $link;

    public static function connect() {
        return isset(self::$link) ? self::$link : 
                                    self::$link = new mysqli("mysql", "root", "root", "post");
    }

    public static function close() {
        if (isset(self::$link))
        {
            self::$link->close();
        }
    }
}