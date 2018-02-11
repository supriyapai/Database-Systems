<?php

require 'connection.php';
$conn    = Connect();
session_start();

$username= $_SESSION['username'];

$seconduser = $_GET['uname'];
$_SESSION['seconduser'] = $seconduser;

if(isset($_POST['followuser']))
{
   $currdate = date("Y-m-d H:i:s");
   
$stmt = $conn->prepare("INSERT into follow (uname,followee,ftimestamp) values (?,?,?)");
$stmt->bind_param("sss", $username,$seconduser,$currdate);
$stmt->execute();

}

$stmt = $conn->prepare("Select followee from follow where followee = ? AND uname = ?");
$stmt->bind_param("ss", $seconduser,$username);
$stmt->execute();
$result6 = $stmt->get_result();



?>
<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>User profile</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
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
   <div  id="usercontent" class="clearfix">
      <div id="userphoto"><img src="images/avatar.png" alt="default avatar"></div>
      <h1><?php echo $seconduser ;?></h1>

      <nav id="profiletabs">
        <ul class="clearfix">

          <li><a href="#following" class="sel">Following</a></li>
		       <li><a href="#followers">Followers</a></li>
		       <li><a href="#playlists"> Playlists</a></li>
          <li><a href="#likes">Artists Liked</a></li>
        </ul>
      </nav>


<div>
  <div class="post-action">
    <?php
    if (mysqli_num_rows($result6) <= 0) {
      ?>

    <p align="right">
      <form action="" method="post">
      <input type="submit" name="followuser" class="btn btn-warning" value="Follow" />
    </form>
    </p>

  <?php
 }
?>
  </div>
</div>
      <section id="following">
        <?php
$stmt = $conn->prepare("Select * from follow where uname = ? limit 50");
$stmt->bind_param("s", $seconduser);
$stmt->execute();
$result = $stmt->get_result();

	
        if ($result->num_rows >0 ) {
        while($row = $result->fetch_assoc()) {

         ?>
                <div class="feed"><a href="seconduser.php?uname=<?php echo $row ['followee']?>" class="whitetext"><?php echo $row ['followee']; ?></a></div>
                <?php

          }
          }
          else {
           echo '<h2 class="defaultcolor">' .$seconduser. ' does not follow any users!</h2>';
          }
          ?>

      </section>

      <section id="followers" class="hidden">
        <?php
		
$stmt = $conn->prepare("Select * from follow where followee = ? limit 50");
$stmt->bind_param("s", $seconduser);
$stmt->execute();
$result1 = $stmt->get_result();

	
	   
        if ($result1->num_rows >0 ) {
        while($row1 = $result1->fetch_assoc()) {

         ?>
                <div class="feed"><a href="seconduser.php?uname=<?php echo $row1 ['uname']?>" class="whitetext"><?php echo $row1 ['uname']; ?></a><span class="pull-right"></span></div>
        <?php

          }
          }
          else
           {
           echo '<h2 class="defaultcolor"> No Users follow ' .$seconduser. '</h2>';
          }
          ?>

      </section>

      </section>
	   <section id="playlists" class="hidden">
       <?php
	   
$stmt = $conn->prepare("Select * from playlist where uname =? AND private_public = '2' limit 50");
$stmt->bind_param("s", $seconduser);
$stmt->execute();
$result2 = $stmt->get_result();
   

	   
       if ($result2->num_rows >0 ) {
       while($row2 = $result2->fetch_assoc()) {

        ?>
               <div class="feed"><a href="secondplaylist.php?pid=<?php echo $row2 ['pid']; ?>?" class="whitetext"><?php echo $row2 ['ptitle']; ?></a></div>
               <?php

         }
         }
         else {
          echo '<h2 class="defaultcolor">No Playlists made Yet!</h2>';
         }
         ?>


      </section>

   <section id="likes" class="hidden">
     <?php
	 
$stmt = $conn->prepare("Select * from likes where uname = ? limit 10");
$stmt->bind_param("s", $seconduser);
$stmt->execute();
$result3 = $stmt->get_result();
	 

     if ($result3->num_rows >0) {
     while($row3 = mysqli_fetch_assoc($result3)) {

      ?>
		<div class="feed"><a href="artist_home.php?ArtistTitle=<?php echo $row3 ['ArtistTitle']?>"><?php echo $row3 ['ArtistTitle']?></a></div>
             <?php

       }
       }
       else {
        echo '<h2 class="defaultcolor">No Artists Liked Yet!</h2>';
       }
       ?>

      </section>

</div>
    </div><!-- @end #content -->
  </div><!-- @end #w -->
  <?php
            ?>

          </div>
</body>
</html>
