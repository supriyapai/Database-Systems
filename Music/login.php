
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
session_start();
require 'connection.php';
$conn    = Connect();
if(isset($_POST["submit"]) ){
$username    = $conn->real_escape_string($_POST['username']);
$password = $conn->real_escape_string($_POST['password']);
$passwordHash = sha1($password);

$stmt = $conn->prepare("SELECT * FROM user where uname = ? and password= ?");
$stmt->bind_param("ss", $_POST['username'],$passwordHash);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows === 0) echo "<span class='whitetext'><center>Incorrect Username/Password for ".$username."</center></span>";
while($row = $result->fetch_assoc()) {
	
  $user= $row['uname'];
  $password = $row['password'];

  
  if($username == $user)
		{
			if($password == $passwordHash)
			{
				$_SESSION['username'] = $user;

				$url = "load.php";
				header("Location:$url");
			}
			else
			{
				echo "Incorrect Username/Password";
			}
		}
		else
		{
			echo "Incorrect Username/Password";
		}
}
$stmt->close();

}
?>

<div class="container">
  <div class="signupcontainer">

  <div class="signupform">
      <h2 class="defaultcolor centerit">Login</h2>
 <form action="" method="post" class="form">
    <div class="form-group">
      <label for="Username">Username:</label>
      <input type="text" class="form-control" id="username" placeholder="Enter username" name="username">
    </div>
    <div class="form-group">
      <label for="pwd">Password:</label>
      <input type="password" class="form-control" id="password" placeholder="Enter password" name="password">
    </div>
    <div class="checkbox">
      <label><input type="checkbox" name="remember"> Remember me</label>
    </div>
    <div class="centerit">
    <button type="submit" name ="submit" class="btn btn-warning btn-lg">Login</button>
  </div>
  <br/>
	<div class="psw centerit" style="font-size:15px;">Dont have an account? <a href="signup.php">Sign Up</a></div>
	
  </form>
</div>
</div>
</div>

</body>
</html>
