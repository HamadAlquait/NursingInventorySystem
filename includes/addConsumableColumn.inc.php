<?php
session_start();

include '../dbh.php';

$name = $_POST['name'];
$type = $_POST['type'];

if($type == "varchar"){
    $sql = "ALTER TABLE consumables ADD `$name` VARCHAR(100);";
}
elseif($type = "tinyint"){
    $sql = "ALTER TABLE consumables ADD `$name` TINYINT(1);";
}

$result = mysqli_query($conn, $sql);

header("Location: ../consumables.php");

?>