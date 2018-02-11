<!DOCTYPE html>
<html lang="en">
<head>
  <title>Music</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<?php
require 'connection.php';
session_start();
$conn    = Connect();
if(isset($_POST["submit"]) ){
$username    = $conn->real_escape_string($_POST['username']);
$stmt = $conn->prepare("SELECT * FROM user where uname = ? ");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
 
while($row = $result->fetch_assoc()) {
	
  echo "<span class='whitetext'><center>This username already exists. Select another username.</center></span>";
  
}
if($result->num_rows === 0)
{

$username    = $conn->real_escape_string($_POST['username']);

$email    = $conn->real_escape_string($_POST['email']);
$city = $conn->real_escape_string($_POST['City']);
$password = $conn->real_escape_string($_POST['password']);
$passwordHash = sha1($password);

$query   = "INSERT into user (uname,name,email,city,password) VALUES('" . $username . "','" . $name . "','" . $email . "','" . $city . "', '" . $passwordHash . "')";
$success = $conn->query($query);
if (!$success) {
    die("Couldn't enter data: ".$conn->error);
}


$_SESSION['username'] = $username;

				$url = "load.php";
				header("Location:$url");
}

}

?>

<div class="container">


  <div class="signupcontainer">

    <div class="signupform">
  <h2 class="defaultcolor centerit">Sign Up</h2>
  <form action="" method="POST" class="form">
    <div class="form-group">
      <label for="Username">Username:</label>
      <input type="text" class="form-control" id="username" placeholder="Enter username" name="username" required>
    </div>
	<div class="form-group">
      <label for="Username">Name:</label>
      <input type="text" class="form-control" id="Name" placeholder="Enter Name" name="Name" required>
    </div>
	<div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email" placeholder="Enter your Email ID" name="email" required>
  </div>
	<div class="form-group">
      <label for="Username">City:</label>
      <input type="text" class="form-control" id="City" placeholder="Enter City" name="City" required>
    </div>
	<div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Choose a password" name="password" required>
    </div>
	<div class="form-group">
      <label for="pwd">Confirm Password:</label>
      <input type="password" class="form-control" id="confirm_password" placeholder="Repeat password" name="repassword" required>
    </div>
    <div class="centerit">
    <button type="submit" class="btn btn-warning btn-lg" name="submit">Sign Up</button>
  </div>
  </form>
  <br/>
  <div class="centerit" style="font-size:15px;">Already a member?<br><a href="login.php">Login Here</a></div>
</div>
</div>
</div>
<script>
var password = document.getElementById("password")
  , confirm_password = document.getElementById("confirm_password");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>
</body>
</html>
