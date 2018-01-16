<?php
session_start();
$rating=$_GET['r'];
$username=$_SESSION['username'];
$track=$_SESSION['PlayTrack'];
date_default_timezone_set('America/New_York');
$date = date('Y-m-d h:i:s a', time());
$db = mysqli_connect('localhost','root','root','Music');
$query = "INSERT INTO Rates (username,tid,rstamp,rating) VALUES ('$username', '$track', '$date', '$rating');";
$results = mysqli_query($db, $query);
echo  "Thank you for giving this song ".$rating." stars!";
?>