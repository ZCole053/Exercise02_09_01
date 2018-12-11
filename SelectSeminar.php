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
            $body .= "<p>Unable to connect to database server error code: ". mysqli_connect_error(). 
            "</p>\n";
        }else{
            $result = mysqli_select_db($DBConnect,$DBName);
            if(!$result){
                ++$errors;
                $body .= "<p>Unable to connect to select the database \"$DBName\" error code: 
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
            $body .= "<p>The email address/password combination entered is not valid.</p>\n";
        }else{
            $row = mysqli_fetch_assoc($queryResult); 
            $userID= $row['UserID']; 
            $body .= "\$usernameID: $userID ";//debug
            $userName = $row['Fname']. " ". $row['Lname']; 
            mysqli_free_result($queryResult); 
            $body .= "<p>Welcome back, $userName!</p>\n"; 
        }
    }


    if($errors == 0){
        $SQLstring = "SELECT COUNT(UserID)". 
            " FROM $tableName". 
            " WHERE UserID ='$userID'";
            $queryResult = mysqli_query($DBConnect,$SQLstring);
            if(mysqli_num_rows($queryResult) > 0){
                $row = mysqli_fetch_row($queryResult);
                $approvedOpportunities = $row[0];
                mysqli_free_result($queryResult);
            }
        
        // gets the user number and puts it in a array
        $selectedSeminar = array(); 
        $SQLstring = "SELECT UserID FROM $tableName". 
        " WHERE SelectSeminar IS NOT NULL"; 
        //could hav nothing or something 
        $queryResult = mysqli_query($DBConnect,$SQLstring); 
        if(mysqli_num_rows($queryResult) > 0){ 
            while(($row = mysqli_fetch_row($queryResult)) != false){ 
                $selectedSeminar[] = $row[0]; 
            } 
            mysqli_free_result($queryResult); 
        } 
// assigns the array 
        $assignedSeminar = array();
        $SQLstring = "SELECT UserID FROM $tableName".
        " WHERE SelectSeminar IS NOT NULL";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) > 0){
            while(($row = mysqli_fetch_row($queryResult)) != false){
                $assignedSeminar[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }
    }


    //test not required
    if($userID < 0){
        ++$errors;
        $body .= "<p>Invalid Intern ID!</p>\n";
    }


    //creating new table
    $tablename = "seminar_info";
    if($errors == 0){
        $selected = false;
        $sql =  "SHOW TABLES LIKE '$tablename'";
        $result = mysqli_query($DBConnect,$sql);
        if(mysqli_num_rows($result) === 0){
            $body .= "<p>$tablename table does not exist, attempting to create a table now.</p>\n";//error message
            $sql = "CREATE TABLE $tablename(SeminarID SMALLINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            Seminar VARCHAR(40), StartTime SMALLINT, EndTime SMALLINT,
            JobType VARCHAR(40), Description VARCHAR(250))";
             $result = mysqli_query($DBConnect,$sql);
             if($result === false){
              $selected = false;
              $body .= "<p>Unable to create the table $tablename.</p>";//error message
             }
             $body .= "<p>$tablename was successfully created</p>\n";//error message
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

echo "<table border='1' width='100%>\n'"; 
echo "<tr>\n"; 
echo "<th>Seminar</th>\n";
echo "<th>Start Time</th>\n";
echo "<th>End Time</th>\n";
echo "<th>End Date</th>\n";
echo "<th>Job type</th>\n";
echo "<th>Description</th>\n";
echo "<th>Status</th>\n";
echo "</tr>\n"; 

echo "</table>\n"; 
echo "<p><a href='index.php'>Log Out</a></p>\n"; 
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