<?php
//continuing active session
session_start();

    //variables
    $body = "";
    $errors = 0;
    $email = "";
    $DBName = "professional_conference";
    $hostname = "localhost";
    $username = "adminer";
    $passwd = "doubt-drink-37";
    $DBConnect = false;

    //checking the email field to validate
    if(empty($_POST['email'])){
        ++$errors;
        $body .= "<p>A email is required</p>\n";
    }else{
        $email = stripslashes($_POST['email']);
        //regular expression to validate email
        if(preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[w-]+)*(\.[A-Za-z]{2,})$/i",$email) == 0){
            ++$errors;
            $body .=  "<p>A valid e-mail is required.</p>\n";
            $email = "";
        }
    }

    //validating passwords
    if(empty($_POST['password'])){
        ++$errors;
        $body .=  "<p>You need to enter an password.</p>\n";
    }else{
        $password = stripslashes($_POST['password']);
    }
    //validating confirmed password
    if(empty($_POST['password2'])){
        ++$errors;
        $body .=  "<p>You need to enter a comfirmed password.</p>\n";
    }else{
        $password2 = stripslashes($_POST['password2']);
    }


    //validating password compatability
    if(!empty($password) && !empty($password2)){
        //checks to see the length of the password
        if(strlen($password) < 6){
            ++$errors;
            $body .=  "<p>The password is too short.</p>\n";
            $password = "";
            $password2 ="";
        }
        //checks to see if passwords match
        if($password <> $password2){
            ++$errors;
            $body .=  "<p>The password do not match.</p>\n";
            $password = "";
            $password2 ="";
        }
    }
    
    //connects to the data base and creates database if not made
    if($errors == 0){
        $DBConnect = mysqli_connect($hostname,$username,$passwd);
        if(!$DBConnect){
            $body .=  "<p>Connection Error: ". mysqli_connect_error(). "</p>\n";
        }else{
            $selected = mysqli_select_db($DBConnect,$DBName);
            if(!$selected){
                echo "<p>Could not select database, attempting to create database now.</p>\n";
                $sql = "CREATE DATABASE $DBName";
                if(mysqli_query($DBConnect,$sql)){
                    echo "<p>Succesfully created the database.</p>\n";
                    $selected = mysqli_select_db($DBConnect,$DBName);
                }else{
                    echo "<p>Could not create database error:". mysqli_error($DBConnect). " occured</p>\n";
                }
            }
        }
    }



    //displays if their is any error
    if($errors > 0){
        $body .=  "<p>Errors have occured when signing up please click the link and go ". 
        " <a href='index.php'><strong>Back</strong></a>" 
        ." and fix the sign up errors.</p>\n ";
    }

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Page Title</title>
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<?php

//displays error title
if($errors > 0){
?>
<h1>Form Errors found</h1>
<hr>
<?php
}
?>

    <?php
    //displays error code or debug code
    echo $body;
    ?>

<!-- displays form -->
<?php
if($errors == 0){
?>
<h1>Company Information</h1>
<hr>
    <form action="SelectSeminar.php" method="post" >
    <p>Enter your Company name:
        <input type="text" name="Cname" >
    </p>
    <p>Enter Position held in company:
        <input type="text" name="Occup" >
    </p>
    </form>

<?php
}
?>
</body>
</html>