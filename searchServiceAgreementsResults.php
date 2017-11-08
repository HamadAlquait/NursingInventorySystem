<?php
include 'table.php';

if(isset($_SESSION['id'])) {
    include 'dbh.php';
    echo "<head><Title>Search Service Agreements Results</Title></head>";

    error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);

    $currentID = $_SESSION['id'];
    $sql = "SELECT acctType FROM users WHERE id='$currentID'";
    $result = mysqli_query($conn, $sql);
    $row = $result->fetch_assoc();
    $acctType = $row['acctType'];

    $sql = "SELECT * FROM serviceAgreements;";
    $result = mysqli_query($conn, $sql);
    $approvals = array();
    while($row = mysqli_fetch_array($result)){
        array_push($approvals, $row['Approval']);
    }

    $name = $_POST['name'];
    $cost = $_POST['cost'];
    $duration = $_POST['duration'];
    $date = $_POST['date'];

    $tableHeadNeeded = true;
    $count = 0;
    $sql = "SELECT * FROM serviceAgreements WHERE ";
    $andNeeded = false;
    if($name == "" && $cost == "" && $duration == "" && $date == ""){
        echo "<br> Please fill out at least 1 search field.";
        echo "<br><br><form action='searchServiceAgreementsForm.php'> 
                   <input type='submit' value='Search Service Agreements'/>
              </form>";
        exit();
    }
    if($name !== "")
    {
        $sql .= "Name LIKE '%".$name."%'";
        error_reporting(E_ERROR | E_PARSE); //silences warning that comes up if a string is searched for
        $andNeeded = true;
    }
    if($cost !== "")
    {
        if($andNeeded){
            $sql .= " AND ";
        }
        $sql .= "`Annual Cost` LIKE '%".$cost."%'";
        $andNeeded = true;
    }
    if($duration !== "")
    {
        error_reporting(E_ERROR | E_PARSE);
        if($andNeeded){
            $sql .= " AND ";
        }
        $sql .= "Duration LIKE '%".$duration."%'";
        $andNeeded = true;
    }
    if($date !== "")
    {
        error_reporting(E_ERROR | E_PARSE);
        if($andNeeded){
            $sql .= " AND ";
        }
        $sql .= "`Expiration Date` LIKE '%".$date."%'";
        $andNeeded = true;
    }
    $sql .=";";

    $result = mysqli_query($conn, $sql);
    echo "<br>";
    while($row = mysqli_fetch_array($result)) {
        if($tableHeadNeeded){
            $tableHeadNeeded = false;
            $count++;
            echo "<table id=\"example\" class=\"table table-striped table-bordered dt-responsive nowrap\" cellspacing=\"0\" width=\"100%\">
            <thead><tr><th>Name</th>
            <th>Annual Cost</th>
            <th>Duration</th>
            <th>Expiration Date</th>";
            if(count($approvals) > 0){
                echo "<th>Approval Form</th>";
            }
            echo "<th>Edit</th>";
            if ($acctType == "Admin" || $acctType == "Super Admin") {
                echo "<th>Delete</th>";
            }
            echo "</tr></thead><tbody>";
        }
        echo "<tr><td> " . $row['Name'] . "</td>
            <td> " . $row['Annual Cost'] . "</td>
            <td> " . $row['Duration'] . "</td>";
        $date = date_create($row['Expiration Date']);
        echo "<td> " . date_format($date, 'm/d/Y') . "</td>";
        if($row['Approval'] !== NULL){
            echo "<td><a href='serviceAgreements/$row[Id].pdf'>Approval Form</a></td>";
        }
        else{
            echo "<td></td>";
        }
        echo "<td><a href='editServiceAgreement.php?edit=$row[Id]'>Edit</a></td>";
        if ($acctType == "Admin" || $acctType == "Super Admin") {
            echo "<td><a href='deleteServiceAgreement.php?id=$row[Id]&name=$row[Name]'>Delete</td>";
        }
        echo "</tr>";
    }
    echo "</tbody></table>";

    if($count == 0) {
        echo "<br> No Service Agreements Found That Match All of Those Criteria.<br>";
    }
}
else{
    header("Location: ./login.php");
}

include 'tableFooter.php';
?>