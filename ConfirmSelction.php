<?php
//defining variablaes
$errors = 0;
$body = "";
$hostname = "Localhost";
$username = "adminer";
$passwd = "doubt-drink-37";
$DBConnect = false;
$DBName = "professional_conference";


//seeing how the user got to this page
if(!isset($_COOKIE['UserID'])){
    ++$errors;
    $body .= "<p>You have not logged in or registered.". 
    " Please return to the ". "<a href='index.php'>".
    "Refistration/ Login Page</a></p>";
}
if($errors == 0){
    if(isset($_COOKIE['SeminarID'])){
        $SeminarID = $_COOKIE['SeminarID'];
    }else {
        ++$errors;
        $body .= "<p>You have not Selected an opportunity.". 
        " Please return to the ". "<a href='SelectSeminar.php?>".
        "Refistration/ Login Page</a></p>";
    }
}


if($errors == 0){
    $DBConnect = mysqli_connect($hostname,$username,$passwd);
    if(!$DBConnect){
        ++$errors;
        $body .= "<p>Unable to connect to database server error code: ". mysqli_connect_error(). 
        "</p>\n";
    }else{
        //command works or doesn't
        $result = mysqli_select_db($DBConnect,$DBName);
        if(!$result){
            //incriments error count
            ++$errors;
            $body .= "<p>Unable to connect to select the database \"$DBName\" error code: 
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
    <title>Confirm Selection</title>
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h1>Saved Selection</h1>

<?php
 //displays echos bellow html
echo $body;

echo "<pre>\n";
print_r($_COOKIE);
echo "</pre>\n";
?>

</body>
</html>