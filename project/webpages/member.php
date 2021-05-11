<?php
session_start();
include("../config/functions.php");

if(isset($_SESSION['key'])){
    $apikey = $_SESSION['key'];  
  } else {
      header("location: main.php");
  }

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $key = getTMDBkey();
    $details = getPeopleTMDB($id,$key);
    $credits = getMovieCreditsTMDB($id,$key);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/menus.css">
    <link rel="stylesheet" href="../css/member.css">
    <link rel="icon" type="image/pgn"
        href="https://icons.iconarchive.com/icons/martz90/circle/24/video-camera-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>HIDDEN:GEMS:PEOPLE</title>
</head>
<body onload="document.body.style.opacity='1'">

<div class="slideUnderLayer" id="slideUnderLayer" hidden></div>
    <!-- TOP HEADER MENU -->
    <header>
        <div class="" id="slideMenuButton"><i class="fas fa-bars"></i></div>
        <nav>
            <button class="nav" id="navButton1" title="Home"><a href="main.php"><i class="fas fa-home"></i></a></button>
            <button class="nav" id="navButton2" title="Results"><a href="movie.php"><i class="fas fa-video"></i></a></button>
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

<!-- PERSON DETAILS SECTION -->
<?php
   echo"
    <div class=\"membercontainer\">
        <div class=\"profile\"><img src=\"http://image.tmdb.org/t/p/w185${details['profile_path']}\" alt=\"\"></div>
        <div class=\"detailscontainer\">
            <div class=\"details\" id=\"name\">{$details['name']}</div>
            <div class=\"details\" id=\"birthday\">{$details['birthday']}</div>
            <a href=\"https://www.imdb.com/name/{$details['imdb_id']}\" target=\"_blank\"><i id=\"logo\" class=\"fab fa-imdb\"></i></a>          
        </div>  
    </div> 
    
    <div class=\"moviecontainer\">
            <h2>Other films that {$details['name']} has starred in</h2>
            <div class=\"movies\">
            ";
                for ($i=0; $i < count($credits) ; $i++) { 
                    echo "    
                    <div class=\"movie\">
                        <a class=\"movielink\" title=\"{$credits[$i]['title']}\" href=\"movie.php?id={$credits[$i]['id']}\"><img src=\"http://image.tmdb.org/t/p/w185{$credits[$i]['poster_path']}\" alt=\"test\"></a>     
                    </div>
                ";}
echo "  
    </div> 
    </div>
    ";
    
?>
<script src="../js/movie.js"></script>
</body>
</html