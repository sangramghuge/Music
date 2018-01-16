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
        <input  type="text" name="searchText" style="width:280px; margin-left: 9.5cm; margin-top : 2mm; background-color:white; border-radius: 25px;" required>
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
		$sql="select u.username, t.ttitle, t.arname, t.tid from Tracks t, Artist a, Likes l, Users u where u.username=l.username and 			l.arid=a.arid and a.arname=t.arname and u.username='$username' ORDER BY rand() limit 10";
		$query1 = mysqli_query($con,$sql);

		if(mysqli_num_rows($query1)>0){

		echo "<table id='customers'>";
		echo"<tr><td colspan='2' align='center' ><strong>Songs by artist you like</strong></td></tr>";
		while($row=mysqli_fetch_assoc($query1)){
			$username = $row[ 'username' ];
			$ttitle = $row[ 'ttitle' ];
			$arname = $row[ 'arname' ];
			$tid = $row[ 'tid' ];
			echo"<tr><td><a href='Play.php?link=".$tid."&id=1' style='color: blue;'>{$ttitle}</a></td><td><a href='Artist.php?id=1&link=".$arname."' style='color: blue;'>{$arname}</a></td></tr>";
		}
		}?>
  <?php $username = $_SESSION['username'];
		$sql="select p.tid, t.ttitle, p.username from Plays p, Tracks t where p.tid=t.tid and username='$username' ORDER BY rand() limit 10";
		$query1 = mysqli_query($con,$sql);

		if(mysqli_num_rows($query1)>0){

		echo "<table id='customers'>";
		echo"<tr><td colspan='2' align='center' ><strong>Revisit songs you played</strong></td></tr>";
		while($row=mysqli_fetch_assoc($query1)){
			$tid = $row[ 'tid' ];
			$ttitle = $row[ 'ttitle' ];
			echo"<tr><td align='center' ><a href='Play.php?link=".$tid."' style='color: blue;'>{$ttitle}</a></td></tr>";
		}
		}?>
<?php $username = $_SESSION['username'];
		$sql="select r.tid, t.ttitle from Rates r, Tracks t where r.tid=t.tid group by tid having AVG(rating)>3 ORDER BY rand() limit 10";
		$query1 = mysqli_query($con,$sql);

		if(mysqli_num_rows($query1)>0){

		echo "<table id='customers'>";
		echo"<tr><td colspan='2' align='center' ><strong>Chart Toppers</strong></td></tr>";
		while($row=mysqli_fetch_assoc($query1)){
			$tid = $row[ 'tid' ];
			$ttitle = $row[ 'ttitle' ];
			echo"<tr><td align='center' ><a href='Play.php?link=".$tid."&id=1' style='color: blue ;'>{$ttitle}</a></td></tr>";
		}
		}?>
</body>
</html>

