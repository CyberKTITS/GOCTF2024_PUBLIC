<?php

if (!isset($_COOKIE['token']))
    die("Вы не авторизованы");

unset($_COOKIE['token']);
setcookie('token', '', -1, '/');
header("Refresh:0");