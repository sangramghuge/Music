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

<?php
$username = $_SESSION['username'];
if($_GET['id']==0)
{
$sql="SELECT username2 FROM Follow WHERE username1='$username'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
while($row=mysqli_fetch_assoc($query1)){
	$username2 = $row[ 'username2' ];
	echo"<tr><td><a href='User.php?id=1&uname=$username2' style='color: blue;'>{$username2}</a></td><td><button class='dropbtn'><a href='User.php?id=2&uname=".$username2."'>UnFollow</a></button></td></tr>";
}
}
else {
    echo "<div align=\"center\">Search for some Friends, or some Strangers!</div>";

}
}
if($_GET['id']==1)
{
$username2=$_GET['uname'];
$sql="select p.username, p.ptitle, p.pid from Playlist p where p.username='$username2' and privacy=1";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
    echo"<table id='customers'>";
    echo"<tr><td>PlayLists </td></tr>";
    while($row=mysqli_fetch_assoc($query1)){
        $username2 = $row[ 'username' ];
        $ptitle = $row[ 'ptitle' ];
        $pid=$row[ 'pid' ];
        echo"<tr><td><a href='User.php?id=3&pid=".$pid."' style='color: blue; '>{$ptitle}</a></td></tr>";
    }
}
else {echo $username2." has no public playlists";}
$sql="select arname from Artist a, Likes l where a.arid=l.arid and username='$username2'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
echo"<tr><td>Artists Liked</td></tr>";
while($row=mysqli_fetch_assoc($query1)){
	$arname = $row[ 'arname' ];
	echo"<tr><td><a href='Artist.php?id=1&link=".$arname."&id=1' style='color:blue;'>{$arname}</td></tr>";
}
}
}

if($_GET['id']==2)
{
$username1=$_SESSION['username'];
$username2=$_GET['uname'];
$sql=" DELETE FROM Follow WHERE username1='$username1' and username2='$username2'";
mysqli_query($con, $sql);
$sql="SELECT username2 FROM Follow WHERE username1='$username1'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
while($row=mysqli_fetch_assoc($query1)){
	$username2 = $row[ 'username2' ];
	echo"<tr><td><a href='User.php?id=1&uname=$username2' style='color: blue;'>{$username2}</a></td><td><button class='dropbtn'><a href='User.php?id=2&uname=".$username2."'>UnFollow</a></button></td></tr>";
}
}
}
if($_GET['id']==3)
{
$pid=$_GET['pid'];
$sql="select p.ptitle,t.ttitle,t.tid from Playlist p, Tracks t, PlTracks pt where p.pid=pt.pid and pt.tid=t.tid and p.pid='$pid'";
$query1 = mysqli_query($con,$sql);
echo "<a href='Play.php?link=".$pid."&id=2&order=1' style='color: red;'>Play Playlist"; //Play Playlist Button
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
while($row=mysqli_fetch_assoc($query1)){
	$ptitle = $row[ 'ptitle' ];
	$ttitle = $row[ 'ttitle' ];
	$tid = $row[ 'tid' ];
	echo"<tr> <td> <a href='Play.php?link=".$tid."&id=1' style='color: blue;'>{$ttitle} </td></tr>";
}
}
}
if($_GET['id']==4)
{
$username2=$_GET['uname'];
$username1=$_SESSION['username'];
$sql=" INSERT INTO `Follow` (`username1`, `username2`, `fstamp`) VALUES ('$username1', '$username2', NOW())";
	mysqli_query($con, $sql);
$sql="SELECT username2 FROM Follow WHERE username1='$username'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
while($row=mysqli_fetch_assoc($query1)){
	$username2 = $row[ 'username2' ];
	echo"<tr><td><a href='User.php?id=1&uname=$username2' style='color: blue;'>{$username2}</a></td><td><button class='dropbtn'><a href='User.php?id=2&uname=".$username2."'>UnFollow</a></button></td></tr>";
}}}
?>
</body>
</html>
