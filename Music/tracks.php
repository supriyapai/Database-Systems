<!DOCTYPE html>
<html lang="en">
<head>
  <title>Music</title>
  <style>
  input[type="text-area"]
  {
      background: transparent;
      border: none;
  }
  select{
    color: #fff !important;
    background: transparent;
    padding: 10px !important;
  }
  input:focus{
    outline: none;
}
  </style>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container">
  <div class="loadcontainer">
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

<?php
require 'connection.php';
$conn    = Connect();

session_start();


$username = $_SESSION['username'];

$perpage = 50;

if(isset($_GET["page"])){
$page = intval($_GET["page"]);
}
else {
$page = 1;
}
$calc = $perpage * $page;
$start = $calc - $perpage;
$result = mysqli_query($conn, "select * from limited_tracks Limit $start, $perpage");

$row = mysqli_num_rows($result);


if(isset($_POST['submit']))
{
  global $conn;

  $tracklist = htmlspecialchars($_POST['tracklist']);

  $Tname = $conn->real_escape_string($_POST['Tname']);

  $query4 = "Select TrackId from tracks where TrackName = '" .$Tname. "'";

  $sqlresult1 = $conn->query($query4);



  $query3 = "Select pid from playlist where ptitle = '" .$tracklist. "'";

  $sqlresult = $conn->query($query3);



  if (mysqli_num_rows($sqlresult1) > 0) {
       while($row4 = mysqli_fetch_assoc($sqlresult1)) {

  if (mysqli_num_rows($sqlresult) > 0) {
       while($row3 = mysqli_fetch_assoc($sqlresult)) {

        

            $query2   = "INSERT into playlist_track (pid,TrackId) VALUES('" . $row3['pid'] . "','" . $row4['TrackId']  . "')";


            $result2 = $conn->query($query2);
            if (!$result2) {
            die('Query FAILED' . mysqli_error($conn));

            } else {

              }

           
            }
              }
            }
          }
        }

        ?>
        <table class="m-40" width="400" cellspacing="2" cellpadding="2" align="center">
        <tbody>
        <tr>
        <td align="center">

        <?php

        if(isset($page))

        {

        $result1 = mysqli_query($conn,"select Count(*) As total from limited_tracks");

        $rows = mysqli_num_rows($result1);

        if($rows)

        {

        $rs = mysqli_fetch_assoc($result1);

        $total = $rs["total"];

        }

        $totalPages = ceil($total / $perpage);

        if($page <=1 ){

        echo "<span id='page_links' style='font-weight: bold; padding:10px;border:1px solid #d58512'>Prev</span>";

        }

        else

        {

        $j = $page - 1;

        echo "<span><a id='page_a_link' style='padding:10px;border:1px solid #d58512' href='tracks.php?page=$j'>Prev</a></span>";

        }

        for($i=1; $i <= $totalPages; $i++)

        {

        if($i<>$page)

        {

        echo "<span><a style='padding:10px;border:1px solid #d58512' id='page_a_link' href='tracks.php?page=$i'>$i</a></span>";

        }

        else

        {

        echo "<span id='page_links' style='padding:10px;border:1px solid #d58512' style='font-weight: bold;'>$i</span>";

        }

        }

        if($page == $totalPages )

        {

        echo "<span id='page_links' style='padding:10px;border:1px solid #d58512' style='font-weight: bold;'>Next ></span>";

        }

        else

        {

        $j = $page + 1;

        echo "<span><a  style='padding:10px;border:1px solid #d58512' id='page_a_link' href='tracks.php?page=$j'>Next</a></span>";

        }

        }

        ?></td>
        <td></td>
        </tr>
        </tbody>
        </table>
        <?php

if (mysqli_num_rows($result) > 0) {
     while($row = mysqli_fetch_assoc($result)) {

     ?>

      <table class="table">
        <thead>
        <th> Track Name </th>
        <?php
        $query1   = "Select ptitle from playlist where uname = '" . $username . "'";

        $result1 = $conn->query($query1);
        if (mysqli_num_rows($result1) > 0) {

         ?>
        <th>Add to Playlist</th>
        <?php
      }
         ?>
        </thead>


      <tbody>
        <form action=" " method="post" class="form">
          <tr>
        <td><a href="play.php?TrackId=<?php echo $row ['TrackId']; ?>"><input type="text-area" id="TrackName" name="Tname" value="<?php echo $row ['TrackName']; ?> " readonly></a></td>
        <?php
        if (mysqli_num_rows($result1) > 0) {
          ?>
        <td><select name="tracklist"><?php


        if (mysqli_num_rows($result1) > 0) {
             while($row1 = mysqli_fetch_assoc($result1)) {

           ?>
        <option><?php echo $row1 ['ptitle']; ?></option>

                       <?php
                       }
                  }
                    ?>
        </select>


      </td>
      <td><button type="submit" class="btn btn-info" name="submit">Add</button></td>
      <?php
    }
       ?>
    </tr>
    </form>
      </tbody>
         </table>


       <?php
       }
  }

$conn->close();

?>

</div>

</body>
</html>
