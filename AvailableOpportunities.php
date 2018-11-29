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

    //picks up cookie if it has been set if not it is set to empty
    if(isset($_COOKIE['LastRequestDate'])){
        $lastRequestDate = urldecode($_COOKIE['LastRequestDate']);
    }else{
        $lastRequestDate = "";
    }

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

     //Gets the count of the id number that is the same and data approved has 
     //something in it.
     $tableName = "assigned_opportunities";
     if($errors == 0){
        $SQLstring = "SELECT COUNT(opportunityID)". 
            " FROM $tableName". 
            " WHERE internID='$internID'".
            " AND dateApproved IS NOT NULL";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) > 0){
            //puts everything in query into an indexed array
            $row = mysqli_fetch_row($queryResult);
            $approvedOpportunities = $row[0];
            //frees up query space
            mysqli_free_result($queryResult);
        }
        //array to hold results
        $selectedOpportunities = array();
        $SQLstring = "SELECT opportunityID FROM $tableName".
        " WHERE internID='$internID'";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        //could have nothing or something
        if(mysqli_num_rows($queryResult) > 0){
            while(($row = mysqli_fetch_row($queryResult)) != false){
                $selectedOpportunities[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }
        //if it is assigned to any intern
        $assignedOpportunities = array();
        $SQLstring = "SELECT opportunityID FROM $tableName".
        " WHERE dateApproved IS NOT NULL";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) > 0){
            while(($row = mysqli_fetch_row($queryResult)) != false){
                $assignedOpportunities[] = $row[0];
            }
            mysqli_free_result($queryResult);
        }
        //change table name
        $tableName = "opportunities";
        $opportunities = array();
        $SQLstring = "SELECT opportunityID, company, city,".
        " startDate, endDate, position, description".
        " From $tableName";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) > 0){
            while(($row = mysqli_fetch_assoc($queryResult)) != false){
                $opportunities[] = $row;
            }
            mysqli_free_result($queryResult);
        }
    }

     //closes database
     if($DBConnect){
        echo "<p>Closing database \"$DBName\" connection.</p>\n";//debug
        mysqli_close($DBConnect);
     }

     if(!empty($lastRequestDate)){
        echo "<p>You last requested an intership". 
        " opportunity on $lastRequestDate.</p>\n";
     }

//code to display table to the user of what opportunties are available
     echo "<table border='1' width='100%>\n'";
     echo "<tr>\n";
     echo "<th style='background-color: cyan'>Company</th>\n";
     echo "<th style='background-color: cyan'>City</th>\n";
     echo "<th style='background-color: cyan'>Start Date</th>\n";
     echo "<th style='background-color: cyan'>End Date</th>\n";
     echo "<th style='background-color: cyan'>Position</th>\n";
     echo "<th style='background-color: cyan'>Description</th>\n";
     echo "<th style='background-color: cyan'>Status</th>\n";
     echo "</tr>\n";
     //for each the array into td rows
     foreach ($opportunities as $opportunity) {
         if(!in_array($opportunity['opportunityID'],$assignedOpportunities)){
            echo "<tr>\n";
            echo "<td>". htmlentities($opportunity['company']). "</td>\n";
            echo "<td>". htmlentities($opportunity['city']). "</td>\n";
            echo "<td>". htmlentities($opportunity['startDate']). "</td>\n";
            echo "<td>". htmlentities($opportunity['endDate']). "</td>\n";
            echo "<td>". htmlentities($opportunity['position']). "</td>\n";
            echo "<td>". htmlentities($opportunity['description']). "</td>\n";
            echo "<td>\n";
            if(in_array($opportunity['opportunityID'], $selectedOpportunities)){
                echo "Selected";
                //already selected
            }elseif($approvedOpportunities > 0){
                echo "Open";
            }else{
                //who selected it 
                echo "<a href='RequestOpportunity.php?".
                "internID=$internID&".
                "opportunityID=". 
                $opportunity['opportunityID']. 
                "'>Available</a>";
            }
            echo "</td>\n";
            echo "</tr>\n";
         }
     }
     echo "</table>\n";
     echo "<p><a href='InternLogin.php'>Log Out</a></p>\n";

    ?>
</body>
</html>