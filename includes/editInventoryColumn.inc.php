<?php

session_start();
include '../dbh.php';

$oldColumn = $_POST['oldColumn'];
if (strpos($oldColumn, '%20')) {
    $oldColumn = str_replace("%20", " ", $oldColumn);
}
$oldType = $_POST['oldType'];
$newColumn = $_POST['newColumn'];
$newType = $_POST['newType'];

if($newType == "Letters & Numbers"){
    $newType = "varchar(100)";
    $oldType = "varchar(100)";
}
else{
    $newType = "boolean";
}

$currentColumns = array();

$sql = "SHOW COLUMNS FROM inventory"; //checks if new column name already exists
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($result)) {
    array_push($currentColumns, $row['Field']);
}

if(in_array($newColumn, $currentColumns) && $newColumn !== $oldColumn){
    header("Location: ../editInventoryColumn.php?error=exists");
    exit();
}
if($newColumn == ""){
    header("Location: ../editInventoryColumn.php?error=empty");
    exit();
}

if($oldType != $newType){
    $sql = "UPDATE inventory SET ".$oldColumn. " = NULL;";
    $result = mysqli_query($conn, $sql);
}

$sql = "ALTER TABLE `inventory` CHANGE `".$oldColumn."` `".$newColumn."` ".$newType.";";
$result = mysqli_query($conn, $sql);
header("Location: ../editInventoryColumn.php?success");
exit();
?>