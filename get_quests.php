<?php
$link = include 'connect.php';
$decoder_month = [
  1 => '01',
  2 => '02',
  3 => '03',
  4 => '04',
  5 => '05',
  6 => '06',
  7 => '07',
  8 => '08',
  9 => '09',
  10 => '10',
  11 => '11',
  12 => '12'
];
$date = $_GET['year'] . '-' . $decoder_month[$_GET['month']] . '-' . $_GET['day'];
$query = "SELECT * FROM `quests` WHERE date = '$date'";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
for ($quests = []; $row = mysqli_fetch_assoc($result); $quests[] = $row);

echo  json_encode($quests);

?>