<?php

include 'dbh.php';

if(isset($_POST["export"]))
{
    $date = $_POST['date'];
    $dateTitle = date_create($date);
    $sql = "SELECT `Activity Type`, `Serial Number`, Item, inventoryReports.Subtype, subtypes.Type, `Beginning Quantity`, `End Quantity`, Timestamp, `Update Person` FROM inventoryReports JOIN subtypes ON subtypes.Subtype = inventoryReports.Subtype WHERE Timestamp BETWEEN '".$date." 00:00:00' AND '".$date." 23:59:59' ORDER BY Timestamp;";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) > 0)
    {
        $output = '<h2><b>Activities for '.date_format($dateTitle, "m/d/Y").'</b></h2>
                   <table class="table" bordered="1">
                       <tr>
                           <th>Activity Type</th>
                           <th>Serial Number</th>
                           <th>Item</th>
                           <th>Type</th>
                           <th>Subtype</th>
                           <th>Beginning Quantity</th>
                           <th>End Quantity</th>
                           <th>Timestamp</th>
                           <th>Update Person</th>
                       </tr>';
        while($row = mysqli_fetch_array($result))
        {
            $output .= '<tr>
                            <td>'.$row["Activity Type"].'</td>
                            <td>'.$row["Serial Number"].'</td>
                            <td>'.$row["Item"].'</td>
                            <td>'.$row["Type"].'</td>
                            <td>'.$row["Subtype"].'</td>
                            <td>'.$row["Beginning Quantity"].'</td>
                            <td>'.$row["End Quantity"].'</td>
                            <td>'.$row["Timestamp"].'</td>
                            <td>'.$row["Update Person"].'</td></tr>';
        }
        $output .= '</table>';

        $date = date_create($date);
        header('Content-Type: application/xls');
        header('Content-Disposition: attachment; filename=reports_'.date_format($date,"m-d-Y").'.xls');
        echo $output;
    }
}
?>