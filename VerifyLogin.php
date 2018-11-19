<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Verify Intern Login</title>
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
    <h1>College Internship</h1>
    <h2>Verify Intern Login</h2>

    <?php
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

    //variable for the table name
    $tableName = "interns";
    if($errors == 0){
        $SQLstring = "SELECT internID, first, last".
        " FROM $tableName" .
        " WHERE email='". stripslashes($_POST['email']).
        "' AND password_md5='". md5(stripslashes($_POST['password'])). "'";
        $queryResult = mysqli_query($DBConnect,$SQLstring);
        if(mysqli_num_rows($queryResult) == 0){
            ++$errors;
            echo "<p>The email address/password combination entered is not valid.</p>\n";
        }else{
            $row = mysqli_fetch_assoc($queryResult);
            $internID = $row['internID'];
            $internName = $row['first']. " ". $row['last'];
            mysqli_free_result($queryResult);
            echo "<p>Welcome back, $internName!</p>\n";
        }
    }

    //if there is a database connection
    if($DBConnect){
        echo "<p>Closing database \"$DBName\" connection.</p>\n";//debug
        mysqli_close($DBConnect);
        echo "<form action='AvailableOpportunities.php' method='post'>\n";
        echo "<input type='hidden' name='internID' value='$internID'>\n";
        echo "<input type='submit' name='submit' value='View Available Opportunities'>\n";
        echo "</form>";
    }

    //display for user errors
    if($errors>0){
        echo "<p>Please use your browsers's BACK button to return to the form and fix the errors 
        indicated.</p>\n ";
    }
    ?>

</body>
</html>