<?php
session_start();
include("db/config.php");

if(isset($_POST['login'])){

    $username=$_POST['username'];
    $password=$_POST['password'];

    $sql="SELECT * FROM users
          WHERE username='$username'
          AND password='$password'";

    $result=$conn->query($sql);

    if($result->num_rows>0){

        $_SESSION['user']=$username;

        header("Location: index.php");
        exit();

    } else {
        $error="Invalid Username or Password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body>

<h1>Blood Bank Login</h1>

<form method="POST">

<p>Username:</p>
<input type="text" name="username">

<p>Password:</p>
<input type="password" name="password">

<br><br>

<button type="submit" name="login">Login</button>

</form>

<?php if(isset($error)){ ?>
<p style="color:red;"><?php echo $error; ?></p>
<?php } ?>

</body>
</html>