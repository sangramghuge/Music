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
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="newcss.css">
<body>

<?php if (isset($_SESSION['username'])): ?>

<div class="w3-bar w3-black" >
    <form  method="post" action='search.php' id="searchform">
        <input  type="text" name="searchText" style="width:280px; margin-left: 12cm; margin-top : 2mm; background-color:white; border-radius: 25px;" required>
        <input  type="submit" class ="btn" name="submitsearch" value="Search" style="border-radius: 25px; background-color:white;">
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
<?php
$username = $_SESSION['username'];
//$con = mysqli_connect('localhost','root','root','Music');
if($_GET['id']==0)
{
$sql="select arname from Artist a, Likes l where a.arid=l.arid and username='$username'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
echo"<tr><td>Artists Liked</td></tr>";
while($row=mysqli_fetch_assoc($query1)){
	$arname = $row[ 'arname' ];
	echo"<tr><td><a href='Artist.php?id=1&link=".$arname."&id=1' style='color: red;'>{$arname}</td></tr>";
}
}
else
{
    echo "<div align=\"center\">Go Explore, Search for some Artists !</div>";
}
}     
if($_GET['id']==1)
{
$arname=$_GET['link'];
$sql="select t.ttitle,t.tid from Tracks t where t.arname='$arname'";
$query1 = mysqli_query($con,$sql);
echo "<a href='Play.php?link=".$arname."&id=4' style='color: red;'>Play All Songs"; //Play Artist Button
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
echo"<tr><td>ttitle</td></tr>";
while($row=mysqli_fetch_assoc($query1)){
	$ttitle = $row[ 'ttitle' ];
	$tid = $row[ 'tid' ];
	echo"<tr><td><a href='Play.php?id=1&link=".$tid."' style='color: red;'>{$ttitle}</td></tr>";
}
}
}
if($_GET['id']==2)
{
$arname=$_GET['link'];
$arid=$_GET['arid'];
$sql="INSERT INTO `Likes` (`username`, `arid`, `lstamp`) VALUES ('$username', '$arid', NOW())";
mysqli_query($con, $sql);
$sql="select arname from Artist a, Likes l where a.arid=l.arid and username='$username'";
$query1 = mysqli_query($con,$sql);
if(mysqli_num_rows($query1)>0){
echo"<table id='customers'>";
echo"<tr><td>Artists Liked</td></tr>";
while($row=mysqli_fetch_assoc($query1)){
	$arname = $row[ 'arname' ];
	echo"<tr><td><a href='Artist.php?id=1&link=".$arname."&id=1' style='color: red;'>{$arname}</td></tr>";
}
}
}
?>
</body>
</html>

