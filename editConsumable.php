<?php
include 'header.php';

if(isset($_SESSION['id'])) {
    include 'dbh.php';

    $originalItem = $_GET['edit'];
    $columnNames = array();
    $type;
    echo "<head><Title>Edit Consumable</Title><script src=\"./js/jquery.min.js\"></script></head>";

    $url ="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(strpos($url, 'error=exists') !== false){
        echo "<br>&nbsp&nbspAn consumable with that name already exists.<br>";
    }
    elseif(strpos($url, 'error=typeMismatch') !== false){
        $subtype = $_GET['subtype'];
        $type = $_GET['type'];
        echo "<br>&nbsp&nbspThe subtype $subtype already relates to the type $type. Subtypes can only have one type.<br>";
    }

    $sql = "SHOW COLUMNS FROM consumables"; //gets first headers for page
    $result = mysqli_query($conn, $sql);
    $innerCount = 0;
    while ($row = mysqli_fetch_array($result)) {
        if ($innerCount < 2) {
            $innerCount++;
            array_push($columnNames, $row['Field']);
        }
    }
    $sql = "SHOW COLUMNS FROM consumables"; //gets second headers for page
    $result = mysqli_query($conn, $sql);
    $innerCount = 0;
    while ($row = mysqli_fetch_array($result)) {
        $innerCount++;
        if ($innerCount > 2) {
            array_push($columnNames, $row['Field']);
        }
    }

    $sqlSubtype = "SELECT Subtype FROM consumables WHERE `Item` = '". $originalItem."';";
    $resultSubtype = mysqli_query($conn, $sqlSubtype);
    $subRow = mysqli_fetch_array($resultSubtype);
    $subtype = $subRow['Subtype'];

    $typeSQL = "SELECT Type FROM subtypes WHERE Subtype = '$subtype'";
    $typeResult = mysqli_query($conn, $typeSQL);
    $typeRow = mysqli_fetch_array($typeResult);
    $type = $typeRow['Type'];

    echo "<div class=\"container\"><form class=\"well form-horizontal\" action ='includes/editConsumable.inc.php'
        id=\"contact_form\" method ='POST'><input type='hidden' name='originalItem' value = '$originalItem'>
        <fieldset><h2 align=\"center\">  Edit Consumable Item</h2><br/>";

    $sql="SELECT * FROM consumables WHERE `Item` = '".$originalItem."';";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    for($count = 0; $count < (count($columnNames)); $count++){
        if($columnNames[$count] != "Last Processing Date" && $columnNames[$count] != "Last Processing Person") { //Last processing date & person should not be editable
            $isSelect = false;
            $columnName = $columnNames[$count];
            $sql2 = "SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS
            WHERE table_name = 'consumables' AND COLUMN_NAME = '$columnNames[$count]';";
            $result2 = mysqli_query($conn, $sql2);
            $rowType = mysqli_fetch_array($result2);
            if ($rowType['DATA_TYPE'] == "tinyint" || $count == 1) {
                $isSelect = true;
                if($count == 1) {
                    $inputs = "<div class=\"form-group\"><label class=\"col-md-4 control-label\">Subtype:</label>  
                    <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
                    <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-th\"></i></span>
                    <input style='height:30px; width:100%;' list='Subtypes' value='$subtype' placeholder='   Subtype' name=";
                }
                else{
                    $inputs = "<div class='form-group'><label class='col-md-4 control-label'>$columnNames[$count]:</label>
                    <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
                    <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-th-list\"></i></span>
                    <select class=\"form-control selectpicker\" name=";
                }
            } elseif ($rowType['DATA_TYPE'] == "int") {
                if($count == 3){
                    $inputs = '<div class="form-group"><label class="col-md-4 control-label">Number in Stock:
                    </label><div class="col-md-4 inputGroupContainer"><div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                    <input type="number" placeholder="Number in Stock" min="0" class="form-control" name=';
                }
                elseif($count == 4){
                    $inputs = '<div class="form-group"><label class="col-md-4 control-label">Minimum Stock:
                    </label><div class="col-md-4 inputGroupContainer"><div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-question-sign"></i></span>
                    <input type="number" placeholder="Minimum Stock" min="0" class="form-control" name=';
                }
            } else {
                if($count == 0){
                    $inputs ="<div class=\"form-group\"><label class=\"col-md-4 control-label\" >Item:</label> 
                    <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
                    <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-info-sign\"></i></span>
                    <input type='text' placeholder=\"Item Name\" class=\"form-control\" name=";
                }
                elseif($count == 2){
                    $inputs = '<div class="form-group"><label class="col-md-4 control-label">Location:
                    </label><div class="col-md-4 inputGroupContainer"><div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
                    <input type="text" placeholder="Item\'s Location" class=\'form-control\' name=';
                }
                else{
                    $inputs = "<div class=\"form-group\"><label class=\"col-md-4 control-label\">$columnNames[$count]:
                    </label><div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
                    <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-info-sign\"></i></span>
                    <input type=\"text\" placeholder='$columnNames[$count]' class='form-control' name=";
                }
            }
            if (strpos($columnName, ' ')) {
                $columnName = str_replace(" ", "", $columnName);
            }
            if ($isSelect) {
                $inputs .= $columnName."><datalist id=\"Subtypes\">";
                if ($count == 1) {
                    $sql3 = "SELECT Subtype FROM subtypes WHERE isConsumable = 1";
                    $result3 = mysqli_query($conn, $sql3);
                    while ($SubtypeRow = mysqli_fetch_array($result3)) {
                        $inputs .= "<option value= '" . $SubtypeRow['Subtype']."'>".$SubtypeRow['Subtype']."</option>";
                    }

                    $inputs .= "</datalist></div></div></div><div class=\"form-group\">
                        <label class=\"col-md-4 control-label\">Type:</label>  
                        <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
                        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-th-large\"></i></span>
                        <input style='height:30px; width:100%;' list='Types' value='$type' placeholder='   Type' id='type' name='type'>
                        <datalist id=\"Types\">";
                    $sql4 = "SELECT DISTINCT Type FROM subtypes WHERE isConsumable = 1";
                    $result4 = mysqli_query($conn, $sql4);
                    while ($row4 = mysqli_fetch_array($result4)) {
                        $inputs .= "<option value= '" . $row4['Type']."'>".$row4['Type']."</option>";
                    }
                    $inputs .= "</datalist></div></div></div>";
                } else {
                    if ($row[$columnNames[$count]] == 0 && $row[$columnNames[$count]] !== null) {
                        $inputs .= "<option value=0>No</option><option value=1>Yes</option></select></div></div></div>";
                    } elseif ($row[$columnNames[$count]] !== null) {
                        $inputs .= "<option value=1>Yes</option><option value=0>No</option></select></div></div></div>";
                    } else {
                        $inputs .= "<option value=''></option><option value=1>Yes</option><option value=0>No</option>
                        </select></div></div></div>";
                    }
                }
            } else {
                $inputs .= $columnName . " value=\"" . $row[$columnNames[$count]] . "\"></div></div></div>";
            }
            echo $inputs;
        }
    }
    echo '<input type="hidden" name="originalSubtype" value = \''.$row['Subtype']. '\'>
          <input type="hidden" name="originalType" value = \''.$type. '\'>
          <div class="form-group"><label class="col-md-4 control-label"></label><div class="col-md-4">
          <button name="submit" type="submit" class="btn btn-warning btn-block" id="contact-submit" 
          data-submit="...Sending">Submit</button></div></div>';


    echo "<script>$('document').ready(function() {
   
    $('#type').on('change',function(){
        alert(\"Warning: Changing type will change the type for every item with this subtype.\");
    });
    
});</script>";
}
else{
    header("Location: ./login.php");
}
?>