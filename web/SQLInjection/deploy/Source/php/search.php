<?php
include "db.php";

$link = new mysqli($host, $user, $password, $dbname);

$query = "SELECT id, title FROM post WHERE title LIKE '%". $_POST['script'] . "%';";
$result = $link->query($query);
if (! $result)
    die('Неверный запрос');

if ($result->num_rows <= 0)
    die('Ничего не найдено');

echo "<table><tr><th>Название</th></tr>";
while ($row = $result->fetch_array()) {
    echo "<tr>";
    echo "<td>". '<a class="post_opener" href="/php/post.php?id='. $row['id'] .'">'. $row['title'] . '</a>' ."</td>";
    echo "</tr>";
}
echo "</table>";


$link->close();