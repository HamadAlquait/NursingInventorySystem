<?php
include 'header.php';
?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.8/angular.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/angular-strap/v2.3.8/angular-strap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>
    <body>
<style>
    #message {
        display:none;
        background: #f1f1f1;
        color: #000;
        position: relative;
        padding: 20px;
        margin-top: 10px;
    }
    /*makes the success message go away*/
    #success_message{ display: none;}
    /* The message box is shown when the user clicks on the password field */
    #message {
        display:none;
        background: #f1f1f1;
        color: #000;
        position: relative;
        padding: 20px;
        margin-top: 10px;
    }

    #message p {
        padding: 10px 35px;
        font-size: 15px;
        text-align:center;
    }

    /* Add a green text color and a checkmark when the requirements are right */
    .valid {
        color: green;
    }

    .valid:before {
        position: relative;
        left: -35px;
        content: "✔";
    }

    /* Add a red text color and an "x" when the requirements are wrong */
    .invalid {
        color: red;
    }

    .invalid:before {
        position: relative;
        left: -35px;
        content: "✖";
    }
</style>
<script>
    $(document).ready(function() {
        $('#contact_form').bootstrapValidator({
            // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                email: {
                    validators: {
                        notEmpty: {
                            message: 'Please add an email address'
                        },
                        emailAddress: {
                            message: 'Please supply a valid email address'
                        }
                    }
                }
            }
        })
    });
</script>
<?php
    error_reporting(E_ALL ^ E_WARNING);
	include './dbh.php';
    echo "<head><Title>Signup</Title></head><div class=\"parent\"><button class='help' onclick=\"window.location.href='http://flowtime.be/wp-content/uploads/2016/01/Naamloosdocument.pdf'\">
        <i class='fa fa-question'></i></button></div>";

    $url ="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    if(strpos($url, 'error=empty') !== false){
        echo "<br><div class='alert alert-danger col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-xl-offset-2 
              col-xs-8 col-sm-8 col-md-8 col-xl-8' style='text-align: center'>
              Please fill out all fields.</div><br><br><br>";
        //echo "<br>&nbsp&nbspPlease fill out all fields.<br>";
    }
    elseif(strpos($url, 'error=username') !== false){
        echo "<br><div class='alert alert-danger col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-xl-offset-2 
              col-xs-8 col-sm-8 col-md-8 col-xl-8' style='text-align: center'>
              Username already in use.</div><br><br><br>";
        //echo "<br>&nbsp&nbspUsername already in use.<br>";
    }
    elseif(strpos($url, 'error=email') !== false){
        echo "<br><div class='alert alert-danger col-xs-offset-2 col-sm-offset-2 col-md-offset-2 col-xl-offset-2 
              col-xs-8 col-sm-8 col-md-8 col-xl-8' style='text-align: center'>
              E-mail address already in use.</div><br><br><br>";
        //echo "<br>&nbsp&nbspEmail Address already in use.<br>";
    }

    if(isset($_SESSION['id'])){
        echo "<br><class style=\"text-align:center;\"> 
        <div class=\"container\"><form class=\"well form-horizontal\" action='includes/signup.inc.php'
        method='POST' id=\"contact_form\"><fieldset><h2 align=\"center\">Create New User</h2><br/>
        
        <div class=\"form-group\"><label class=\"col-md-4 control-label\">First Name:
        <a style=\"color:red;\" title=\"This field must be filled\">*</a></label> 
        <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user\"></i></span>
        <input name='first' required placeholder='First Name' class=\"form-control\" type=\"text\"></div></div></div>
        
        <div class=\"form-group\"><label class=\"col-md-4 control-label\" >Last Name:
        <a style=\"color:red;\" title=\"This field must be filled\">*</a></label>
        <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user\"></i></span>
        <input name='last' required placeholder='Last Name' class='form-control' type='text'></div></div></div>
        
        <div class=\"form-group\"><label class=\"col-md-4 control-label\">E-Mail:
        <a style=\"color:red;\" title=\"This field must be filled\">*</a></label> 
        <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-envelope\"></i></span>
        <input name='email' required placeholder='E-Mail Address' class='form-control' type='email'></div></div></div>
        
        <div class=\"form-group\"><label class=\"col-md-4 control-label\">Account Type:
        <a style=\"color:red;\" title=\"This field must be filled\">*</a></label>
        <div class=\"col-md-4 selectContainer\"><div class=\"input-group\">
        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-list\"></i></span>
        <select required class=\"form-control selectpicker\" name='acctType'>
        <option value='Standard User'>Standard User</option>";
        $currentID = $_SESSION['id'];
        $sql = "SELECT acctType FROM users WHERE id='$currentID'";
        $result = mysqli_query($conn, $sql);
        $row = $result->fetch_assoc();
        $acctType = $row['acctType'];
        if($acctType == "Admin" || $acctType == "Super Admin"){
            echo "<option value='Admin'>Admin</option>";
        }
        if($acctType == "Super Admin"){
            echo "<option value='Super Admin'>Super Admin</option>";
        }

        echo "</select></div></div></div>
        <div class=\"form-group\"><label class=\"col-md-4 control-label\">Username:
        <a style=\"color:red;\" title=\"This field must be filled\">*</a></label>  
        <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-user\"></i></span>
        <input type='text' required class='form-control' placeholder='Username' name='uid'></div></div></div>
        
        <div class=\"form-group\"><label class=\"col-md-4 control-label\" for=\"psw\">Password:
        <a style=\"color:red;\" title=\"This field must be filled\">*</a></label> 
        <div class=\"col-md-4 inputGroupContainer\"><div class=\"input-group\">
        <span class=\"input-group-addon\"><i class=\"glyphicon glyphicon-lock\"></i></span>
        <input name='pwd' placeholder='Password' class='form-control' type='password' id='pwd'
         pattern=\"(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}\" 
         title=\"Must contain at least one number and one uppercase and lowercase letter,
          and at least 8 or more characters\" required></div></div></div><div id=\"message\">
        <h4>Password must contain the following:</h4><p id=\"letter\" class=\"invalid\">A <b>lowercase</b> letter</p>
        <p id=\"capital\" class=\"invalid\">A <b>capital (uppercase)</b> letter</p>
        <p id=\"number\" class=\"invalid\">A <b>number</b></p>
        <p id=\"length\" class=\"invalid\">Minimum <b>8 characters</b></p></div>
        
        <div class=\"form-group\"><label class=\"col-md-4 control-label\"></label><div class=\"col-md-4\">
        <button type='submit' class='btn btn-warning btn-block'>Create User</button></div></div></fieldset></form></div>";
    }else {
        header("Location: ./login.php");
    }
?>