<?php

require 'connection.php';
$conn    = Connect();
session_start();

$ArtistTitle=$_GET['ArtistTitle'];
$userid = $_SESSION['username'];

?>
<!doctype html>
<html lang="en-US">
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html">
  <title>User profile</title>
  <style type="text/css">
		input[type=button] {
    border: 1px solid #f44c0e;
    color: #fff;
    background: tomato;
    padding: 10px 10px;
    border-radius: 3px;
}
input[type=button]:hover {
    background: #f44c0e;
}
		</style>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">
    .bs-example{
    	margin: 20px;
    }
</style>
  <link rel="stylesheet" type="text/css" media="all" href="style.css">
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

$(document).ready(function(){

    // like and unlike click
    $(".like, .unlike").click(function(){
        var id = this.id;   // Getting Button id
        var split_id = id.split("_");

        var text = split_id[0];
        var ArtistTitle = split_id[1];  // postid

		//document.write(ArtistTitle);

        // Finding click type
        var type = 0;
        if(text == "like"){
            type = 1;
        }else{
            type = 0;
        }



        // AJAX Request
        $.ajax({
            url: 'likeunlike.php',
            type: 'post',
            data: {ArtistTitle:ArtistTitle,type:type},
            dataType: 'json',
            success: function(data){
                var likes = data['likes'];
                var unlikes = data['unlikes'];

                $("#likes_"+ArtistTitle).text(likes);        // setting likes
                $("#unlikes_"+ArtistTitle).text(unlikes);    // setting unlikes

                if(type == 1){
                    $("#like_"+ArtistTitle).css("color","#ffa449");
                    $("#unlike_"+ArtistTitle).css("color","lightseagreen");
                }

                if(type == 0){
                    $("#unlike_"+ArtistTitle).css("color","#ffa449");
                    $("#like_"+ArtistTitle).css("color","lightseagreen");
                }
              location.reload();

            },
            error: function(data){
                alert("error : " + JSON.stringify(data));
            }
        });

    });


});
</script>
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

<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
  <ul class="nav navbar-nav">
    <li><a class="active" href="tracks.php">Tracks</a></li>
    <li><a href="userprof.php">My Profile</a></li>
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
   <div id="usercontent" class="clearfix">
      <div id="userphoto"><img src="images/avatar.png" alt="default avatar"></div>

      <h1 style="color:#fff"><?php echo $_GET['ArtistTitle']; ?></h1>
	  <h2 style="color:#fff"><?php
		$query4 ="select count(*) as total_songs from tracks WHERE ArtistTitle= '".$ArtistTitle."'";
                $result4 = $conn->query($query4);
				$data=mysqli_fetch_assoc($result4);
		$query5 ="select count(*) as total_albums from albums where AlbumId IN (select AlbumId from tracks where ArtistTitle='".$ArtistTitle."')";
                $result5 = $conn->query($query5);
				$data1=mysqli_fetch_assoc($result5);
               echo $data['total_songs']."  songs |  ".$data1['total_albums']."  albums";

	  ?></h2>

      <nav id="profiletabs">
        <ul class="clearfix">

          <li><a href="#activity">TOP SONGS</a></li>
		  <li><a href="#activity1">TOP ALBUMS</a></li>
		  <li><a href="#bio" class="sel">ALL TRACKS</a></li>
          <li><a href="#settings">ABOUT</a></li>
        </ul>
      </nav>
        <?php

             
                $query = "SELECT * FROM `artists` WHERE ArtistTitle= '".$ArtistTitle."'";
                $result = $conn->query($query);
                while($row = mysqli_fetch_array($result)){

                   $content = $row['ArtistDescription'];

                    $type = -1;


					// like
					$status_query = "SELECT count(*) as cntStatus,type FROM likes WHERE uname='".$userid."' and ArtistTitle='".$ArtistTitle."'";
                    $status_result =  $conn->query($status_query);
					if (!$status_result) {
    die("Couldn't enter data: ".$conn->error);

}
					if (mysqli_num_rows($status_result) > 0) {
                    while($status_row = mysqli_fetch_array($status_result))
					{
                    $count_status = $status_row['cntStatus'];

                    if($count_status > 0){
                        $type = $status_row['type'];
                    }
					}

					}

                    // Count post total likes and unlikes
                    $like_query = "SELECT COUNT(*) AS cntLikes FROM likes WHERE type=1 and ArtistTitle='".$ArtistTitle."'";
                    $like_result = $conn->query($like_query);
                    $like_row = mysqli_fetch_array($like_result);
                    $total_likes = $like_row['cntLikes'];

                    $unlike_query = "SELECT COUNT(*) AS cntUnlikes FROM likes WHERE type=0 and ArtistTitle='".$ArtistTitle."'";
                    $unlike_result = $conn->query($unlike_query);
                    $unlike_row = mysqli_fetch_array($unlike_result);
                    $total_unlikes = $unlike_row['cntUnlikes'];

					//end like


				$query1 = "select * from tracks where TrackId IN ( select TrackId from rating order by rating desc)and ArtistTitle='".$ArtistTitle."'";
                $result1 = $conn->query($query1);

				$query2 ="select * from albums where AlbumId IN (select AlbumId from tracks where ArtistTitle='".$ArtistTitle."')";
                $result2 = $conn->query($query2);

				$query3 ="select * from tracks WHERE ArtistTitle= '".$ArtistTitle."' limit 10";
                $result3 = $conn->query($query3);

            ?>

       <div class="post">

                        <div class="post-action">
                          <p align="right" style="color:#fff">
                            <input type="button" value="Like" id="like_<?php echo $ArtistTitle; ?>" class="like" style="<?php if($type == 1){ echo "color: #49A4FF;"; } ?>" />&nbsp;(<span id="likes_<?php echo $ArtistTitle; ?>"><?php echo $total_likes; ?></span>)&nbsp;

                            <input type="button" value="Unlike" id="unlike_<?php echo $ArtistTitle; ?>" class="unlike" style="<?php if($type == 0){ echo "color: #49A4FF;"; } ?>" />&nbsp;(<span id="unlikes_<?php echo $ArtistTitle; ?>"><?php echo $total_unlikes; ?></span>)
                            </p>
                        </div>
                    </div>
      <section id="activity" class="hidden">
	  <div class="bs-example">



      <table class="table">
          <thead>
              <tr style="color:#fff">
                  <th colspan=3>SONG TITLE</th>
  				<th colspan=3>ARTIST NAME</th>
                  <th colspan=3>DURATION</th>
                  <th colspan=3>PLAY</th>
              </tr>
          </thead>
			<tbody>
			
		<?php   while($row1 = mysqli_fetch_array($result1)){
			
			?>
			<tr style="color:#fff;">
		<td colspan=3><?php echo $row1['TrackName'];?></td>
		<td colspan=3><?php echo $row1['ArtistTitle'];?></td>
		<td colspan=3><?php echo $row1['TrackDuration']."  s";?>
    <td colspan=3><a href="play.php?TrackId=<?php echo $row1 ['TrackId']?>">Play</a></td>
	<?php
		}
		?>
        </tr>
      </tbody>
      </table>

		

		</div>
      </section>
      <section id="activity1" class="hidden">
	   <div class="bs-example">
    <table class="table">
        <thead>
            <tr style="color:#fff">
                <th colspan=3>ALBUM NAME</th>
                <th colspan=3>TRACKS</th>
				<th colspan=3></th>
            </tr>
        </thead>
		<?php
		if (mysqli_num_rows($result2) > 0) {
		while($row2 = mysqli_fetch_array($result2)){
			//$TrackId=$_POST['TrackId'];

			?>
		<tbody>
			<tr style="color:#fff;">
			<th colspan=3><?php echo $row2['AlbumName'];?></th>
		<th colspan=3><?php echo "<iframe src='https://open.spotify.com/embed/album/".$row2['AlbumId']. "' width='300' height='300' frameborder='0' allowtransparency='true'></iframe>";
?><th>
        <th><a href="album1.php?AlbumName=<?php echo $row2 ['AlbumName']?>">PLAYALL </a></th>

			</tr>

		<?php

			}
	
		}

		?>

		</tbody>
		</table>
      </section>
	   <section id="bio" >
	    <div class="bs-example">
       

    <table class="table">
        <thead>
            <tr style="color:#fff">
                <th colspan=3>SONG TITLE</th>
				<th colspan=3>ARTIST NAME</th>
                <th colspan=3>DURATION</th>
                <th colspan=3>PLAY</th>
            </tr>
        </thead>


			<tbody>
			 <?php
			if (mysqli_num_rows($result2) > 0) {
        while($row3 = mysqli_fetch_array($result3)){
    //  $_SESSION['TrackId']=$row3['TrackId'];
     ?>

			<tr style="color:#fff;">
    
		<td colspan=3><?php echo $row3['TrackName'];?></td>
		<td colspan=3><?php echo $row3['ArtistTitle'];?></td>
		<td colspan=3><?php echo $row3['TrackDuration']." s";?></td>
    <td colspan=3><a href="play.php?TrackId=<?php echo $row3 ['TrackId']?>">Play</a></td>

	   </tr>
	    <?php
    }
			}
    ?>

		<tbody>
		</table>
   
      </section>

   <section id="settings" class="hidden">
        <p style="color:#fff"><?php echo  $content; ?></p>


      </section>
    </div><!-- @end #content -->
  </div><!-- @end #w -->

  <?php
               }
            ?>

</div>
</body>
</html>
