<?php
include 'header.php';
include 'inputJS.php';

if(isset($_SESSION['id'])) {
    include 'dbh.php';
    $number = $_GET['edit'];
    echo "<head>
              <Title>Edit Client</Title>
          </head>
          <div class=\"parent\">
              <button class=\"help\" onclick=\"window.location.href='./UserManual.pdf#page=25'\">
                  <i class='fa fa-question'></i>
              </button>
          </div>";

    $sql="SELECT * FROM clients WHERE number = $number";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    if(mysqli_num_rows($result) == 0){
        echo "<br><h3 style='text-align: center'>Sorry, some information got lost along the way. Please go back and try again.</h3><br>
              <div style='text-align: center'>
                  <input onclick=\"window.location.href='clients.php';\" class='btn btn-warning' value='Back'>
              </div>";
        exit();
    }

    $first = $row['First'];
    $first = str_replace("\"","&quot;","$first");
    $last = $row['Last'];
    $last = str_replace("\"","&quot;","$last");
    $office = $row['Office'];
    $office = str_replace("\"","&quot;","$office");

    echo "<div class=\"container\">
              <form action ='includes/editClient.inc.php' class=\"well form-horizontal\" method ='POST' id=\"contact_form\">
                  <fieldset>
                      <h2 align=\"center\">Edit Client</h2><br/>
                      <input type='hidden' name='number' value = $number>
              
                      <div class=\"form-group\">
                          <label class=\"col-md-4 control-label\">First Name:
                              <a style=\"color:red;\" title=\"This field must be filled\">*</a>
                          </label>
                          <div class=\"col-md-4 inputGroupContainer\">
                              <div class=\"input-group\">
                                  <span class=\"input-group-addon\">
                                      <i class=\"glyphicon glyphicon-user\"></i>
                                  </span>
                                  <input type='text' name='first' class=\"form-control\" required placeholder='First Name' value=\"".$first."\">
                              </div>
                          </div>
                      </div>
                        
                      <div class=\"form-group\">
                          <label class=\"col-md-4 control-label\">Last Name:
                              <a style=\"color:red;\" title=\"This field must be filled\">*</a>
                          </label> 
                          <div class=\"col-md-4 inputGroupContainer\">
                              <div class=\"input-group\">
                                  <span class=\"input-group-addon\">
                                      <i class=\"glyphicon glyphicon-user\"></i>
                                  </span>
                                  <input type='text' name='last' class=\"form-control\" required placeholder='Last Name' value=\"".$last."\">
                              </div>
                          </div>
                      </div>
                          
                      <div class=\"form-group\">
                          <label class=\"col-md-4 control-label\">Ext.
                              <a style=\"color:red;\" title=\"This field must be filled\">*</a>
                          </label>
                          <div class=\"col-md-4 inputGroupContainer\">
                              <div class=\"input-group\">
                                  <span class=\"input-group-addon\">
                                      <i class=\"fa fa-phone-square\"></i>
                                  </span>  
                                  <input type='number' min='0' name='ext' class=\"form-control\" required placeholder='Extension' value='".$row['Ext']."'>
                              </div>
                          </div>
                      </div>
                      
                      <div class=\"form-group\">
                          <label class=\"col-md-4 control-label\">E-Mail:
                              <a style=\"color:red;\" title=\"This field must be filled\">*</a>
                          </label>
                          <div class=\"col-md-4 inputGroupContainer\">
                              <div class=\"input-group\">
                                  <span class=\"input-group-addon\">
                                      <i class=\"glyphicon glyphicon-envelope\"></i>
                                  </span>
                                  <input type='email' name='email' class=\"form-control\" required placeholder='E-mail' value=\"".$row['Email']."\">
                              </div>
                          </div>
                      </div>
                      
                      <div class=\"form-group\">
                          <label class=\"col-md-4 control-label\">Office:
                              <a style=\"color:red;\" title=\"This field must be filled\">*</a>
                          </label> 
                          <div class=\"col-md-4 inputGroupContainer\">
                              <div class=\"input-group\">
                                  <span class=\"input-group-addon\">
                                      <i class=\"fa fa-building\"></i>
                                  </span>
                                  <input type='text' name='office' class=\"form-control\" required placeholder='Office' value=\"".$office."\">
                              </div>
                          </div>
                      </div>
                      
                      <div class=\"form-group\">
                          <label class=\"col-md-4 control-label\"></label>
                          <div class=\"col-md-4\">
                              <button type='submit' class='btn btn-warning btn-block'>Edit Client</button>
                          </div>
                      </div>
              </fieldset>
          </form>
      </div>";
}
else{
    header("Location: ./login.php");
}
?>