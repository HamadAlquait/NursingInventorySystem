<?php

include 'header.php';

$id = $_GET['id'];
$type = $_GET['type'];
$item = $_GET['item'];

if(isset($_SESSION['id'])) {
    echo "Are you sure you want to delete this ".$item. " ". strtolower($type) ."? This action cannot be undone.
        <form action ='includes/deleteRepairUpdateUpgrade.inc.php' method ='POST'><br>
            <input type='hidden' name='id' value = $id>
            <button type='submit'>Delete</button>
        </form><br>
        <form action='repairsUpdatesUpgrades.php'>
            <input type='submit' value='Cancel' />
         </form>";
}
else{
    header("Location: ./login.php");
}
?>