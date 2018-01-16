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

$db = mysqli_connect('localhost','root','root','Music');

?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" type="text/css" href="newcss.css">

<body background="ms.jpg">

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

<?php

if(isset($_GET['id']))
{
    $id=$_GET['id'];
    $link=$_GET['link'];
    $username=$_SESSION['username'];
    $playtrack;
    date_default_timezone_set('America/New_York');
    $date = date('Y-m-d h:i:s a', time());

    if($id==1) //only track played
    {
        $query = "INSERT INTO Plays(username, tid, pstamp, alid, pid) VALUES ('$username','$link','$date',NULL,NULL);";
        $results = mysqli_query($db, $query);
        echo "<div align=\"center\"><iframe align='center' src='https://open.spotify.com/embed?uri=spotify:track:".$link."'frameborder='0' allowtransparency='true'></iframe></div>";
        $playtrack=$link;




    }

    if($id==2)  //playlist played
    {
        $order = $_GET['order'];
        $query = "SELECT tid FROM PlTracks NATURAL JOIN Playlist WHERE pid ='$link'  and porder ='$order'";
        $results = mysqli_query($db, $query);
        $track;
        if(mysqli_num_rows($results)>0)
        {
            while($row=mysqli_fetch_assoc($results))
            {
                $track = $row[ 'tid' ];
                $query2 = "INSERT INTO Plays(username, tid, pstamp, alid, pid) VALUES ('$username','$track','$date',NULL,'$link');";
                $results2 = mysqli_query($db, $query2);
            }
            echo "<div align=\"center\"><iframe src='https://open.spotify.com/embed?uri=spotify:track:".$track."'frameborder='0' allowtransparency='true'></iframe></div>";
            $playtrack=$track;
            echo "<div align=\"center\"><a href='Play.php?link=".$link."&id=2&order=".($order+1)."' style='color: red;'>Play Next </a></div>"; //Play Playlist Button

        }
        else
        {
            echo "<div align=\"center\">End of Playlist</div>";
        }

    }
    if($id==3)
    {
        $order = $_GET['order'];
        $query = "SELECT tid FROM AlTracks NATURAL JOIN Album WHERE alid ='$link'  and aorder ='$order'";
        $results = mysqli_query($db, $query);
        $track;
        if(mysqli_num_rows($results)>0)
        {
            while($row=mysqli_fetch_assoc($results))
            {
                $track = $row[ 'tid' ];
                $query2 = "INSERT INTO Plays(username, tid, pstamp, alid, pid) VALUES ('$username','$track','$date','$link',NULL);";
                $results2 = mysqli_query($db, $query2);
            }
            echo "<div align=\"center\"><iframe src='https://open.spotify.com/embed?uri=spotify:track:".$track."'frameborder='0' allowtransparency='true'></iframe></div>";
            $playtrack=$track;
            echo "<div align=\"center\"><a href='Play.php?link=".$link."&id=3&order=".($order+1)."' style='color: red;'>Play Next </a></div>"; //Play Playlist Button

        }
        else
        {
            echo "<div align=\"center\">End of Album</div>";
        }
    }

    if($id==4)
    {
        $query = "select t.ttitle,t.tid from Tracks t where t.arname='$link' ORDER BY RAND() LIMIT 1;";
        $results = mysqli_query($db, $query);
        $track;
        if(mysqli_num_rows($results)>0)
        {
            while($row=mysqli_fetch_assoc($results))
            {
                $track = $row[ 'tid' ];
                $query2 = "INSERT INTO Plays(username, tid, pstamp, alid, pid) VALUES ('$username','$track','$date',NULL,NULL);";
                $results2 = mysqli_query($db, $query2);
            }
            echo "<div align=\"center\"><iframe allign='center' src='https://open.spotify.com/embed?uri=spotify:track:".$track."'frameborder='0' allowtransparency='true'></iframe></div>";
            $playtrack=$track;
            echo "<div align=\"center\"><a href='Play.php?link=".$link."&id=4' style='color: red;'>Play Next </a></div>"; //Play Playlist Button

        }
        else
        {
            echo "<div align=\"center\">End of Album</div>";
        }
    }
    $rating;

    if(isset($playtrack)) {
        $query = "SELECT * FROM rates WHERE username ='$username' and tid='$playtrack';";
        $results = mysqli_query($db, $query);
        if (mysqli_num_rows($results) > 0) {
            while ($row = mysqli_fetch_assoc($results)) {
                $rating = $row['rating'];
            }
            echo "<p style='color:black' align='center'>You gave this song a $rating star rating!</p>";

        } else {

            $_SESSION['PlayTrack'] = $playtrack;
            ?>
            <div style="color:black" align="center">
                <form>
                    <p id="message"> You havent rated this song yet!</p>
                    <p id="desc">Rate Song: </p><input type="button" value="1" id="b1" onclick="rates(this.value)">
                    <input type="button" value="2" id="b2" onclick="rates(this.value)">
                    <input type="button" value="3" id="b3" onclick="rates(this.value)">
                    <input type="button" value="4" id="b4" onclick="rates(this.value)">
                    <input type="button" value="5" id="b5" onclick="rates(this.value)">

                </form>
            </div>
            <script>
                function rates(str) {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.onreadystatechange = function () {
                        if (this.readyState == 4 && this.status == 200) {
                            document.getElementById("b1").style.visibility = "hidden";
                            document.getElementById("b2").style.visibility = "hidden";
                            document.getElementById("b3").style.visibility = "hidden";
                            document.getElementById("b4").style.visibility = "hidden";
                            document.getElementById("b5").style.visibility = "hidden";
                            document.getElementById("desc").innerHTML = this.responseText;
                            document.getElementById("message").innerHTML = "";

                        }
                    };
                    xmlhttp.open("GET", "rate.php?r=" + str, true);
                    xmlhttp.send();
                }
            </script>


            <?php


        }
    }


}

?>


</body>
</html>




