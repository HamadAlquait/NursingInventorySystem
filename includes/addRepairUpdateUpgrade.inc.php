<?php
session_start();

include '../dbh.php';

$type = $_POST['type'];
$type = str_replace("\\","\\\\","$type");
$type = str_replace("'","\'","$type");
$serialNumber = $_POST['serialNumber'];
$serialNumber = str_replace("\\","\\\\","$serialNumber");
$serialNumber = str_replace("'","\'","$serialNumber");
$item = $_POST['item'];
$item = str_replace("\\","\\\\","$item");
$item = str_replace("'","\'","$item");
$part = $_POST['part'];
$part = str_replace("\\","\\\\","$part");
$part = str_replace("'","\'","$part");
$cost = $_POST['cost'];
$date = $_POST['date'];
$supplier = $_POST['supplier'];
$supplier = str_replace("\\","\\\\","$supplier");
$supplier = str_replace("'","\'","$supplier");
$reason = $_POST['reason'];
$reason = str_replace("\\","\\\\","$reason");
$reason = str_replace("'","\'","$reason");

$sql = "INSERT INTO `repairs/updates/upgrades` (Type, `Serial Number`, Item, Part, Cost, Date, Supplier, Reason) 
    VALUES ('$type', '$serialNumber', '$item', '$part', '$cost', '$date', '$supplier', '$reason');";

echo $sql;

$result = mysqli_query($conn, $sql);

header("Location: ../repairsUpdatesUpgrades.php");

?>