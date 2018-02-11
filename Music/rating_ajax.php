<?php

require 'connection.php';
$conn    = Connect();

session_start();

$userid =$_SESSION['username'];
$TrackId = htmlspecialchars($_POST['TrackId']);

$rating =htmlspecialchars($_POST['rating']);
echo $rating;

$query = "SELECT COUNT(*) AS cntpost FROM post_rating WHERE postid=".$postid." and userid=".$userid;

$result = $conn->query($query);
$fetchdata = mysqli_fetch_array($result);
$count = $fetchdata['cntpost'];


    $insertquery = "INSERT INTO rating(uname,TrackId,rating) values('".$userid."','".$TrackId."','".$rating."')";
    
	$success = $conn->query($insertquery);
if (!$success) {
    die("Couldn't enter data: ".$conn->error);
}


echo json_encode($return_arr);



?>