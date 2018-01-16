<?php 
   session_start(); 
  $username = "";
	$k=0;
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
<style>
.dropbtn {
    background-color: black;
    color: white;
    padding: 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 10px 10px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {background-color: blue;}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: blue;
}
</style>
<body>
<?php if (isset($_SESSION['username'])): ?>

<div class="w3-bar w3-black" >
    <form  method="post" action='Playlistedit.php?id=3' id="searchform">
        <input  type="text" name="searchText" style="width:280px; margin-left: 12cm; margin-top : 2mm; background-color:white; border-radius: 25px;" required>
        <input  type="submit" class ="btn" name="addsearch" value="Search" style="border-radius: 25px; background-color:white;">
	<a href='Home.php?logout=1' class="w3-bar-item w3-button" style="margin-right: 0cm; float: right;">logout</a>
        <a href='Home.php?'  class="w3-bar-item w3-button">Home</a> 
        <p  style="margin-top : 2mm;margin-right: 0cm; float: right;">Welcome <?php echo $_SESSION['username']; ?></p>
    </form>
</div>
<?php endif ?>
<div class="w3-bar w3-blue" ">
<a href='Playlistedit.php?id=0' class="w3-bar-item w3-button" >PlayLists</a>
<a href='User.php?id=0' class="w3-bar-item w3-button" >Follows</a>
<a href='Artist.php?id=0' class="w3-bar-item w3-button" >Artists</a>
</div>
<form  method="post" action='Playlistedit.php?id=4' id="searchform">
        <input  type="text" name="plText" style="width:280px; margin-top : 2mm; background-color:white; border-radius: 25px;" required>
	<select name="plprivacy">
        <option value="0">private</option>
        <option value="1">public</option>
    </select>
        <input  type="submit" class ="btn" name="Create" value="Create a new Playlist" style="border-radius: 25px; background-color:white;">
 </form>
<?php
$username = $_SESSION['username'];
//$con = mysqli_connect('localhost','root','','Music');
if($_GET['id']==0)
{
$sql="select p.username, p.ptitle, p.pid from Playlist p where p.username='$username'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
    echo"<table id='customers'>";
    while($row=mysqli_fetch_assoc($query1)){
        $username = $row[ 'username' ];
        $ptitle = $row[ 'ptitle' ];
        $pid=$row[ 'pid' ];
        echo"<tr><td><p><a href='User.php?id=3&pid=".$pid."' style='color: blue; '>{$ptitle}</a>  <a href='Playlistedit.php?pid=".$pid."&id=6' style='color: blue; text-align: right; float: right;'>Delete</a>  <a href='Playlistedit.php?pid=".$pid."&id=1' style='color: blue; margin-right : 2mm; text-align: right; float: right;'>edit</a></p></td></tr>";
    }
}
}
if($_GET['id']==1)
{
$_SESSION['pid']=$_GET['pid'];
$pid=$_SESSION['pid'];
$sql="select ptitle from Playlist where pid='$pid'";
$query = mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($query);
echo "<strong><font size='5'> {$row[ 'ptitle' ]} </font></strong>";
$sql="select p.ptitle,t.ttitle,t.tid,pt.porder from Playlist p, Tracks t, PlTracks pt where p.pid=pt.pid and pt.tid=t.tid and p.pid='$pid'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
while($row=mysqli_fetch_assoc($query1)){
	$ptitle = $row[ 'ptitle' ];
	$ttitle = $row[ 'ttitle' ];
	$tid = $row[ 'tid' ];
	$porder = $row['porder'];
	echo"<tr><td><a href='Play.php?link=".$tid."&id=1' style='color: blue;'>{$ttitle} <a href='Playlistedit.php?id=2&tid=$tid&order=$porder' style='color: blue; text-align: right; float: right;'>Delete</a></p></td></tr>";
}
}
}
if($_GET['id']==2)
{   
$tid = $_GET['tid'];
$pid = $_SESSION['pid'];
$porder = $_GET['order'];
$sql=" DELETE FROM PlTracks WHERE pid='$pid' and tid='$tid'";
$query1 = mysqli_query($con,$sql);
mysqli_query($con, $sql);
$sql="select ptitle from Playlist where pid='$pid'";
$query = mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($query);
echo "<strong><font size='5'> {$row[ 'ptitle' ]} </font></strong>";
$sql="select p.ptitle,t.ttitle,t.tid,pt.porder from Playlist p, Tracks t, PlTracks pt where p.pid=pt.pid and pt.tid=t.tid and p.pid='$pid'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0)
 {
	echo"<table id='customers'>";
	while($row=mysqli_fetch_assoc($query1))
  {
	$ptitle = $row[ 'ptitle' ];
	$ttitle = $row[ 'ttitle' ];
	$tid = $row[ 'tid' ];
	$porder1 = $row['porder'];
	echo"<tr><td><a href='Play.php?link=".$tid."&id=1' style='color: blue;'>{$ttitle} <a href='Playlistedit.php?id=2&tid=$tid&order=$porder1' style='color: blue; text-align: right; float: right;'>Delete</a></p></td></tr>";
	if($porder1>$porder)
	{
	$porder1= $porder1-1;
	$sql="UPDATE PlTracks SET porder='$porder1' WHERE pid='$pid' and tid='$tid'";
	mysqli_query($con, $sql);
	}
  }
 }
}
if(isset($_POST['addsearch']))
{
$text1=mysqli_real_escape_string($con,$_POST['searchText']);
$text = '%'.$text1.'%';
$query = "SELECT ttitle, arname, tid FROM Tracks WHERE ttitle like '$text' ";
$results = mysqli_query($con, $query);
if(mysqli_num_rows($results)>0)
{
    echo"<table id='customers'>";
    echo"<tr><td>Track</td><td>Artist</td><td>Add to PlayList</td></tr>";
    while($row=mysqli_fetch_assoc($results))
    {
        $track = $row[ 'ttitle' ];
        $artist = $row[ 'arname' ];
	$tid = $row['tid'];
        echo"<tr><td><a href='Play.php?link=".$tid."&id=1' style='color: blue;'>{$track}</a> </td><td><a href='Artist.php?id=1&link=".$artist."' style='color: blue;'>{$artist}</a></td> <td>";
        echo "<div class='dropdown'> <button class='dropbtn'>Add</button>";
        $sql="select p.username, p.ptitle, p.pid from Playlist p where p.username='$username'";

$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
    echo '<div class=\'dropdown-content\'>';
    while($row=mysqli_fetch_assoc($query1)){
        $username = $row[ 'username' ];
        $ptitle = $row[ 'ptitle' ];
        $pid=$row[ 'pid' ];
        echo "<a href='Playlistedit.php?tid=$tid&pid=$pid&id=5'>{$ptitle} </a>";}
        echo '</div>'; }
        echo '</div>';
  	echo "</td></tr>";
    }
    echo"</table>";
}
else
{
    echo "No Tracks found";
}
}
if($_GET['id']==5)
{
$_SESSION['pid'] = $_GET['pid'];
$pid=$_SESSION['pid'] ;
$tid = $_GET['tid'];
$sql=" select max(porder) from PlTracks where pid='$pid'";
	$query1 = mysqli_query($con,$sql);
	$row=mysqli_fetch_assoc($query1);
	$order=$row[ 'max(porder)' ];
	$order=$order+1;
        $sql=" INSERT INTO `PlTracks` (`pid`, `tid`, `porder`) VALUES ('$pid', '$tid', '$order')";
	mysqli_query($con, $sql);
$sql="select ptitle from Playlist where pid='$pid'";
$query = mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($query);
echo "<strong><font size='5'> {$row[ 'ptitle' ]} </font></strong>";
$sql="select p.ptitle,t.ttitle,t.tid,pt.porder from Playlist p, Tracks t, PlTracks pt where p.pid=pt.pid and pt.tid=t.tid and p.pid='$pid'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
while($row=mysqli_fetch_assoc($query1)){
	$ptitle = $row[ 'ptitle' ];
	$ttitle = $row[ 'ttitle' ];
	$tid = $row[ 'tid' ];
	$porder = $row['porder'];
	echo"<tr><td><a href='Play.php?link=".$tid."&id=1' style='color: blue;'>{$ttitle} <a href='Playlistedit.php?id=2&tid=$tid&order=$porder' style='color: blue; text-align: right; float: right;'>Delete</a></p></td></tr>";
}
}
}
if($_GET['id']==4)
{
$text=mysqli_real_escape_string($con,$_POST['plText']);
$plprivacy=mysqli_real_escape_string($con,$_POST['plprivacy']);
$sql="select max(pid) from Playlist";
$query1 = mysqli_query($con,$sql);
$row=mysqli_fetch_assoc($query1);
$pid=$row[ 'max(pid)' ];
$pid=$pid+1;
$sql=" INSERT INTO `Playlist` (`pid`, `ptitle`, `pdate`, `privacy`, `username`) VALUES ('$pid', '$text', CURDATE(), '$plprivacy', '$username')";
mysqli_query($con, $sql);
$sql="select p.username, p.ptitle, p.pid from Playlist p where p.username='$username'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
    echo"<table id='customers'>";
    while($row=mysqli_fetch_assoc($query1)){
        $username = $row[ 'username' ];
        $ptitle = $row[ 'ptitle' ];
        $pid=$row[ 'pid' ];
        echo"<tr><td><p><a href='User.php?id=3&pid=".$pid."' style='color: blue; '>{$ptitle}</a><a href='Playlistedit.php?pid=".$pid."&id=6' style='color: blue; text-align: right; float: right;'>Delete</a>  <a href='Playlistedit.php?pid=".$pid."&id=1' style='color: blue; margin-right : 2mm; text-align: right; float: right;'>edit</a></p></td></tr>";
    }
}
}
if($_GET['id']==6)
{
$_SESSION['pid']=$_GET['pid'];
$pid=$_SESSION['pid'];
$sql=" DELETE FROM PlTracks WHERE pid='$pid'";
$query1 = mysqli_query($con,$sql);
mysqli_query($con, $sql);
$sql=" DELETE FROM Playlist WHERE pid='$pid'";
$query1 = mysqli_query($con,$sql);
mysqli_query($con, $sql);
$sql="select p.username, p.ptitle, p.pid from Playlist p where p.username='$username'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
    echo"<table id='customers'>";
    while($row=mysqli_fetch_assoc($query1)){
        $username = $row[ 'username' ];
        $ptitle = $row[ 'ptitle' ];
        $pid=$row[ 'pid' ];
        echo"<tr><td><p><a href='User.php?id=3&pid=".$pid."' style='color: blue; '>{$ptitle}</a>  <a href='Playlistedit.php?pid=".$pid."&id=6' style='color: blue; text-align: right; float: right;'>Delete</a>  <a href='Playlistedit.php?pid=".$pid."&id=1' style='color: blue;margin-right : 2mm; text-align: right; float: right;'>edit</a></p></td></tr>";
    }
}
}
?>
</body>
</html>



