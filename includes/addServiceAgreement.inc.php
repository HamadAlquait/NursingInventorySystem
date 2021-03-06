<?php
session_start();

include '../dbh.php';

$name = $_POST['name'];
$name = str_replace("\\","\\\\","$name");
$name = str_replace("'","\'","$name");
$cost = $_POST['cost'];
$duration = $_POST['duration'];
$duration = str_replace("\\","\\\\","$duration");
$duration = str_replace("'","\'","$duration");
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

if($startDate > $endDate){
    header("Location: ../addServiceAgreement.php?error=reverseDates");
    exit();
}

if($_FILES["file"]["name"] == ""){ //no file uploaded
    $sql = "INSERT INTO serviceAgreements (Name, `Annual Cost`, Duration, `Start Date`, `End Date`)
    VALUES ('$name', '$cost', '$duration', '$startDate', '$endDate');";
    $result = mysqli_query($conn, $sql);

    header("Location: ../serviceAgreements.php");
}
else{ // file uploaded
    $extension = end(explode(".", $_FILES["file"]["name"]));

    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        die("Upload failed with error " . $_FILES['file']['error']);
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $_FILES['file']['tmp_name']);

    if($mime == 'application/pdf') {
        $sql = "INSERT INTO serviceAgreements (Name, `Annual Cost`, Duration, `Start Date`, `End Date`)
        VALUES ('$name', '$cost', '$duration', '$startDate', '$endDate');";
        $result = mysqli_query($conn, $sql);

        $sql = "SELECT * FROM serviceAgreements ORDER BY Id DESC LIMIT 1;";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_array($result);

        $docName = $row['Id'];

        $sql = "UPDATE serviceAgreements SET Approval = '$docName' WHERE Id = '$docName';";
        $result = mysqli_query($conn, $sql);

        move_uploaded_file($_FILES["file"]["tmp_name"], "../serviceAgreements/".$docName .".pdf");

        header("Location: ../serviceAgreements.php");
    }
    else{
        header("Location: ../addServiceAgreement.php?error=wrongType");
        exit();
    }
}
?>