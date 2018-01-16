<?php
session_start();

// Login Check
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login.php');
}
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['username']);
    header("location: login.php");
}
$db = mysqli_connect('localhost', 'root', 'root', 'Music');
//variable declaration
if (isset($_POST['submitsearch']))
{
    $searchText1=mysqli_real_escape_string($db,$_POST['searchText']);
    $searchText = '%'.$searchText1.'%';
}
?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="newcss.css">
<head>
    <title>Registration system PHP and MySQL</title>
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
</head>
<body>

<?php if (isset($_SESSION['username'])): ?>

<div class="w3-bar w3-black" >
    <form  method="post" action='search.php' id="searchform">
        <input  type="text" name="searchText" style="width:280px; margin-left: 12cm; margin-top : 2mm; background-color:white; border-radius: 25px;" required>
        <input  type="submit" class ="btn" name="submitsearch" value="Search" style="border-radius: 25px; background-color:white;">
	<a href='Home.php?logout=1' class="w3-bar-item w3-button" style="margin-right: 0cm; float: right;">logout</a>
        <a href='Home.php?'  class="w3-bar-item w3-button">Home</a> 
        <p style="margin-top : 2mm;margin-right: 0cm; float: right;">Welcome <?php echo $_SESSION['username']; ?></p>
    </form>
</div>
<?php endif ?>

<div class="w3-bar w3-blue" ">
<a href='Playlistedit.php?id=0' class="w3-bar-item w3-button" >PlayLists</a>
<a href='User.php?id=0' class="w3-bar-item w3-button" >Follows</a>
<a href='Artist.php?id=0' class="w3-bar-item w3-button" >Artists</a>
</div>

<?php

// connect to database
$misscount=0;
$username = $_SESSION['username'];
//find users
$query = "SELECT username, name FROM Users WHERE name like '$searchText' and username!='$username'";
$results = mysqli_query($db, $query);
if(mysqli_num_rows($results)>0)
{
    echo"<table id='customers'>";
    echo"<tr><td>Username</td></tr>";
    while($row=mysqli_fetch_assoc($results))
    {
        $username1 = $row[ 'username' ];
        $name = $row[ 'name' ];
        $sql="select * from Follow where username1='$username' and username2='$username1'";
        $res=mysqli_query($db, $sql);
        if(mysqli_num_rows($res)==0)
        {
        echo "<tr><td>{$username1}</td><td><button class='dropbtn'><a href='User.php?id=4&uname=".$username1."'>Follow</a></button>";
        }
        else
        {
        echo "<tr><td><a href='User.php?id=1&uname=$username1' style='color: blue;'>{$username1}</td><td><button class='dropbtn'><a href='User.php?id=2&uname=".$username1."'>UnFollow</a></button>";
        }
        echo "</td></tr>";
    }
    echo"</table>";
}
else{$misscount+=1;}


//find artists
$query = "SELECT arname, ardesc, arid FROM Artist WHERE arname like '$searchText' ";
$results = mysqli_query($db, $query);
if(mysqli_num_rows($results)>0)
{
    echo"<table id='customers'>";
    echo"<tr><td>Artist</td><td>Genre</td></tr>";
    while($row=mysqli_fetch_assoc($results))
    {
        $arname = $row[ 'arname' ];
        $ardesc = $row[ 'ardesc' ];
        $arid = $row['arid'];
        $sql="select * from Likes l,Artist a where l.arid=a.arid and a.arname='$arname' and l.username='$username'";
        $res=mysqli_query($db, $sql);
        if(mysqli_num_rows($res)==0)
        {
        echo "<tr><td><a href='Artist.php?id=1&link=".$arname."' style='color: blue;'>{$arname}</a></td><td>$ardesc</td><td><a href='Artist.php?id=2&arid=$arid&link=".$arname."' style='color: blue;'>Like</a></button>";
        }
        else
        {
        echo "<tr><td><a href='Artist.php?id=1&link=".$arname."' style='color: blue;'>{$arname}</a></td><td>$ardesc</td>";
        }
        echo "</tr>";
    }
    echo"</table>";
}
else{$misscount+=1;}

//find track
$query = "SELECT ttitle, arname, tid FROM Tracks WHERE ttitle like '$searchText' ";
$results = mysqli_query($db, $query);
if(mysqli_num_rows($results)>0)
{
    echo"<table id='customers'>";
    echo"<tr><td>Track</td><td>Artist</td></tr>";
    while($row=mysqli_fetch_assoc($results))
    {
        $track = $row[ 'ttitle' ];
        $artist = $row[ 'arname' ];
	$tid = $row['tid'];
        echo"<tr><td><a href='Play.php?link=".$tid."&id=1' style='color: blue;'>{$track}</a> </td><td><a href='Artist.php?id=1&link=".$artist."' style='color: blue;'>{$artist}</a></td></tr>";
    }
    echo"</table><br>";
}
else{$misscount+=1;}
//find album
$query = "SELECT altitle, aldate, alid FROM Album WHERE altitle like '$searchText' ";
$results = mysqli_query($db, $query);
if(mysqli_num_rows($results)>0)
{
    echo"<table id='customers'>";
    echo"<tr><td>Album</td><td>ReleaseDate</td></tr>";
    while($row=mysqli_fetch_assoc($results))
    {
        $album = $row[ 'altitle' ];
        $rdate = $row[ 'aldate' ];
	$alid =$row['alid'];
        echo"<tr><td><a href='Album.php?link=".$alid."' style='color: blue;'>{$album}</a></td><td>{$rdate}</td></tr>";
    }
    echo"</table>";
}
else{$misscount+=1;}
if($misscount==4)
{
    echo "<table><tr><td>Oops! you have unique taste, try searching again?</td></tr></table>";
}

?>


</body>
</html>

