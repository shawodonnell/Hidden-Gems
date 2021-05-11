<?php
session_start();

include("../config/functions.php");
$genrelist = genreList();

if (isset($_SESSION['key'])) {
    $key = $_SESSION['key'];          
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/menus.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="icon" type="image/pgn"
        href="https://icons.iconarchive.com/icons/martz90/circle/24/video-camera-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>HIDDEN:GEMS:MAIN</title>
</head>

<body onload="document.body.style.opacity='1'">

    <div class="slideUnderLayer" id="slideUnderLayer" hidden></div>
    <!-- TOP HEADER MENU -->
    <header>
        <div class="" id="slideMenuButton"><i class="fas fa-bars"></i></div>
        <nav>
            <button class="nav" id="navButton1" title="Home"><a href="main.php"><i class="fas fa-home"></i></a></button>
            <button class="nav" id="navButton2" title="Search"><a href="#Main"><i class="fas fa-video"></i></a></button>
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
            <div class="Close" id="Close"><a href=""><i class="fas fa-window-close"></i>Close</a></div>
        </div>
    </aside>
    <!-- SOCIAL MENU -->
    <div class="fixedsideMenu" id="fixedsideMenu">
        <button class="fixedsideButton" id="fixed1"><i class="fab fa-facebook-f"></i></button>
        <button class="fixedsideButton" id="fixed2"><i class="fab fa-twitter"></i></button>
        <button class="fixedsideButton" id="fixed3"><i class="fab fa-instagram"></i></button>
    </div>
    <!-- WELCOME TITLE PAGE SECTION -->
    <section class="WelcomePage" id="welcome">
        <div id="curtaincontainer"></div>
        <h1 id="Title">HIDDEN:GEMS</h1>
        <div class="subtitletext" id="SubtitleText1">from the big screen</div>
        <div class="subtitletext" id="SubtitleText2"> to the small screen</div>
        <div class="subtitletext" id="SubtitleText3">the BEST unseen</div>
        <a id="nextSection" href="../user/index.php">
            <h2 id="WelcomeText">Tickets Please</h2>
        </a>

    </section>

    <!-- MAIN PAGE SECTION -->
    <!-- TILE SECTION -->
    <section class="Main" id="Main">
        <div class="maintilescontainer">
            <div class="tilecontainer">

                <div class="maintile upcomeTile" id="upcomeTile">
                    <h2 id="uph2" hidden>UP AND COMING</h2>
                    <div class="uptile" id="upcoming0"></div>
                    <div class="uptile" id="upcoming1"></div>
                    <div class="uptile" id="upcoming2"></div>
                </div>

                <div class="maintile trending" id="trendTile">
                    <h2 id="trh2" hidden>WEEKLY TRENDING</h2>
                    <ul class="trendlist" id="trendlist"></ul>
                </div>

                <div class="maintile wildcard" id="wildcardTile">
                    <h2>WILD CARD!</h2>
                    <h4>FEELING LUCKY OR CONFUSED.COM???</h4>
                    <p>USE OUR WILDCARD SEARCH FOR A TOTALLY RANDOM HIDDEN GEM!</p>
                    <form action="results.php" method="POST" name="wildcardsearch">
                        <input type="submit" name="wildcard" id="wildcardbtn" value="Wildcard!">
                    </form>
                </div>
            </div>
        </div>

        <!-- SEARCH SECTION -->
        <div class="mainsearchcontainer">
            <!-- TITLE AND REF SEARCH -->
            <div class="searchtile maintitleref">
                <form name="titlerefsearch" class="searchform" id="titlerefform" action="results.php" method="POST">
                    <input type="text" id="titletext" name="titletext" placeholder="Enter Movie Title">
                    <input type="text" id="reftext" name="reftext" placeholder="OR IMDB Reference">
                    <button type="reset" id="reset" name="resettitleref"><i class="fas fa-sync-alt"></i></button>
                    <button type="submit" name="titlerefbtn" id="titlerefbtn"><i class="fa fa-search"></i></button>                    
                </form>
            </div>
            <!-- GENRE SEARCH -->
            <div class="searchtile maingenre">
                <form name="genresearch"  class="searchform" action="results.php" method="POST">
                    <input type="text" id="genretext" list="genres" name="genretext" placeholder="Select Genre">

                    <?php //Dynamically building genre drop down list.
                    echo "<datalist id=\"genres\">";
                    while ($list = $genrelist->fetch_assoc()) {
                        echo "<option value={$list['genre']}></option>";
                    }
                    echo "</datalist>";
                    ?>
                     <label for="topTen" id="topTen">Top 10 Only</label>
                    <input type="checkbox" name="topTen" value="topTen">
                    <button type="submit" name="genrebtn" id="genrebtn"><i class="fa fa-search"></i></button>
                   

                </form>
            </div>
            <!-- ACTOR SEARCH -->
            <div class="searchtile mainactor">
                <form name="actorsearch" class="searchform" action="results.php" method="POST">
                    <input type="text" id="actortext" name="actortext" placeholder="Enter Actor's Name">
                    <button type="submit" name="actorbtn" id="actorbtn"><i class="fa fa-search"></i></button>
                </form>
            </div>
            <!-- DIRECTOR SEARCH -->

            <div class="searchtile maindirector">
                <form name="directorsearch" class="searchform" action="results.php" method="POST">
                    <input type="text" id="directortext" name="directortext" placeholder="Enter Director's Name">
                    <button type="submit" name="directorbtn" id="directorbtn"><i class="fa fa-search"></i></button>
                </form>
            </div>
        </div>

    </section>

    <footer></footer>
    <script src="../js/main.js"></script>

</body>

</html>