<?php
/* Displays user information and some useful messages */
session_start();
error_reporting(0);
include("../config/functions.php");

// Check if user is logged in using the session variable
if (!isset($_SESSION['userlogin'])) {
  echo '<script language="javascript">';
  echo 'alert("You must be logged in to see this page")';
  echo '</script>';
  header("location: ../webpages/main.php");    
}
else {
    // Makes it easier to read
    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $email = $_SESSION['email'];
    $key = $_SESSION['key'];
    $userID = $_SESSION['userid'];
    $TMDBkey = getTMDBkey();
}

$query = "favourite";
$param = $userID;
$dbfavs = searchAPI($query,$param,$key);

?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <link rel="icon" type="image/pgn"
        href="https://icons.iconarchive.com/icons/martz90/circle/24/video-camera-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>HIDDEN:GEMS:PROFILE</title>  
  <?php include 'css/css.html'; ?>
</head>

<body>
  <div class="form">

          <h1>Welcome</h1>
          
          <p>
                    
          <h2><?php echo $first_name.' '.$last_name; ?></h2>
          <p><?= $email ?></p>

          <p>Your API key is <?php echo $key?></p>

          <div class="favourites">
            <h2 id="favtitle">Favourites</h2>
            <?php
            if(empty($dbfavs)) {
                       echo "<p id=\"nofavs\">You have no Favourites to display</p>";
            } 
            else { 

              foreach ($dbfavs as $key => $value) {
                $TMDBid = $value['TMDB'];
                $favID = $value['favID'];
                $favs = getDetailsTMDB($TMDBid,$TMDBkey);   
                  foreach ($favs as $key => $value) {
                    if($key=='title'){
                      $title = $value;                      
                       echo "            
            <div class=\"fav\">
              <div class=\"name\"><p>{$title}</p></div>              
              <form action=\"../webpages/movie.php\" method=\"POST\" name=\"profilefavform\">
                <input type=\"text\" name=\"titleid\" value=\"{$imdbid}\" hidden>
                <input type=\"text\" name=\"titlename\" value=\"{$title}\" hidden>
                <input type=\"text\" name=\"TMDBid\" value=\"{$TMDBid}\" hidden>
                <button type=\"submit\" class=\"btn\" name=\"resultbtn\" title=\"Movie Details\"><i class=\"fas fa-video\"></i></button>
                </form>
                <form action=\"../webpages/fav.php\" method=\"POST\" name=\"deletefav\">
                <input type=\"text\" name=\"favID\" value=\"{$favID}\" hidden>
                <button type=\"submit\" class=\"btn\" name=\"deletebtn\" title=\"Delete Favourite\"><i class=\"fas fa-trash-alt\"></i></button> 
              </form> 
            </div>            
            ";
                    }                    
                  }             
              }
            }
            ?>


          </div>

          <a href="../webpages/main.php#Main"><button class="button button-block" name="home">Home</button></a>
          <a href="logout.php"><button class="button button-block" name="logout">Log Out</button></a>

    </div>
    
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
