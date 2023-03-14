<?php
$link = include 'connect.php';
$id = $_GET['id'];
$query = "DELETE FROM `quests` WHERE id = $id";
mysqli_query($link, $query) or die(mysqli_error($link));

echo $id;
?>