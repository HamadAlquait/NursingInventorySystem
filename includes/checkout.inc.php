<?php
session_start();

include '../dbh.php';

$type = $_POST['type'];
$type = str_replace("'","\'","$type");
$subType = $_POST['subtype'];
$subType = str_replace("'","\'","$subType");
$item = $_POST['item'];
$item = str_replace("'","\'","$item");
$numBorrowed = $_POST['stock'];
$person = $_POST['person'];
$person = str_replace("'","\'","$person");
$reason = $_POST['reason'];
$reason = str_replace("'","\'","$reason");
$notes = $_POST['notes'];
$notes = str_replace("'","\'","$notes");
$date = $_POST['date'];

if(isset($_SESSION['id'])) {
    //get update person
    $id = $_SESSION['id'];
    $sql = "SELECT uid FROM users WHERE id = ". $id.";";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $uid = $row['uid'];

    $sql = "SELECT CURDATE();";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $today = $row['CURDATE()'];

    //check if borrowed amount exceeds stock
    $sql = "SELECT `Number in Stock` FROM inventory WHERE Item = '".$item."';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $stock = $row['Number in Stock'];
    $remaining = $stock - $numBorrowed;

    if($numBorrowed > $stock){
        header("Location: ../checkout.php?type=".$type."&subtype=".$subType."&item=".$item."&error=over");
        exit();
    }
    if($numBorrowed == 0){
        header("Location: ../checkout.php?type=".$type."&subtype=".$subType."&item=".$item."&error=zero");
        exit();
    }

    //finds serial number
    $sql = "SELECT `Serial Number` FROM inventory WHERE Item = '".$item."';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    $serialNumber = $row['Serial Number'];

    $sql = "INSERT INTO checkouts(`Item`, `Subtype`, `Quantity Borrowed`, `Serial Number`, `Person`, `Reason`, `Notes`,
    `Due Date`, `Checkout Date`, `Update Person`) VALUES('".$item."','".$subType."','".$numBorrowed."','".$serialNumber."','".
    $person."','".$reason."','".$notes."','".$date."','".$today."','".$uid."');";

    $result = mysqli_query($conn, $sql);

    $sql = "UPDATE inventory SET `Number in Stock` = ".$remaining.", `Last Processing Date` = '".$today."', `Last Processing Person` = '".$uid."' WHERE `Item` = '".$item."';";
    $result = mysqli_query($conn, $sql);

    header("Location: ../checkout.php?success");
    exit();
}
else{
    header("Location: ./login.php");
}
?>