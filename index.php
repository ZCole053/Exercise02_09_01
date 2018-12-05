
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Index</title>

    <script src="modernizr.custom.65897.js"></script>
</head>
<body>
<h1>Conference Sign up</h1>
<hr>
    <form action="CompanyInfo.php" method="post">
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
        <input type="reset" name="reset" value="Reset Sign up Form">
        <input type="submit" name="register" value="Register">
    </form>
    <hr>
<h1>Log In</h1>
<form action="SelectSeminar.php" method="post">
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