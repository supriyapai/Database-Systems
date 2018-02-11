<?php

require 'connection.php';
$conn    = Connect();
session_start();


$userid =$_SESSION['username'];

$ArtistTitle = htmlspecialchars($_POST['ArtistTitle']);

$type = htmlspecialchars($_POST['type']);


// Check entry within table
$query = "SELECT COUNT(*) AS cntpost FROM likes WHERE ArtistTitle='".$ArtistTitle."' and uname='".$userid."'";

$result = $conn->query($query);
$fetchdata = mysqli_fetch_array($result);
if (!$fetchdata) {
    die("Couldn't enter data: ".$conn->error);

}
$count = $fetchdata['cntpost'];


if($count == 0){
    $insertquery = "INSERT INTO likes(uname,ArtistTitle,type) values('".$userid."','".$ArtistTitle."','".$type."')";
    $success= $conn->query($insertquery);
	
if (!$success) {
    die("Couldn't enter data: ".$conn->error);
}

}else {
    $updatequery = "UPDATE likes SET type='" . $type . "', where uname='" . $userid . "' and ArtistTitle='" . $ArtistTitle."'";
    $conn->query($updatequery);
}


// count numbers of like and unlike in post
$query = "SELECT COUNT(*) AS cntLike FROM likes WHERE type=1 and ArtistTitle='".$ArtistTitle."'";
$result = $conn->query($query);
$fetchlikes = mysqli_fetch_array($result);
$totalLikes = $fetchlikes['cntLike'];


$query = "SELECT COUNT(*) AS cntUnlike FROM likes WHERE type=0 and ArtistTitle='".$ArtistTitle."'";
$result = $conn->query($query);
$fetchunlikes = mysqli_fetch_array($result);
$totalUnlikes = $fetchunlikes['cntUnlike'];


$return_arr = array("likes"=>$totalLikes,"unlikes"=>$totalUnlikes);

echo json_encode($return_arr);
?>