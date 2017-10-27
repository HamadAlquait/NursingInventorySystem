<?php
include 'header.php';

if(isset($_SESSION['id'])) {
    include 'dbh.php';

    echo "<head><Title>Delete Inventory Column</Title></head>";

    $url ="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(strpos($url, 'success') !== false){
        echo "<br>&nbsp&nbspColumn deleted successfully.<br>";
    }

    $columnNames = array();

    $sql = "SHOW COLUMNS FROM inventory";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        array_push($columnNames, $row['Field']);
    }

    echo '<br>&nbsp&nbspWhich column do you want to delete?
    
    <form method="post">
        <label>
            <br>&nbsp&nbsp<select name="column" onchange="this.form.submit()">
                <option selected value=""></option>';

    for($columnsCount = 0; $columnsCount < count($columnNames); $columnsCount++) {
        if($columnsCount > 8){
            echo '<option value = "'.$columnNames[$columnsCount].'">'.$columnNames[$columnsCount].'</option>';
        }
    }
    echo '</select></label></form>';

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $column = $_POST['column'];

        echo "<br><p>&nbsp&nbspAre you sure you want to delete column ".$column."? All data it contains will be gone forever.</p><br>
        <form action ='includes/deleteInventoryColumn.inc.php' method ='POST'>
                <input type='hidden' name='column' value = \"$column\">
                &nbsp&nbsp<button type='submit'>Delete</button>
            </form><br>
            <form action='inventory.php'>
                &nbsp&nbsp<input type='submit' value='Cancel' />
             </form>";
    }
}
else{
    header("Location: ./login.php");
}
?>