<?php
session_start();
include("../config/functions.php");
include("../config/connection.php");

if (isset($_SESSION['key'])) {
  $apikey = $_SESSION['key'];
} else {
  header("location: main.php");
}
//POSTED DATA PROCESSING 
if (isset($_POST['titlerefbtn'])) {
  if (empty($_POST['reftext'])) {
    $query = "title";
    $cleanse = cleanseData($_POST['titletext']);
    $param = urlencode($cleanse);
  } elseif (empty($_POST['titletext'])) {
    $query = "ref";
    $cleanse = cleanseData($_POST['reftext']);
    $param = urlencode($cleanse);
  }
} elseif (isset($_POST['actorbtn'])) {
  $query = "actor";
  $cleanse = cleanseData($_POST['actortext']);
  $param = urlencode($cleanse);
} elseif (isset($_POST['directorbtn'])) {
  $query = "director";
  $cleanse = cleanseData($_POST['directortext']);
  $param = urlencode($cleanse);
} elseif (isset($_POST['wildcard'])) {
  $query = "random";
  $cleanse = cleanseData($_POST['wildcard']);
  $param = urlencode($cleanse);
} elseif (isset($_POST['genrebtn'])) {
  $cleanse = cleanseData($_POST['genretext']);
  $param = urlencode($cleanse);
  if (isset($_POST['topTen'])) {
    $query = "topten";
  } else {
    $query = "genre";
  }
}
$results = searchAPI($query, $param, $apikey);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/menus.css">
  <link rel="stylesheet" href="../css/results.css">
  <link rel="icon" type="image/pgn" href="https://icons.iconarchive.com/icons/martz90/circle/24/video-camera-icon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>HIDDEN:GEMS:RESULTS</title>
</head>

<body onload="document.body.style.opacity='1'">

<div class="slideUnderLayer" id="slideUnderLayer" hidden></div>
    <!-- TOP HEADER MENU -->
    <header>
        <div class="" id="slideMenuButton"><i class="fas fa-bars"></i></div>
        <nav>
            <button class="nav" id="navButton1" title="Home"><a href="main.php"><i class="fas fa-home"></i></a></button>
            <button class="nav" id="navButton2" hidden><a href="movie.php"><i class="fas fa-video"></i></a></button>
            <button class="nav" id="navButton3" title="Profile"><a href="../user/profile.php"><i class="fas fa-user-circle"></i></a></button>
        </nav>
    </header>
    <!-- SLIDING MAIN MENU -->
    <aside class="slideSideMenu" id="slideSideMenu" hidden>

        <div class="UserCard">
            <div id="userWelcomeMessage">
                <p>WELCOME</p>
                <P><?php
                    if (isset($_SESSION['first_name'])) {
                        echo $_SESSION['first_name'];
                    }
                    ?></P>
            </div>
        </div>
        <div class="slideOptions">
            <div class="OptionsContainer">
                <div class="OptionsButton"><a href="../user/profile.php"><i class="fas fa-user"></i>User Profile</a></div>
            </div>
            <div class="OptionsContainer">
                <div class="OptionsButton"><a href="#Main"><i class="fas fa-bookmark"></i>Favourites</a></div>
            </div>
            <div class="OptionsContainer">
                <div class="OptionsButton"><a href="#Main"><i class="fas fa-question-circle"></i>Support</a></div>
            </div>
            <div class="OptionsContainer">
                <div class="OptionsButton"><a href="#Main"><i class="fas fa-at"></i>Contact</a></div>
            </div>
            <div class="OptionsContainer">
                <div class="OptionsButton"><a href="../user/logout.php"><i class="fas fa-sign-out-alt"></i>Log Out</a></div>
            </div>
        </div>
        <div class="slideFooter">
            <div class="Close" id="Close"><a href="#Main"><i class="fas fa-window-close"></i>Close</a></div>
        </div>
    </aside>
    <!-- SOCIAL MENU -->
    <div class="fixedsideMenu" id="fixedsideMenu">
        <button class="fixedsideButton" id="fixed1"><i class="fab fa-facebook-f"></i></button>
        <button class="fixedsideButton" id="fixed2"><i class="fab fa-twitter"></i></button>
        <button class="fixedsideButton" id="fixed3"><i class="fab fa-instagram"></i></button>
    </div>




<!-- RESULTS SECTION *****************************************************************-->
  <div class="main-results" id="main-results">
    <h1 id="resulth1">Heres what we found for you!</h1>

    <?php
    if(!$results){
    echo
    "<script language=\"javascript\">   
    alert(\"Movie Details Not Found, Please Search Again!\"); 
    window.location.replace(\"http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/webpages/main.php#Main\");
    </script>;";
    }

    if (isset($results[0]) && is_array($results[0])) {
      //if multidimensional array....     
      echo "<div class=\"resultsContainer\" id=\"results\">";  
      for ($i = 0; $i < count($results); $i++) {             
          echo "<div class=\"Cards multi\" id=\"result{$i}\">
                  <div id=\"posterw154\" name=\"posterw154\"></div>
                  <div id=\"resultdetails\">
                      <form action=\"movie.php\" method=\"POST\" name=\"movieform\" id=\"movieform\">
                        <input type=\"text\" name=\"titleid\" id=\"titleid\" readonly hidden value=\"{$results[$i]['imdb_title_id']}\">
                        <input type=\"text\"  name=\"titlename\" id=\"titlename\" readonly hidden value=\"{$results[$i]['title']}\">
                        <input type=\"submit\" name=\"resultbtn\" id=\"namebtn\" readonly value=\"{$results[$i]['title']}\">
                      </form>
                      <div class=\"resultchild\" name=\"director\" id=\"director\">Director: {$results[$i]['director']}</div>
                      <div class=\"resultchild\" name=\"genre\" id=\"genre\">{$results[$i]['genre']}</div>
                      <div class=\"resultchild\" name=\"year\" id=\"year\">{$results[$i]['year']}</div>
                      <div class=\"resultchild\" name=\"lang\" id=\"lang\">{$results[$i]['language']}</div>
                      <div class=\"resultchild\" name=\"metascore\" id=\"metascore\">Metascore: {$results[$i]['metascore']}</div>
                      <div class=\"resultchild\" name=\"plot\" id=\"plot\">{$results[$i]['description']}</div>
                  </div>
                </div> 
                      ";
        }
        echo "</div>"; //closing results div
        echo "<footer></footer>";

    } 
    else 
    { //if single movie....
      echo "<div class=\"Cards single\" id=\"singlemovie\">
                  <div id=\"posterw154\" name=\"posterw154\"></div>
                  <div id=\"resultdetails\">
                      <form action=\"movie.php\" method=\"POST\" name=\"movieform\" id=\"movieform\">
                        <input type=\"text\" name=\"titleid\" id=\"titleid\" readonly hidden value=\"{$results['imdb_title_id']}\">
                        <input type=\"text\"  name=\"titlename\" id=\"titlename\" readonly hidden value=\"{$results['title']}\">
                        <input type=\"submit\" name=\"resultbtn\" id=\"namebtn\" readonly value=\"{$results['title']}\">
                      </form>
                      <div class=\"resultchild\" name=\"director\" id=\"director\">Director: {$results['director']}</div>
                      <div class=\"resultchild\" name=\"genre\" id=\"genre\">{$results['genre']}</div>
                      <div class=\"resultchild\" name=\"year\" id=\"year\">{$results['year']}</div>
                      <div class=\"resultchild\" name=\"lang\" id=\"lang\">{$results['language']}</div>
                      <div class=\"resultchild\" name=\"metascore\" id=\"metascore\">Metascore: {$results['metascore']}</div>
                      <div class=\"resultchild\" name=\"plot\" id=\"plot\">{$results['description']}</div>
                  </div>
                </div> 
                      ";                
    }
    ?>

  </div>

  <script src="../js/results.js"></script>
</body>

</html>