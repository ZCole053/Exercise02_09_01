<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h1>College internships</h1>
<h2>Intern Registration</h2>
    <?php
    //variables
    $errors = 0;
    $email = "";
    $hostname = "localhost";
    $username = "adminer";
    $passwd = "doubt-drink-37";
    $DBConnect = false;
    $DBName = "internships1";

    

    //validating email
    if(empty($_POST['email'])){
        ++$errors;
        echo "<p>You need to enter an e-mail address.</p>\n";
    }else{
        $email = stripslashes($_POST['email']);
        //regular expression to validate email
        if(preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[w-]+)*(\.[A-Za-z]{2,})$/i",$email) == 0){
            ++$errors;
            echo "<p>You need to enter a valid e-mail address.</p>\n";
            $email = "";
        }
    }
    //validate email
    if(empty($_POST['password'])){
        ++$errors;
        echo "<p>You need to enter an password.</p>\n";
    }else{
        //cleans up password input
        $password = stripslashes($_POST['password']);
    }//validate password
    if(empty($_POST['password2'])){
        ++$errors;
        echo "<p>You need to enter a confirmation password.</p>\n";
    }else{
        $password2 = stripslashes($_POST['password2']);
    }
    if(!empty($password) && !empty($password2)){
        //checks to see the length of the password
        if(strlen($password) < 6){
            ++$errors;
            echo "<p>The password is too short.</p>\n";
            $password = "";
            $password2 ="";
        }
        //checks to see if passwords match
        if($password <> $password2){
            ++$errors;
            echo "<p>The password do not match.</p>\n";
            $password = "";
            $password2 ="";
        }
    }

    //connecting to a server
    if($errors == 0){
        $DBConnect = mysqli_connect($hostname,$username,$passwd);
        if(!$DBConnect){
            ++$errors;
            echo "<p>Unable to connect to database server error code: ". mysqli_connect_error(). 
            "</p>\n";
        }else{
            //selects the database
            $result = mysqli_select_db($DBConnect, $DBName);
            if(!$result){
                //incriments error count
                ++$errors;
                echo "<p>Unable to connect to select the database \"$DBName\" error code: 
                ". mysqli_error($DBConnect). "</p>\n";
            }
            //created variable for intern table
            $tableName = "interns";
            if($errors == 0){
                $SQLstring = "SELECT count(*) FROM $tableName". 
                " WHERE email='$email'";
                $queryResult = mysqli_query($DBConnect,$SQLstring);
                if($queryResult){
                    $row = mysqli_fetch_row($queryResult);
                    if($row[0] > 0){
                        ++$errors;
                        echo "<p>The email address entered (". htmlentities($email) . 
                        ") is already registered.</p>\n";
                    }
                }
            }
            //if no errors execute
            if($errors == 0){
                $first = stripslashes($_POST['first']);
                $last = stripslashes($_POST['last']);
                $SQLstring = "INSERT INTO $tableName".
                " (first , last, email, password_md5)".
                "  VALUES('$first','$last','$email', ".
                "'". md5($password). "')";
                $queryResult = mysqli_query($DBConnect, $SQLstring);
                if(!$queryResult){
                    ++$errors;
                    echo "<p>Unable to connect to save you registration information 
                    error code: ". mysqli_error($DBConnect). "</p>\n";
                }else{
                    $internID = mysqli_insert_id($DBConnect);
                }
            }
    }
}
    if($errors == 0){
        $internName = $first. " ". $last;
        echo "<p>Thank you, $internName. ";
        echo "Your new Intern ID is <strong>". 
        $internID . "</strong>.</p>\n";
    }

    if($DBConnect){
        echo "<p>Closing database \"$DBName\" connection.</p>\n";//debug
        mysqli_close($DBConnect);
        echo "<form action='AvailableOpportunities.php' method='post'>\n";
        echo "<input type='hidden' name='internID' value='$internID'>\n";
        echo "<input type='submit' name='submit' value='View Available Opportunities'>\n";
        echo "</form>";
    }

    //code to tell user to go back if their is errors
    if($errors > 0){
        echo "<p>Please use your browsers's BACK button to return to the form and fix the errors 
        indicated.</p>\n ";
    }
    

    ?>
</body>
</html>