<?php 
   session_start(); 
  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }

$con = mysqli_connect('localhost','root','root','Music');
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="newcss.css">
<body>

<?php if (isset($_SESSION['username'])): ?>

<div class="w3-bar w3-black" >
    <form  method="post" action='search.php' id="searchform">
        <input  type="text" name="searchText" style="width:280px; margin-left: 12cm; margin-top : 2mm; background-color:white; border-radius: 25px;" required>
        <input  type="submit" class ="btn" name="submitsearch" value="Search" style="border-radius: 25px; background-color:white;">
	<a href='Home.php?logout=1' class="w3-bar-item w3-button" style="margin-right: 0cm; float: right;">logout</a>
        <a href='Home.php?'  class="w3-bar-item w3-button" >Home</a>
        <p  style="margin-top : 2mm;margin-right: 0cm; float: right;">Welcome <?php echo $_SESSION['username']; ?></p>
    </form>
</div>
<?php endif ?>

<div class="w3-bar w3-blue" ">
<a href='Playlistedit.php?id=0' class="w3-bar-item w3-button" >PlayLists</a>
<a href='User.php?id=0' class="w3-bar-item w3-button" >Follows</a>
<a href='Artist.php?id=0' class="w3-bar-item w3-button" >Artists</a>
</div>

<?php $username = $_SESSION['username'];
$_SESSION['alid'] =$_GET['link'];
$alid=$_SESSION['alid'];
$sql="select al.altitle,t.ttitle,t.tid from Album al, Tracks t, AlTracks alt where al.alid=alt.alid and alt.tid=t.tid and alt.alid='$alid'";
$query1 = mysqli_query($con,$sql);
echo "<a href='Play.php?link=".$alid."&id=3&order=1' style='color: red;'>Play Album"; //Play Playlist Button
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
echo"<tr><td>altitle</td><td>ttitle</td></tr>";
while($row=mysqli_fetch_assoc($query1)){
	$altitle = $row[ 'altitle' ];
	$ttitle = $row[ 'ttitle' ];
	$tid = $row[ 'tid' ];
	echo"<tr><td>{$altitle}</td><td><a href='Play.php?link=".$tid."&id=1' style='color: red;'>{$ttitle}</td></tr>";
}
}
?>
</body>
</html>



