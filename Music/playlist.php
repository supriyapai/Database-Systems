<?php

require 'connection.php';
$conn    = Connect();
session_start();
$pid=htmlspecialchars($_GET['pid']);
$userid = $_SESSION['username'];

?>
<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>User profile</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
</style>
  <link rel="stylesheet" type="text/css" media="all" href="style.css">

</head>

<body>
<div class="">
  <div class="navbar-header">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>



</div>

<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
    <li><a class="active" href="tracks.php">Tracks</a></li>
    <li><a href="userprof.php">User</a></li>
	<li><a href="load.php">Dashboard</a></li>
    <li><a href="#contact">Back</a></li>
   <li><a href="logout.php">Logout</a></li>
  </ul>

  <form class="navbar-form pull-right" role="search" action="rating.php" method="post">
    <div class="input-group">
      <input type="text" class="form-control" placeholder="Search" name="keyword" id="srch-term">
      <span class="input-group-btn">
        <button type="reset" class="btn btn-search">
          <span class="glyphicon glyphicon-remove">
            <span class="sr-only">Close</span>
          </span>
        </button>
        <button type="submit" class="btn btn-search">
          <span class="glyphicon glyphicon-search">
            <span class="sr-only">Search</span>
          </span>
        </button>
      </span>
    </div>
  </form>
</div>


  <div id="w">
   <div id="content" class="clearfix">
      <div id="userphoto"><img src="images/avatar.png" alt="default avatar"></div>
      <?php  
	  
$stmt = $conn->prepare("Select * from playlist where pid =?");
$stmt->bind_param("s", $pid);
$stmt->execute();
$result11 = $stmt->get_result();
$row11 = $result11->fetch_assoc()
	  

          ?>
      <h1 style='color:#fff'><?php echo $row11['ptitle'];?></h1>
	  <?php
	  
$stmt = $conn->prepare("Select * from tracks where TrackId In (Select TrackId from playlist_track where pid in (Select pid from playlist where pid =?))");
$stmt->bind_param("s", $pid);
$stmt->execute();
$result2 = $stmt->get_result();	  
	  
	

if ($result2->num_rows >0){
		$row2=$result2->fetch_assoc();
		
		?>

		 <table class="table table-striped">
        <thead>
            <tr>
                <th>SONG TITLE</th>
	              <th>ARTIST NAME</th>
                <th>DURATION</th>
                <th>PLAY</th>
            </tr>
        </thead>

		<?php   while($row2=$result2->fetch_assoc()){
			?>
			<tbody>
			<tr>
		<form action='play.php' method='post'>
		<td><?php echo $row2['TrackName'];?></td>
    <td><?php echo $row2['ArtistTitle'];?></td>
    <td><?php echo $row2['TrackDuration'];?></td>
	 <td><a href="play.php?TrackId=<?php echo $row2 ['TrackId']?>">Play</a></td>
		</form>
			</tr>
		<?php
		}
}
else
{
	 echo "<h4 class='defaultcolor'>There are no songs in this playlist !</h4>";
}
		?>
		 <td><a href="tracks.php?pid=<?php echo $pid; ?>">Add songs to playlist</a></td>
		</tbody>
		</table>

</body>

<?php 	mysqli_close($conn); ?>
</html>
