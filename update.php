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
$quest = $_GET['value'];
$query = "INSERT INTO `quests` (quest, date, deleted) VALUES ('$quest', '$date', 0)";
mysqli_query($link, $query) or die(mysqli_error($link));

$query = "SELECT id,quest FROM `quests` WHERE id=LAST_INSERT_ID()";
$result = mysqli_query($link, $query) or die(mysqli_error($link));
$json = json_encode(mysqli_fetch_assoc($result));
echo $json;
?>