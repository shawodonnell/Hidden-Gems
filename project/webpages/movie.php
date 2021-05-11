<?php
session_start();
include("../config/functions.php");
error_reporting(0);
if(isset($_SESSION['key'])){
    $apikey = $_SESSION['key'];  
    $userID = $_SESSION['userid'];
    $email = $_SESSION['email'];
  } else {
      header("location: main.php");
  }
  
  if(isset($_POST['resultbtn'])){
    $id = $_POST['titleid'];
    $title = $_POST['titlename'];
    $TMDBid = $_POST['TMDBid'];    
    $query = "ref"; 
    $resultsSQL = searchAPI($query, $id, $apikey);    
  } 
  else if
  (isset($_GET['id'])) {
    $TMDBid = $_GET['id'];
  }
    $TMDBkey = getTMDBkey();   
    $details = getDetailsTMDB($TMDBid,$TMDBkey);
    $imdbID = $details['imdb_id'];
    $genrearray = $details['genres'];
    $cast = getCastTMDB($TMDBid,$TMDBkey);
    $director = end($cast);
    $director = $director['name'];
    $recos = getRecosTMDB($TMDBid,$TMDBkey);
    $watch = getProvidersTMDB($TMDBid,$TMDBkey);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/menus.css">
    <link rel="stylesheet" href="../css/movie.css">
    <link rel="icon" type="image/pgn"
        href="https://icons.iconarchive.com/icons/martz90/circle/24/video-camera-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>HIDDEN:GEMS:MOVIE</title>
</head>

<body onload="document.body.style.opacity='1'">

<div class="slideUnderLayer" id="slideUnderLayer" hidden></div>
    <!-- TOP HEADER MENU -->
    <header>
        <div class="" id="slideMenuButton"><i class="fas fa-bars"></i></div>
        <nav>
            <button class="nav" id="navButton1" title="Home"><a href="main.php"><i class="fas fa-home"></i></a></button>
            <button class="nav" id="navButton2" title="Results"><a href="results.php"><i class="fas fa-video"></i></a></button>
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


<!-- MOVIE DETAILS SECTION  -->

<?php
echo"
<form action=\"fav.php\" method=\"POST\" name=\"favform\">
    <input type=\"text\" name=\"TMDBid\" value=\"$TMDBid\" hidden readonly>
    <button type=\"submit\" name=\"favbtn\" class=\"favbtn\" value=\"$imdbID\" title=\"Add to Favourites\">
    <i id=\"favbtn\" class=\"fas fa-bookmark\"></i>
    </button>
</form>
";
//MOVIE DETAILS AND BANNER SECTION
echo "
<div class=\"bannercontainer\">
    <div class=\"moviecontainer\" id=\"mc\"style=\"background-image: url(http://image.tmdb.org/t/p/original{$details['backdrop_path']})\">
        <div class=\"detailscontainer\" id=\"dc\">            
            <div class=\"detail\" id=\"title\">{$details['title']}</div>
            <div class=\"detail\" id=\"tagline\">{$details['tagline']}</div>
            <div class=\"detail\" id=\"releaseDate\">{$details['release_date']}</div>
            <div class=\"detail\" id=\"runtime\">{$details['runtime']} minutes</div>
            <div class=\"detail\" class=\"genrelist\">
                <ul>";                    
                for ($i=0; $i < count($genrearray); $i++) { 
                    echo "<li class=\"detail genre\">{$genrearray[$i]['name']}</li>";
                }                    
echo "          </ul>  
            <div class=\"detail\" id=\"plot\">{$details['overview']}</div>           
            <div class=\"detail director\">
                <div>Director</div>
                <h2>{$director}</h2>            
            </div>
        </div> <!--DETAILS CONTAINER END  -->

        <div class =\"providerscontainer\">
        <h2> Available on:</h2>
            <div class=\"providers\">";
               
        for ($i=0; $i < count($watch) ; $i++) { 
            echo "
            <div class=\"provider\">
            <img src=\"http://image.tmdb.org/t/p/w45{$watch[$i]['logo_path']}\" alt=\"logo\">
            <p class=\"provider_name\">{$watch[$i]['provider_name']}</p>
            </div>
            ";
        }

echo "      </div> <!--  providers END-->
</div><!--  providercontainer END-->
</div> <!--  moviecontainer END-->
</div>  <!-- Bannercontainer END-->
";

//CAST AND RECOMMENDATIONS SECTION
echo "
<div class=\"castcontainer\">
<h2>Main Cast of {$details['title']}</h2>
    <div class=\"maincast\">";
for ($i=0; $i < count($cast) ; $i++) { 
    echo "
    <div class=\"cast\">
    <a class=\"castlink\" title=\"{$cast[$i]['name']}\" href=\"member.php?id={$cast[$i]['id']}\"><img src=\"http://image.tmdb.org/t/p/w185{$cast[$i]['profile_path']}\" alt=\"test\"></a>
    <a class=\"castname\" title=\"{$cast[$i]['name']}\" href=\"member.php?id={$cast[$i]['id']}\">{$cast[$i]['name']}</a>      
    </div>
    ";    
}
echo "  
    </div>
</div>

";

echo "
<div class=\"recocontainer\">
<h2>Other films like {$details['title']}</h2>
    <div class=\"recos\">";
for ($i=0; $i < count($recos) ; $i++) { 
    echo "    
    <div class=\"reco\">
    <a class=\"movielink\" title=\"{$recos[$i]['title']}\" href=\"movie.php?id={$recos[$i]['id']}\"><img src=\"http://image.tmdb.org/t/p/w185{$recos[$i]['poster_path']}\" alt=\"test\"></a>     
    </div>
    ";    
}
echo "  
    </div>
</div>


";

?>



    <script src="../js/movie.js"></script>
</body>
</html>