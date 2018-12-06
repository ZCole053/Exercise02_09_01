<?php

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
            $body .= "<p>A valid e-mail is required.</p>\n";
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
            $password2 = "";
        }
        //checks to see if passwords match
        if($password <> $password2){
            ++$errors;
            $body .=  "<p>The password do not match.</p>\n";
            $password = "";
            $password2 = "";
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
                $body .= "<p>Could not select database, attempting to create database now.</p>\n";
                $sql = "CREATE DATABASE $DBName";
                if(mysqli_query($DBConnect,$sql)){
                    $body .= "<p>Succesfully created the database.</p>\n";
                    $selected = mysqli_select_db($DBConnect,$DBName);
                }else{
                    $body .= "<p>Could not create database error:". mysqli_error($DBConnect). " occured</p>\n";
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


    if($errors == 0){
        $body .= "Setting cookie";
        setcookie("first",$_POST['first'],time()+60*60*24*7);
        setcookie("last",$_POST['last'],time()+60*60*24*7);
        setcookie("email",$_POST['email'],time()+60*60*24*7);
        setcookie("password",$_POST['password'],time()+60*60*24*7);
    }



?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Company Info</title>
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
    <form action="ValidateData.php" method="post" >
    <p>Enter your Company name:
        <input type="text" name="Cname" >
    </p>
    <p>Enter Position held in company:
        <input type="text" name="Occup" >
    </p>
    <p> Number of years at the company:
        <input type="number" name="Wyears" >
    </p>
    <input type="reset" name="reset" value="Reset Form">
    <input type="submit" name="submit" value="Register">
    </form>

<?php
}

//creating table variable
$tablename = "user_info";
//creating table if table is not already made
if($errors == 0){
    $selected = false;
    $sql =  "SHOW TABLES LIKE '$tablename'";
    $result = mysqli_query($DBConnect,$sql);
    if(mysqli_num_rows($result) === 0){
        echo "<p>$tablename table does not exist, attempting to create a table now.</p>\n";//error message
        $sql = "CREATE TABLE $tablename(UserID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
         Fname VARCHAR(40), Lname VARCHAR(40), email VARCHAR(40), password2 VARCHAR(40),
         Companyname VARCHAR(40), occupation VARCHAR(40), Corkyears SMALLINT)";
         $result = mysqli_query($DBConnect,$sql);
         if($result === false){
          $selected = false;
          echo "<p>Unable to create the table $tablename.</p>";//error message
         }
         echo "<p>$tablename was successfully created</p>\n";//error message
    }
}

if($DBConnect){
    echo "<p>Closing database \"$DBName\" connection.</p>\n";//debug 
    mysqli_close($DBConnect); 
}

?>
</body>
</html>