<html>
   <head>
      <title> List on search </title>
      <style>
         table input[type="text"]
         {
         background: transparent;
         border: none;
         }
         table input[type="text-area"]
         {
         background: transparent;
         border: none;
         }
      </style>
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

         <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
               <li><a class="active" href="tracks.php">Tracks</a></li>
               <li><a href="userprof.php">My profile</a></li>
              <li><a href="load.php">Dashboard</a></li>
              <li><a href="index.php">Logout</a></li>
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

         <div class="loadcontainer">

               <?php
                  require 'connection.php';
                  session_start();
                  $conn    = Connect();
                  $username = $_SESSION['username'];

                  $keyword1   = $conn->real_escape_string($_GET['keyword']);
                   $keyword = "%{$keyword1}%";

                  $userid = $_SESSION['username'];


$stmt = $conn->prepare("SELECT * FROM tracks where TrackName like ? limit 10");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result = $stmt->get_result();



                  if ($result->num_rows >0) {
                    echo "<h2>TRACKS</h2>";
                  }
                    ?>


            <table class="table">
				          <thead>
                  <!-- <th>TrackId</th> -->
                  <th>TrackName</th>
                  <th>ArtistTitle</th>
                  <th> </th>
               </thead>
               <?php
               if (mysqli_num_rows($result) > 0) {
                  while($row = $result->fetch_assoc()) {

                  ?>
               <tr>
                  <td style="display:none"><input type="text-area" name="TrackId" value="<?php echo htmlspecialchars($row ['TrackId']); ?> ">
                  </td>
                  <td>
                     <input type="text-area" name="TrackName" value="<?php echo htmlspecialchars($row ['TrackName']); ?> ">
                  </td>
                  <td>
                     <a href="artist_home.php?ArtistTitle=<?php echo $row['ArtistTitle']?>"><input type="text" name="Address" value="<?php echo htmlspecialchars($row ['ArtistTitle']); ?> "></a>
                  </td>
                  <td>
                     <a class="btn btn-warning" href="play.php?TrackId=<?php echo htmlspecialchars($row ['TrackId']) ?>">Play</a>
                  </td>
               </tr>
               <?php
                  }

                  if (isset($_POST['select'])) {
                  $_SESSION['TrackId'] =$row ['TrackId'] ;
                  echo $_SESSION['TrackId'];
                  }
                  }
                  ?>
			    </table>
        <?php
			   $stmt = $conn->prepare("SELECT * FROM albums where AlbumName like ? limit 10");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result1 = $stmt->get_result();


                  if ($result1->num_rows >0) {

                   echo "<h2>ALBUMS</h2>";
                    ?>

               <?php
                  while($row1 = $result1->fetch_assoc()) {

                  ?>
				  <form action="album.php" method="post">
            <table class="table">
               <thead>
                  <th></th>
                  <th>AlbumName</th>

                  <th> </th>
               </thead>
               <tr>
                  <td>
                     <input type="hidden" name="AlbumId" value="<?php echo htmlspecialchars($row1 ['AlbumId']); ?> ">
                  </td>
                  <td>
                     <input type="text-area" name="AlbumName" value="<?php echo htmlspecialchars($row1 ['AlbumName']); ?> ">
                  </td>

                  <td>
                     <input class="btn btn-warning" type="submit" name="select" value="select" >
                  </td>
               </tr>

            </table>
         </form>
               <?php
                  }

                  if (isset($_POST['select'])) {
                  $_SESSION['AlbumId'] =$row1 ['AlbumId'] ;
				  $_SESSION['AlbumName'] =$row1 ['AlbumName'] ;
                  echo $_SESSION['AlbumId'];
                  }
                  }
                    ?>
         <?php


$stmt = $conn->prepare("SELECT * FROM user where uname like ?");
$stmt->bind_param("s", $keyword);
$stmt->execute();
$result5 = $stmt->get_result();


            if ($result5->num_rows >0) {
              echo "<h2>USERS</h2>";
              while($row5 = $result5->fetch_assoc()) {

              ?>



            <table class="table">
               <thead>
                  <th>User name</th>
               </thead>
                  <form action="" method="post">
               <tr>
                  <td>
                     <a href="seconduser.php?uname=<?php echo htmlspecialchars($row5 ['uname']); ?>" class="whitetext"><?php echo htmlspecialchars($row5 ['uname']);  ?></a>
                  </td>
               </tr>
               </form>


			   </table>
         <?php
            }
          }
            ?>

      <?php

$stmt = $conn->prepare("Select * from artists where ArtistTitle like ? or ArtistDescription like ? limit 10");
$stmt->bind_param("ss", $keyword, $keyword);
$stmt->execute();
$result2 = $stmt->get_result();


            if ($result2->num_rows >0) {
            echo "<h2>ARTISTS</h2>";
            ?>

            <table class="table">
               <thead>

                  <th>ArtistTitle</th>
                  <th>ArtistDescription</th>
               </thead>

               <?php
               if (mysqli_num_rows($result2) > 0) {
                  while($row2 = $result2->fetch_assoc()) {
                    ?>

			 <tbody>
			<tr>


		<td><a href="artist_home.php?ArtistTitle=<?php echo $row2 ['ArtistTitle']?>"><?php echo $row2['ArtistTitle'];?></td>
		<td><?php echo $row2['ArtistDescription']."  s";?></td>


			</tr>


          <?php
             }
           }
             ?>
			 </body>
			      </table>

           <?php
             }

             if ($result5->num_rows <= 0 && $result->num_rows <= 0 && $result1->num_rows <= 0 && $result2->num_rows <= 0)
             {
               echo "<h2>No Results found!</h2>";
             }


            mysqli_close($conn);
                  ?>
                </div>
      </div>
   </body>
</html>
