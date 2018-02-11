<?php

require 'connection.php';
$conn    = Connect();
session_start();
$AlbumName=$_GET['AlbumName'];
$userid = $_SESSION['username'];



$stmt = $conn->prepare("Select * from tracks where albumId IN (select albumId from albums where AlbumName=?)");
$stmt->bind_param("s",$AlbumName);
$stmt->execute();
$result2 = $stmt->get_result();
$row2 = $result2->fetch_assoc();

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
    <li><a href="userprof.php">My Profile</a></li>
	<li><a href="load.php">Dashboard</a></li>
    <li><a href="#contact">Back</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>

  <form class="navbar-form pull-right" role="search" action="rating.php" method="get">
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
      <div id="userphoto">	<?php echo "<iframe src='https://open.spotify.com/embed/album/".$row2['AlbumId']. "' width='300' height='300' frameborder='0' allowtransparency='true'></iframe>";
?>
	
  </div>
  <?php
      echo"<h1 style='color:#fff'>".$AlbumName."</h1>";
	  
	  
	$stmt = $conn->prepare("Select * from tracks where albumId IN (select albumId from albums where AlbumName=?)");
$stmt->bind_param("s",$AlbumName);
$stmt->execute();
$result1 = $stmt->get_result();
$row1 = $result1->fetch_assoc();

		?>

		 <table class="table table-striped">
        <thead>
            <tr style='color:#fff'>
                <th>SONG TITLE</th>
				<th>ARTIST NAME</th>
                <th>DURATION</th>
                <th>PLAY</th>
            </tr>
        </thead>

		<?php   while($row1 = $result1->fetch_assoc()){
			?>
			<tbody>
			<tr>
		
		<td><?php echo $row1['TrackName'];?></td>
		<td><a href="artist_home.php?ArtistTitle=<?php echo $row1 ['ArtistTitle']?>"><?php echo $row1['ArtistTitle'];?></td>
		<td><?php echo $row1['TrackDuration']."  s";?></td>
           <td><a href="play.php?TrackId=<?php echo $row1 ['TrackId']?>">Play</a></td>

			</tr>
		<?php
		}
		mysqli_close($conn);
		?>
		</tbody>
		</table>

</body>
</html>
