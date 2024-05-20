<?php

require_once __DIR__.'/php/models/route.php';
require_once __DIR__.'/php/utils/imports.php';

$router = new Route;

$router->add("/",              __DIR__."/index.php");
$router->add("post/{postid}", __DIR__.'/pages/post.php');
$router->add("/post/{postid}/{access_token}", __DIR__."/pages/post.php");
$router->add("/login",         __DIR__."/pages/login.php");
$router->add("/register",      __DIR__."/php/controllers/register.php");

$router->notFound(__DIR__.'/pages/notFound.php');