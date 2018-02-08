
<?php

ob_start();
session_start();
require_once 'dbconnect.php';

 // it will never let you open index(login) page if session is set

 if ( isset($_SESSION['user'])!="" ) {
   
    header("Location: home.php");

  exit;

}

 
$error = false;
if( isset($_POST['btn-login']) ) {

 

  // prevent sql injections/ clear user invalid inputs

  $email = trim($_POST['email']);
  $email = strip_tags($email);
  $email = htmlspecialchars($email);

 

  $pass = trim($_POST['pass']);
  $pass = strip_tags($pass);
  $pass = htmlspecialchars($pass);

  // prevent sql injections / clear user invalid inputs

 

  if(empty($email)){

   $error = true;
   $emailError = "Please enter your email address.";

} else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {

   $error = true;

   $emailError = "Please enter valid email address.";

  }

 

  if(empty($pass)){

   $error = true;

   $passError = "Please enter your password.";

  }

 

  // if there's no error, continue to login

  if (!$error) {

   $password = hash('sha256', $pass); // password hashing using SHA256
  $res=mysqli_query($conn, "SELECT userId, userName, userPass FROM users WHERE userEmail='$email'");

   $row=mysqli_fetch_array($res, MYSQLI_ASSOC);

   $count = mysqli_num_rows($res); // if uname/pass correct it returns must be 1 row

   

   if( $count == 1 && $row['userPass']==$password ) {

    $_SESSION['user'] = $row['userId'];

    header("Location: home.php");

   } else {

    $errMSG = "Incorrect Credentials, Try again...";

   }

   

  }

 

 }

?>

<!DOCTYPE html>
<html>
<head>
<title>Login & Registration System</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<style type="text/css">
  .container {
  background-color: #f6f7f7;
}
.form-control {
  margin-top: 8px;
}
h2 {
  text-align: center;
  background-color:#f6f7f7;
  font-weight: bold;
  margin: 10px; 
  padding: 10px;
}
.button {
    background-color: gray; 
    border: none;
    color: white;
    padding: 10px;
    text-align: center;
    text-decoration: none;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 12px;
    font-weight: ;
    
}


button {
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
}

button:hover {
    opacity: 0.8;
}

.Sign {
  font-size: 20px;

}
.psw {
  float: right;
  font-size: 15px;
}

  
</style>
</head>
<body>


<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">


<h2>Sign In Here</h2>


<hr />

             

<?php

if ( isset($errMSG) ) {
echo $errMSG; ?>

               

<?php

}

?>




<div class="container">
<input type="email" name="email" class="form-control" placeholder="Your Email" value="<?php echo $email; ?>" maxlength="40" />

<span class="text-danger"><?php echo $emailError; ?></span>

<input type="password" name="pass" class="form-control" placeholder="Your Password" maxlength="15" />

<span class="text-danger"><?php echo $passError; ?></span>

<div class="checkbox">
      <label><input type="checkbox"> Remember me</label>
    </div>
     <span class="psw">Forgot <a href="#">password?</a></span>


<hr />


<button class="button" type="submit" name="btn-login">Sign In</button>

           

 <hr />

   

 <a class="Sign" href="register.php">Sign Up Here...</a>

</form>
</div>
</div>
</body>
</html>
<?php ob_end_flush(); ?>