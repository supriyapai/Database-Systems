<?php

include "config.php";
session_start();
$username = $_SESSION['username'];
$TrackId=htmlspecialchars($_GET['TrackId']);
$query   = "INSERT into plays (uname,TrackId) VALUES('" . $username . "','" . $TrackId."')";
$success = mysqli_query($con,$query);

?>
<html>
    <head>


        <!-- CSS -->
		
		  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <link href="style.css" type="text/css" rel="stylesheet" />
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <link href='jquery-bar-rating-master/dist/themes/fontawesome-stars.css' rel='stylesheet' type='text/css'>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Script -->
					  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

        <script src="jquery-3.0.0.js" type="text/javascript"></script>
        <script src="jquery-bar-rating-master/dist/jquery.barrating.min.js" type="text/javascript"></script>
        <script type="text/javascript">
        $(function() {
            $('.rating').barrating({
                theme: 'fontawesome-stars',
                onSelect: function(value, text, event) {

                    // Get element id by data-id attribute
                    var el = this;
                    var el_id = el.$elem.data('id');

                    // rating was selected by a user
                    if (typeof(event) !== 'undefined') {

                        var split_id = el_id.split("_");

                        var TrackId = split_id[1];  // TrackId


                        // AJAX Request
                        $.ajax({
                            url: 'rating_ajax.php',
                            type: 'post',
                            data: {TrackId:TrackId,rating:value},
                            success: function(response){
								location.reload();

                            }
                        });
                    }
                }
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
<div class="content">

<?php

$username1 = htmlspecialchars($_GET['TrackId']);


echo "<iframe src='https://open.spotify.com/embed/track/".$username1. "' width='600' height='500' frameborder='0' allowtransparency='true'></iframe>";


$stmt = $con->prepare("SELECT * FROM rating WHERE TrackId=? and uname=? ");
$stmt->bind_param("ss", $username1,$username);
$stmt->execute();
$userresult = $stmt->get_result();



                   while( $fetchRating = $userresult->fetch_assoc())
				   {
                    $rating = $fetchRating['rating'];
				   }
	
$stmt = $con->prepare("SELECT ROUND(AVG(rating),1) as averageRating FROM rating WHERE TrackId=?");
$stmt->bind_param("s", $username1);
$stmt->execute();
$avgresult = $stmt->get_result();


                    $fetchAverage = $avgresult->fetch_assoc();
                    $averageRating = $fetchAverage['averageRating'];

                    if($averageRating <= 0){
                        $averageRating = "No rating yet.";
                    }
?>

                        <div class="post-action">
					

					
						
                            <!-- Rating -->
                            <select class='rating' id='rating_<?php echo $username1; ?>' data-id='rating_<?php echo $username1; ?>'>
                                <option value="1" >1</option>
                                <option value="2" >2</option>
                                <option value="3" >3</option>
                                <option value="4" >4</option>
                                <option value="5" >5</option>
                            </select>
                           <div  style='clear: both;'></div>
                            <p style="font-size:20px;color:#fff">Average Rating : <span  style="font-size:20px"id='avgrating_<?php echo $postid; ?>'><?php echo $averageRating; ?></span>
</p>


                            <!-- Set rating -->
                            <script type='text/javascript'>
                            $(document).ready(function(){
                                $('#rating_<?php echo $username1; ?>').barrating('set',<?php echo $rating; ?>);
                            });

                            </script>
							
                       
						 
	</div>
</div>
</body>
</html>
