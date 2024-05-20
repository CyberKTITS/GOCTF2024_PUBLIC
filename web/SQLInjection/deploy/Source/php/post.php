<?php

include "db.php";

$link = new mysqli($host, $user, $password, $dbname);

if (! $link)
    die($link->connect_error);

$query = $link->query("select title, description from post where id = ". $_GET['id'] . ";");
$result = $query->fetch_array();

echo "<h3>". $result['title'] ."</h3>" .
     "<p>". $result['description'] ."</p>";