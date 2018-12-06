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





if($DBConnect){
    $body .= "<p>Closing database \"$DBName\" connection.</p>\n";//debug 
    mysqli_close($DBConnect); 
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
<h1>Available Seminars</h1>
<hr>
<P><em>(Please select you top 2 seminars.)</em></P><br>

<?php
 echo $body;
?>

    
</body>
</html>