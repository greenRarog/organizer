<?php
$host = 'localhost';
$nameBD = 'organizer';
$userBD = 'root';
$passwordBD = 'root';
$link = mysqli_connect($host, $userBD, $passwordBD, $nameBD);

return $link;
?>