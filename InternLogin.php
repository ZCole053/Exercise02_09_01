<?php
    session_start();
    echo "Session id:". session_id(). "<br>\n";

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>College internships</title>
    <script src="modernizr.custom.65897.js"></script>
</head>

<body>
    <h1>College internships</h1>
    <h2>Register / Login</h2>
    <p>New interns, please complete the top form to register as a user. Returning users please
        complete the second form to login.</p>

    <h3>New Intern Registration</h3>
    <form action="RegisterIntern.php?PHPSESSID=<?php echo session_id(); ?>" method="post">
        <p>Enter your name: First
            <input type="text" name="first" >
            Last: 
            <input type="text" name="last">
        </p>
        <p>
            Enter your e-mail address:
            <input type="text" name="email" >
        </p>
        <p>
            Enter a password for your account:
            <input type="password" name="password">
        </p>
        <p>
            Confirm your password:
            <input type="password" name="password2">
        </p>
        <p><em>(Passwords are case-sensitive and must be at least 6 characters long.)</em></p>
        <input type="reset" name="reset" value="Reset Registration Form">
        <input type="submit" name="register" value="Register">
    </form>

    <h3>Returning Inter Login</h3>
    <form action="VerifyLogin.php?PHPSESSID=<?php echo session_id(); ?>" method="post">
        <p>
            Enter your e-mail address:
            <input type="text" name="email" >
        </p>
        <p>
            Enter your password:
            <input type="password" name="password">
        </p>
        <p><em>(Passwords are case-sensitive and must be at least 6 characters long.)</em></p>
        <input type="reset" name="reset" value="Reset Login Form">
        <input type="submit" name="Login" value="Login">
    </form>
</body>

</html>