<?php
$link = include 'connect.php';
$id = $_GET['id'];
$query = "UPDATE `quests` SET deleted = 1 WHERE id= $id";
mysqli_query($link, $query) or die(mysqli_error($link));

var_dump($_GET);
?>