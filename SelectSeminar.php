<?php
   //declaring variables
   $body = "";
   $errors = 0;
   $hostname = "Localhost";
   $username = "adminer";
   $passwd = "doubt-drink-37";
   $DBConnect = false;
   $DBName = "professional_conference";
   $tableName = "user_info";

 //opening connection 

 if(isset($_REQUEST['Login'])){
    if($errors == 0){
        $DBConnect = mysqli_connect($hostname,$username,$passwd);
        if(!$DBConnect){
            ++$errors;
            echo "<p>Unable to connect to database server error code: ". mysqli_connect_error(). 
            "</p>\n";
        }else{
            $result = mysqli_select_db($DBConnect,$DBName);
            if(!$result){
                ++$errors;
                echo "<p>Unable to connect to select the database \"$DBName\" error code: 
                ". mysqli_error($DBConnect). "</p>\n";
            }
        }
    }


    if($errors == 0){
        $SQLstring = "SELECT UserID, Fname, Lname".
        " FROM $tableName" .
        " WHERE email='". stripslashes($_REQUEST['email']).
        "' AND password2='". md5(stripslashes($_REQUEST['password'])). "'";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) == 0){
            ++$errors;
            echo "<p>The email address/password combination entered is not valid.</p>\n";
        }else{
            $row = mysqli_fetch_assoc($queryResult); 
            $userID= $row['UserID']; 
            $userName = $row['Fname']. " ". $row['Lname']; 
            mysqli_free_result($queryResult); 
            echo "<p>Welcome back, $userName!</p>\n"; 
        }
    }

    if($DBConnect){
        $body .= "<p>Closing database \"$DBName\" connection.</p>\n";//debug 
        mysqli_close($DBConnect); 
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Select Seminar</title>
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>

<?php
//displays table
if($errors == 0){
?>
<h1>Available Seminars</h1>
<hr>
<P><em>(Please select you top 2 seminars.)</em></P><br>

<?php
 echo $body;
}

//displays error
 if($errors > 0){

?>
<h1>Login Error Found</h1>
<hr>
<p>Login failed the email and password does not exist.<br> <a href="index.php">Click here to go back.</a></p>
<?php
    echo $body;
 }
?>

<?php

?>

    
</body>
</html>