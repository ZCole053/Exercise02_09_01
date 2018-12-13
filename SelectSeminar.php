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

 
    //using $tableName to switch between tables
    $tablename = "user_info";
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


    $tableName = "assigned_seminars";
    if($errors == 0){
        $SQLstring = "SELECT COUNT(SeminarID)". 
            " FROM $tableName". 
            " WHERE UserID='$userID'".
            " AND dateApproved IS NOT NULL";
            $queryResult = mysqli_query($DBConnect,$SQLstring);
            if(mysqli_num_rows($queryResult) > 0){
                $row = mysqli_fetch_row($queryResult);
                $approvedOpportunities = $row[0];
                mysqli_free_result($queryResult);
            }
        
        // gets the user number and puts it in a array
        $selectedSeminar = array(); 
        $SQLstring = "SELECT SeminarID FROM $tableName". 
        " WHERE UserID='$userID'"; 
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
        $SQLstring = "SELECT SeminarID FROM $tableName".
        " WHERE dateApproved IS NOT NULL";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) > 0){
            while(($row = mysqli_fetch_row($queryResult)) != false){
                $assignedSeminar[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }

    //change table name again
    $tableName = "seminar_info";
    $opportunities = array();
        $SQLstring = "SELECT SeminarID, Seminar,".
        " StartTime, EndTime, JobType, Description".
        " From $tableName";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) > 0){
            while(($row = mysqli_fetch_assoc($queryResult)) != false){
                $opportunities[] = $row;
            }
            mysqli_free_result($queryResult);
        }
    }
    


    //test not required
    if($userID < 0){
        ++$errors;
        $body .= "<p>Invalid Intern ID!</p>\n";
    }

    if($DBConnect){
        mysqli_close($DBConnect); 
    }

//sets the
    if($errors == 0){
        $body .= "Setting cookie";
        setcookie("UserID",$userID,time()+60*60*24*7);
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
echo "<th>Job type</th>\n";
echo "<th>Description</th>\n";
echo "<th>Status</th>\n";
echo "</tr>\n"; 
foreach ($opportunities as $opportunity) {
    if(!in_array($opportunity['SeminarID'],$assignedSeminar)){
        echo "<tr>\n";
            echo "<td>". htmlentities($opportunity['Seminar']). "</td>\n";
            echo "<td>". htmlentities($opportunity['StartTime']). "</td>\n";
            echo "<td>". htmlentities($opportunity['EndTime']). "</td>\n";
            echo "<td>". htmlentities($opportunity['JobType']). "</td>\n";
            echo "<td>". htmlentities($opportunity['Description']). "</td>\n";
            echo "<td>\n";
            if(in_array($opportunity['SeminarID'], $selectedSeminar)){
                echo "Selected";
                //already selected
            }elseif($approvedOpportunities > 0){
                echo "Open";
            }else{ 
                //who selected it  
                echo "<a href='ConfirmSelction.php?". //add next page
                "UserID=$userID&". 
                "SeminarID=".  
                $opportunity['SeminarID'].  
                "'>Available</a>"; 
            } 
            echo "</td>\n"; 
            echo "</tr>\n";
    }
}

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