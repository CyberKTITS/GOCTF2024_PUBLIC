<?php

class Database {
    public static $link;

    public static function connect() {
        return isset(self::$link) ? self::$link : 
                                    self::$link = new mysqli("mysql", "root", "64d0ea90b1a2307ad12c7e2f1d158ca6a3", "post");
    }

    public static function close() {
        if (isset(self::$link))
        {
            self::$link->close();
        }
    }
}