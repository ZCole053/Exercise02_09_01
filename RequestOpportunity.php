<?php
session_start();
$body = "";
$errors = 0;
//$internID = 0;

// if(isset($_GET['internID'])){
//     $internID = $_GET['internID'];
// }
if(!isset($_SESSION['internID'])){
    ++$errors;
    $body .= "<p>You have not logged in or registered.". 
    " Please return to the ". "<a href='InternLogin.php'>".
    "Refistration/ Login Page</a></p>";
}
if($errors == 0){
    if(isset($_GET['opportunityID'])){
        $opportunityID = $_GET['opportunityID'];
    }else {
        ++$errors;
        $body .= "<p>You have not Selected an opportunity.". 
        " Please return to the ". "<a href='AvailableOpportunities.php?".
        "PHPSESSID=". session_id(). "'>".
        "Refistration/ Login Page</a></p>";
    }
}


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
//gets a nicely displayed date
$displayDate = date("l, F j, Y, g:i A");
$body .= "\$displayDate: $displayDate<br>";//debug
$dbDate = date("Y-m-d H:i:s");
$body .= "\$dbDate: $dbDate<br>";//debug

if($errors == 0){
    $tableName = "assigned_opportunities";
    $SQLstring = "INSERT INTO $tableName".
    " (opportunityID, internID, dateSelected)".
    " VALUES($opportunityID,". 
    $_SESSION['internID']. ",'$dbDate')";
    $queryResult = mysqli_query($DBConnect,$SQLstring);
    if(!$queryResult){
        ++$errors;
        $body .= "<p>Unable to execute the query, ".
        "error code: ". mysqli_errno($DBConnect). 
        ": ". mysqli_error($DBConnect). "</p>\n";
    }else{
        $body .= "<p>Your results for opportunity #".
        " $opportunityID have been entered on".
        " $displayDate.</p>\n";
    }
}

if($DBConnect){
    $body .= "<p>Closing database \"$DBName\" connection.</p>\n";//debug
    mysqli_close($DBConnect);
}

if($_SESSION['internID'] > 0){
    $body .= "<p>Return to the ". "<a href='AvailableOpportunities.php?".
    "PHPSESSID=". session_id(). "'>Available Opportunities</a> page.</p>\n";
}else{
    $body .= "<p>Please ". "<a href='InternLogin.php'>".
    "Register or Log In". "</a> to use this page.</p>\n";
}

if($errors == 0){
    echo "Setting cookie<br>";
    setcookie("LastRequestDate",
    urlencode($displayDate),time()+60*60*24*7);
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Request Opportunity</title>
    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h1>College Internship</h1>
<h2>Opportunity Requested</h2>
    <?php
    //displays echos bellow html
    echo $body;
    ?>
</body>
</html>