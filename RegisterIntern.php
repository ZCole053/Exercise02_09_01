<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Page Title</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
</head>
<body>
    <?php
    $errors = 0;
    $email = "";
    if(empty($_POST['email'])){
        ++$errors;
        echo "<p>You need to enter an e-mail address.</p>\n";
    }else{
        $email = stripslashes($_POST['email']);
        if(preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[w-]+)*(\.[A-Za-z]{2,})$/i",$email) == 0){
            ++$errors;
            echo "<p>You need to enter a valid e-mail address.</p>\n";
            $email = "";
        }
    }
    if(empty($_POST['password'])){
        ++$errors;
        echo "<p>You need to enter an password.</p>\n";
    }else{
        $password = stripslashes($_POST['password']);
    }
    if(empty($_POST['password2'])){
        ++$errors;
        echo "<p>You need to enter a confirmation password.</p>\n";
    }else{
        $password2 = stripslashes($_POST['password2']);
    }
    if(!empty($password) && !empty($password2)){
        if(strlen($password) < 6){
            ++$errors;
            echo "<p>The password is too short.</p>\n";
            $password = "";
            $password2 ="";
        }
        if($password <> $password2){
            ++$errors;
            echo "<p>The password do not match.</p>\n";
            $password = "";
            $password2 ="";
        }
    }
    if($errors > 0){
        echo "<p>Please use your browsers's BACK button to return to the form and fix the errors 
        indicated.</p>\n ";
    }
    

    ?>
</body>
</html>