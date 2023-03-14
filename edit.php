<?php
$link = include 'connect.php';
$id = $_GET['id'];
$value = $_GET['value'];
if($value == 'delete!') {
    $query = "DELETE FROM `quests` WHERE id = $id";
    mysqli_query($link, $query) or die(mysqli_error($link));
    $json = [
        'id' => $id,
        'quest' => 'задача была удалена!',
    ];
} else {
    $query = "UPDATE `quests` SET quest = '$value' WHERE id = $id";
    mysqli_query($link, $query) or die(mysqli_error($link));
    $json = [
        'id' => $id,
        'quest' => $value,
    ];
}
echo json_encode($json);
?>