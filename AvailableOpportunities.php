<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Available Opportunities</title>
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h1>College Internship</h1>
<h2>Available Opportunities</h2>
    <?php

    //got here through post,get,or cookies
    if(isset($_REQUEST['internID'])){
        $internID = $_REQUEST['internID'];
    }else{
        $internID = -1;
    }
    //debug
    echo "\$internID: $internID\n";
     //declaring variables
     $errors = 0;
     $hostname = "Localhost";
     $username = "adminer";
     $passwd = "doubt-drink-37";
     $DBConnect = false;
     $DBName = "internships1";
 
     //opening connection 
     if($errors == 0){
         $DBConnect = mysqli_connect($hostname,$username,$passwd);
         if(!$DBConnect){
             ++$errors;
             echo "<p>Unable to connect to database server error code: ". mysqli_connect_error(). 
             "</p>\n";
         }else{
             //command works or doesn't
             $result = mysqli_select_db($DBConnect,$DBName);
             if(!$result){
                 //incriments error count
                 ++$errors;
                 echo "<p>Unable to connect to select the database \"$DBName\" error code: 
                 ". mysqli_error($DBConnect). "</p>\n";
             }
         }
     }
     //setting table name
     $tableName = "interns";
     //if no errors
     if($errors == 0){
         //searches for field with this information
        $SQLstring = "SELECT * FROM $tableName".
        " WHERE internID='$internID'";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        //if it does not work it will send an error
        if(!$queryResult){
            ++$errors;
            echo "<p>Unable to esecute the query, error code: ".
            mysqli_errno($DBConnect). ": ". 
            mysqli_error($DBConnect). "</p>\n";
        }else {
            //if not rows is returned then a error is sent to the user
            if(mysqli_num_rows($queryResult) == 0){
                ++$errors;
                echo "<p>Invalid Intern ID!</p>\n";
            }
        }
     }

     if($errors == 0){
        $row = mysqli_fetch_assoc($queryResult);
        $internName = $row['first']. " ". $row['last'];
     }else{
         $internName = "";
     }

     echo "\$internName: $internName";//debug

     //closes database
     if($DBConnect){
        echo "<p>Closing database \"$DBName\" connection.</p>\n";//debug
        mysqli_close($DBConnect);
     }
    ?>
</body>
</html>