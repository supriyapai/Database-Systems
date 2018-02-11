<?php

require 'connection.php';
$conn    = Connect();
session_start();

$username= $_SESSION['username'];

if(isset($_POST['cplaylist']))
{
  $ptitle    = $conn->real_escape_string($_POST['ptitle']);
  $priv_pub = $_POST['priv_pub'];
  $currdate = date("Y-m-d H:i:s");

  $stmt = $conn->prepare("INSERT INTO playlist (ptitle,r_date,uname, private_public) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssss", $ptitle, $currdate, $username,$priv_pub);
  $stmt->execute();
  

}

?>
<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>User profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="style.css">

  <script type="text/javascript">
$(function(){
  $('#profiletabs ul li a').on('click', function(e){
    e.preventDefault();
    var newcontent = $(this).attr('href');

    $('#profiletabs ul li a').removeClass('sel');
    $(this).addClass('sel');

    $('#usercontent section').each(function(){
      if(!$(this).hasClass('hidden')) { $(this).addClass('hidden'); }
    });

    $(newcontent).removeClass('hidden');
  });
});
</script>
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

  <div id="w">
   <div  id="usercontent" class="clearfix">
      <div id="userphoto"><img src="images/avatar.png" alt="default avatar"></div>
      <h1><?php echo $username; ?></h1>

      <nav id="profiletabs">
        <ul class="clearfix">

          <li><a href="#following" class="sel">Following</a></li>
		       <li><a href="#followers">Followers</a></li>
		       <li><a href="#playlists">My Playlists</a></li>
          <li><a href="#likes">My Likes</a></li>
        </ul>
      </nav>


<div>
      <section id="following">
        <?php

        $stmt2 = $conn->prepare("Select * from follow where uname = ? limit 50");
        $stmt2->bind_param("s", $username);
        $stmt2->execute();
        $result = $stmt2->get_result();

        if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {

         ?>
                <div class="feed"><a href="seconduser.php?uname=<?php echo $row ['followee']?>" class="whitetext"><?php echo $row ['followee']; ?></a><span class="pull-right round">Following</span></div>
                <?php

          }
          }
          else {
            echo "<h2 class='defaultcolor'>You are not following any users</h2>";
          }
          ?>

      </section>
      <section id="followers" class="hidden">
        <?php
        $stmt3 = $conn->prepare("Select * from follow where followee = ? limit 50");
        $stmt3->bind_param("s", $username);
        $stmt3->execute();
        $result1 = $stmt3->get_result();

        if (mysqli_num_rows($result1) > 0) {
        while($row1 = mysqli_fetch_assoc($result1)) {

         ?>
                <div class="feed"><a href="seconduser.php?uname=<?php echo $row1 ['uname']?>" class="whitetext"><?php echo $row1 ['uname']; ?></a><span class="pull-right"></span></div>
                <?php

          }
          }
          else {
            echo "<h2 class='defaultcolor'>No users follow you!</h2>";
          }
          ?>

      </section>

      </section>
	   <section id="playlists" class="hidden">
       <button class="btn btn-success" data-toggle="modal" data-target="#myModal">Create a new Playlist</button>
       <div class="modal fade" id="myModal" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content blackcolor">
              <form action=" " method="POST" class="form">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title defaultcolor">Create a playlist</h4>
              </div>
              <div class="modal-body defaultcolor">

                  <div class="form-group">
                    <label for="Username">Playlist Title</label>
                    <input type="text" class="form-control" id="ptitle" placeholder="Enter a Name" name="ptitle" required>
                  </div>
               <div class="form-group">
                  <label for="priv_pub">Is it Private or Public?</label>
                  <select class="form-control" name="priv_pub">
                  <option value="1">Private</option>
                  <option value="2">Public</option>
               </select>
                </div>



              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-default" name="cplaylist" ata-dismiss="modal">Create a Playlist</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </form>
            </div>

          </div>
        </div>
       <?php


       $stmt4 = $conn->prepare("Select * from playlist where uname = ? limit 50");
       $stmt4->bind_param("s", $username);
       $stmt4->execute();
       $result2 = $stmt4->get_result();

       if (mysqli_num_rows($result2) > 0) {
       while($row2 = mysqli_fetch_assoc($result2)) {
         $pid = $row2 ['pid'];

        ?>

               <div class="feed"><a href="playlist.php?pid=<?php echo $pid  ?>" class="whitetext"><?php echo $row2 ['ptitle']; ?></a></div>
               <?php

         }
         }
         else {
           echo "<center><h2 class='defaultcolor'>No playlists made yet!</h2></center>";
         }
         ?>


      </section>

   <section id="likes" class="hidden">
     <?php
     $stmt5 = $conn->prepare("Select * from likes where uname = ? limit 10");
     $stmt5->bind_param("s", $username);
     $stmt5->execute();
     $result3 = $stmt5->get_result();

     if (mysqli_num_rows($result3) > 0) {
     while($row3 = mysqli_fetch_assoc($result3)) {

      ?>

		<div class="feed"><a href="artist_home.php?ArtistTitle=<?php echo $row3 ['ArtistTitle'];?>"><?php echo $row3 ['ArtistTitle'];?></a></div>
             <?php

       }
       }
       else {
         echo "<h2 class='defaultcolor'>No Artists liked yet!</h2>";
       }
       ?>

      </section>

</div>
    </div><!-- @end #content -->
  </div><!-- @end #w -->
  <?php


mysqli_close($conn);

            ?>

          </div>
</body>
</html>
