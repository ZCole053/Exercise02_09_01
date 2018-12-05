<?php
//variables
$errors = 0;
$body = "";
$DBName = "professional_conference";
$hostname = "localhost";
$username = "adminer";
$passwd = "doubt-drink-37";
$DBConnect = false;

if(isset($_POST['submit'])){
    echo "we are in part 1";
    $DBConnect = mysqli_connect($hostname,$username,$passwd);
    if(!$DBConnect){
        ++$errors;
        $body .=  "<p>Unable to connect to database server error code: ". mysqli_connect_error(). 
        "</p>\n";
    }else{
        //selects the database
        $result = mysqli_select_db($DBConnect, $DBName);
        if(!$result){
            ++$errors;
            $body .=  "<p>Unable to connect to select the database \"$DBName\" error code: 
            ". mysqli_error($DBConnect). "</p>\n";
        }
    }

    
    if($errors == 0){
        echo "we are in the code";
        //creates data filled variables
        $first = stripslashes($_POST['first']);
        $last = stripslashes($_POST['last']);
        $email = stripslashes($_POST['email']);
        $password = stripslashes($_POST['password']);
        $Cname = stripslashes($_POST['Cname']);
        $Occup = stripslashes($_POST['Occup']);
        $Wyears = stripslashes($_POST['Wyears']);
        //creating variable for table name
        $tableName = "user_info";
        //if no errors check for duplicates
            if($errors == 0){
                $SQLstring = "SELECT count(*) FROM $tableName". 
                " WHERE email='$email'";
                $queryResult = mysqli_query($DBConnect,$SQLstring);
                if($queryResult){
                    $row = mysqli_fetch_row($queryResult);
                    if($row[0] > 0){
                        ++$errors;
                        $body .=  "<p>The email address entered (". htmlentities($email) . 
                        ") is already registered.</p>\n";
                    }
                }
            }
        $SQLstring = "INSERT INTO $tableName".
        " (Fname , Lname, email, password2, Companyname, occupation, Corkyears)".
        "  ALUES('$first','$last','$email', ".// purpose error
        "'". md5($password). "', '$Cname', '$Occup', '$Wyears' )";
        //$queryResult = mysqli_query($DBConnect, $SQLstring);
        if(!$queryResult){
            ++$errors;
            $body .=  "<p>Unable to connect to save you registration information 
            error code: ". mysqli_error($DBConnect). "</p>\n";
        }
    }
}

?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Validate Data</title>
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>

<?php
if($errors === 0 ){
?>
<h1>Form Complete</h1>
<p>Thank for filling out the form and signing up for the conference. If you would like to edit your information<br>
click <strong>Here</strong>.If you would like to select your seminar click <strong> <a href="SelectSeminar.php">
Here</a></strong>.</p>
<?php
}
//displays error code or debug code
echo $body;
?>

<?php
if($errors > 0 ){
?>
<h1>Form Incomplete</h1>
<p>An error has occured uploading your data please go back and retype answers in form.
Click <strong><a href="CompanyInfo.php">here </a></strong> to go back</p>


<?php
}
//displays error code or debug code
echo $body;
?>
</body>
</html>