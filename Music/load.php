 <?php
 require 'connection.php';
 $conn    = Connect();

 if(! $conn ) {
       die('Could not connect: ' . mysqli_error());
    }

session_start();
$username= $_SESSION['username'];




?>
<!DOCTYPE  html>
<html>
<head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

      <div class="container">

        <div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>



			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
          <li><a class="active" href="tracks.php">Tracks</a></li>
					<li><a href="userprof.php">My Profile</a></li>
          
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


<div class="clear"></div>
<div class="m-40">
<div class="col-lg-6 col-md-6 col-sm-6">
  <h3 class="defaultcolor centerit">Trending Songs Last Week</h3>
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
      <li data-target="#myCarousel" data-slide-to="2"></li>
      <li data-target="#myCarousel" data-slide-to="3"></li>
      <li data-target="#myCarousel" data-slide-to="4"></li>
      <li data-target="#myCarousel" data-slide-to="5"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
      <?php

  
  
  
    $stmt = $conn->prepare("select * from tracks where TrackId IN ( select TrackId from rating order by rating desc,timestamp desc)");

$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows >0){
	$counter=1;
while($row = $result->fetch_assoc()) {
	 $TrackId = $row['TrackId'];
             ?>

      <div class="item<?php if($counter <= 1){echo " active"; } ?>">
            <?php
        	   echo "<iframe src='https://open.spotify.com/embed/track/".htmlspecialchars($row['TrackId']). "' width='350' height='600' frameborder='0' allowtransparency='true'></iframe>";
            ?>

        </div>
        <?php
      $counter++;
           }


    }

           ?>



    </div>

    <!-- Left and right controls -->
    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#myCarousel" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>
</div>
<div class="col-lg-6 col-md-6 col-sm-6">

<div class="newsfeed">

  <?php



$stmt = $conn->prepare("select * from follow where uname != ? and uname IN (select uname from follow where uname = ?) order by ftimestamp DESC limit 5");
$stmt->bind_param("ss", $username,$username);
$stmt->execute();
$result = $stmt->get_result();

$stmt2 = $conn->prepare("select * from likes where uname != ? and ArtistTitle IN (select ArtistTitle from likes where uname = ?) order by ltimestamp DESC limit 5");
$stmt2->bind_param("ss", $username,$username);
$stmt2->execute();
$result2 = $stmt2->get_result();

        if($result->num_rows >0 || $result2->num_rows >0 ){
          echo "<h3 class='defaultcolor centerit'>Explore</h3>";

        }

      if ($result->num_rows >0 ) {
             while($row1 = $result->fetch_assoc()) {
               $user = $row1['uname'];
               $followee = $row1['followee'];
               ?>

                 <div "padding: 10px;
                 border: 1px solid #d58512;"><a href="seconduser.php?uname=<?php echo $user1?>"><?php echo htmlspecialchars($user) ?></a> Followed <?php echo htmlspecialchars($followee) ?>
                </div>


               <?php
             }
           }



              if ($result2->num_rows > 0) {

                   while($row2 = $result2->fetch_assoc()) {

                     $user1 = $row2['uname'];
                     $ArtistTitle = $row2['ArtistTitle'];
                     ?>
                     <div style="padding: 10px;
                     border: 1px solid #d58512;">
                 <a href="seconduser.php?uname=<?php echo $user1?>"> <?php echo htmlspecialchars($user1) ?></a>  Likes <?php echo htmlspecialchars($ArtistTitle) ?>
                     </div>
                     <?php
         }
       }
 
 
      $stmt3 = $conn->prepare("select  ArtistTitle from likes GROUP by ArtistTitle order by count(type),ltimestamp desc limit 3");
$stmt3->execute();
$result3 = $stmt3->get_result();

       if ($result3->num_rows >0) {
         echo "<h3 class='defaultcolor centerit'>Top rated Artists this week</h3>";
         while($row3 = $result3->fetch_assoc()) {
           ?>
           <div style="padding: 10px;
           border: 1px solid #d58512;">

            <a href="artist_home.php?ArtistTitle=<?php echo $row3['ArtistTitle']?>"><?php echo htmlspecialchars($row3['ArtistTitle']) ?></a>
           </div>
           <?php
         }
       }

	   
	    
	   
	   
	   
	   
      $stmt4 = $conn->prepare("SELECT * from albums ORDER by AlbumReleaseDate desc LIMIT 3");
$stmt4->execute();
$result4 = $stmt4->get_result();

       if ($result4->num_rows >0) {
         echo "<h3 class='defaultcolor centerit'>New Albums this week</h3>";
         while($row4 = $result4->fetch_assoc()) {
           ?>
           <div style="padding: 10px;
           border: 1px solid #d58512;">

             <a href="top_album.php?AlbumName=<?php echo $row4['AlbumName']?>"><?php echo htmlspecialchars($row4['AlbumName']) ?></a>
           </div>
           <?php
         }
       }


           mysqli_close($conn);
  ?>

  </div>
</div>
</div>
    </div>
    


    </body>
</html>
