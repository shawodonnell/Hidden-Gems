<?php
session_start();
include("../config/connection.php");
include("../config/functions.php");

if(!isset($_SESSION['loggedin'])){
header("location: adminlogin.php");
exit;
} else {
    $user = $_SESSION['loggedin'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <title>HIDDEN: GEM : ADMIN</title>
</head>

<body>
    <?php
    echo "<h2>Admin: {$user}</h2>";
    echo "Session Owner : ". $_SESSION['loggedin'];
    echo "
    <form action=\"adminlogout.php\" method=\"POST\" name=\"logoutadmin\">
        <input type=\"submit\" name=\"logoutbtn\" value=\"Log Out\">
    </form>";
    echo "<br>";
    ?>
    <!-- UPDATE MOVIE DETAILS -->
    <section class="updateMovie">
        <div class="updateMoviecontainer">
            <h2>Update Movie Details</h2>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" name="editmovie">
                <input type="submit" name="editmovieadmin" value="Submit / Reset Section">
            </form>

            <?php

            if (isset($_POST['editmovieadmin'])) { //STEP 1 - GET MOVIE DETAILS FOR EDITING 
                echo "
    <form method=\"POST\" name=\"editform\">
    <input type=\"text\" name=\"refToEdit\" id=\"refToEdit\" value=\"\" placeholder=\"Enter IMDB ref to edit\">
    <input type=\"text\" name=\"titleToEdit\" id=\"titleToEdit\" value=\"\" placeholder=\"OR Enter Title to edit\">        
    <input type=\"submit\" name=\"getdetailsforedit\" id=\"getdetailsforedit\" value=\"Retrieve movie details\">
    <input type=\"reset\" name=\"reseteditform\" id=\"reseteditform\">
    </form>
    ";
            }

            if (isset($_POST['getdetailsforedit'])) { //STEP 2 - EDIT MOVIE DETAILS

                if (empty($_POST['refToEdit'])) {
                    $key = "title";
                    $value = urlencode($_POST['titleToEdit']);
                    $results = searchAdminMovies($key, $value);
                } else if (empty($_POST['titleToEdit'])) {
                    $key = "ref";
                    $value = $_POST['refToEdit'];
                    $results = searchAdminMovies($key, $value);
                } else {
                    echo "Please enter search value";
                }

                if (!$results) { //VALIDATION CHECK IF MOVIE NOT FOUND
                    echo $conn->error;
                    echo "Movie Data Not Found";
                } else { //POPULATING PAGE WITH RETURNED DATA
                    echo "<form method=\"POST\" name=\"moviedetailsform\">";
                    foreach ($results as $key => $value) {

                        echo "                               
        <input type=\"text\" name=\"{$key}_r\" id=\"{$key}_r\" value=\"{$key}\" readonly disabled>
        <input type=\"text\" name=\"{$key}_edit\" id=\"{$key}_edit\" value=\"{$value}\"><br><br>
        ";
                    }
                    echo "
    <input type=\"submit\" name=\"confirmedit\" id=\"confirmedit\" value=\"Submit Edit\">
    <input type=\"reset\" name=\"resetdets\" >
    </form>";
                }
            }

            if (isset($_POST['confirmedit'])) { //STEP 3 - PROCESS AND PUT NEW DETAILS TO DATABASE

                $ref = $_POST['imdb_title_id_edit'];
                $title = $_POST['title_edit'];
                $duration = $_POST['duration_edit'];
                $director = $_POST['director_edit'];
                $writer = $_POST['writer_edit'];
                $production_company = $_POST['production_company_edit'];
                $castStr = $_POST['cast_edit'];
                $cast = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $castStr);
                $description = $_POST['description_edit'];
                $ave_votes = $_POST['ave_votes_edit'];
                $votes = $_POST['votes_edit'];
                $budget = $_POST['budget_edit'];
                $usa_gross = $_POST['usa_gross_edit'];
                $world_gross = $_POST['world_gross_edit'];
                $metascore = $_POST['metascore_edit'];
                $user_reviews = $_POST['user_reviews_edit'];
                $critic_reviews = $_POST['critic_reviews_edit'];
                $genre = genreChange($_POST['genre_edit']);
                $year = yearChange($_POST['year_edit']);
                $language = langChange($_POST['language_edit']);
                $CountryName = countryChange($_POST['CountryName_edit']);

                $putURL = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/admin/adminmovie.php";

                $posteddata = http_build_query(
                    array(
                        'ref' => $ref,
                        'title' => $title,
                        'duration' => $duration,
                        'director' => $director,
                        'writer' => $writer,
                        'production_company' => $production_company,
                        'cast' => $cast,
                        'description' => $description,
                        'ave_votes' => $ave_votes,
                        'votes' => $votes,
                        'budget' => $budget,
                        'usa_gross' => $usa_gross,
                        'world_gross' => $world_gross,
                        'metascore' => $metascore,
                        'user_reviews' => $user_reviews,
                        'critic_reviews' => $critic_reviews,
                        'year' => $year,
                        'language' => $language,
                        'country' => $CountryName,
                        'genre' => $genre,
                    )
                );

                $opts = array(
                    'http' => array(
                        'method' => 'PUT',
                        'header' => 'Content-Type: application/x-www-form-urlencoded',
                        'content' => $posteddata
                    )
                );

                $context = stream_context_create($opts);

                $result = file_get_contents($putURL, false, $context);

                echo $result;
            }
            ?>
        </div>
    </section>

    <!-- ADD NEW MOVIE TO DATABASE -->
    <section class="addmovie">
        <div class="addmoviecontainer">

            <h2>Add New Movie </h2>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="addmovieform">
                <input type="submit" name="addmovieadmin" id="" value="Submit / Reset Section">
            </form>

            <?php
            if (isset($_POST['addmovieadmin'])) { //STEP 1 - ENTER NEW MOVIE DETAILS
                echo "
            <form method=\"POST\" name=\"editform\">";
                $categories = categoryList();
                foreach ($categories as $key => $value) {
                    echo "                               
                <input type=\"text\" name=\"{$value}_r\" id=\"{$value}_r\" value=\"{$value}\" readonly disabled>
                <input type=\"text\" name=\"{$value}_add\" id=\"{$value}_add\" placeholder=\"{$value}\"><br><br>
                ";
                }
                echo "<input type=\"text\" name=\"genre_r\" id=\"genre_r\" value=\"genre\" readonly disabled>
            <input type=\"text\" name=\"genre_add1\" id=\"genre_add1\" placeholder=\"Enter Genre\">
            <input type=\"text\" name=\"genre_add2\" id=\"genre_add2\" placeholder=\"Enter Genre\">
            <input type=\"text\" name=\"genre_add3\" id=\"genre_add3\" placeholder=\"Enter Genre\"><br><br>    
            <input type=\"submit\" name=\"addmoviedetails\"  value=\"Add to Database\">
            <input type=\"reset\" name=\"resetaddform\">
            </form>
            ";
            }

            if (isset($_POST['addmoviedetails'])) { //STEP 2 - PROCESS NEW DETAILS TO API

                $ref = $_POST['imdb_title_id_add'];
                $title = $_POST['title_add'];
                $duration = $_POST['duration_add'];
                $director = $_POST['director_add'];
                $writer = $_POST['writer_add'];
                $production_company = $_POST['production_company_add'];
                $cast = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $_POST['cast_add']);
                $description = $_POST['description_add'];
                $ave_votes = $_POST['ave_votes_add'];
                $votes = $_POST['votes_add'];
                $budget = $_POST['budget_add'];
                $usa_gross = $_POST['usa_gross_add'];
                $world_gross = $_POST['world_gross_add'];
                $metascore = $_POST['metascore_add'];
                $user_reviews = $_POST['user_reviews_add'];
                $critic_reviews = $_POST['critic_reviews_add'];
                $year = yearChange($_POST['yearID_add']);
                $language = langChange($_POST['languageID_add']);
                $CountryName = countryChange($_POST['countryID_add']);

                $genre1 = genreChange($_POST['genre_add1']);
                $genre2 = genreChange($_POST['genre_add2']);
                $genre3 = genreChange($_POST['genre_add3']);
                $genre = $genre1 . "," . $genre2 . "," . $genre3;

                $endpointPOST = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/admin/adminmovie.php";
                $posteddata = http_build_query(
                    array(
                        'ref' => $ref,
                        'title' => $title,
                        'duration' => $duration,
                        'director' => $director,
                        'writer' => $writer,
                        'production_company' => $production_company,
                        'cast' => $cast,
                        'description' => $description,
                        'ave_votes' => $ave_votes,
                        'votes' => $votes,
                        'budget' => $budget,
                        'usa_gross' => $usa_gross,
                        'world_gross' => $world_gross,
                        'metascore' => $metascore,
                        'user_reviews' => $user_reviews,
                        'critic_reviews' => $critic_reviews,
                        'year' => $year,
                        'language' => $language,
                        'country' => $CountryName,
                        'genre' => $genre,
                    )
                );

                $opts = array(
                    'http' => array(
                        'method' => 'POST',
                        'header' => 'Content-Type: application/x-www-form-urlencoded',
                        'content' => $posteddata
                    )
                );

                $context = stream_context_create($opts);

                $result = file_get_contents($endpointPOST, false, $context);

                echo $result;
            }

            ?>

        </div>
    </section>

<!-- DELETE MOVIE SECTION -->
    <section>
        <div>
            <h2>Delete Movie Record</h2>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="deletemovieform">
                <input type="submit" name="deletemovieadmin" value="Submit / Reset Section">
            </form>

            <?php

            if (isset($_POST['deletemovieadmin'])) {
                echo "
<form method=\"POST\" name=\"deleteform\">
<input type=\"text\" name=\"refToDel\" id=\"refToDel\" value=\"\" placeholder=\"Enter IMDB ref to Delete\">
<input type=\"text\" name=\"titleToDel\" id=\"titleToDel\" value=\"\" placeholder=\"OR Enter Title to Delete\">        
<input type=\"submit\" name=\"deleteMoviebtn\" id=\"getdetailsforDel\" value=\"Delete Movie\">
<input type=\"reset\" name=\"resetDelform\" id=\"resetDelform\">
</form>
";
            }

            if (isset($_POST['deleteMoviebtn'])) {

                if (empty($_POST['refToDel'])) {
                    $key = "title";
                    $value = urlencode($_POST['titleToDel']);
                    $results = searchAdminMovies($key, $value);
                    $param = $_POST['titleToDel'];
                } else if (empty($_POST['titleToDel'])) {
                    $key = "ref";
                    $value = $_POST['refToDel'];;
                    $results = searchAdminMovies($key, $value);
                    $param = $_POST['refToDel'];
                }

                if (!$results) {
                    echo $conn->error;
                    echo "Movie Data Not Found";
                } else {

                    $key = "delete";
                    $value = $param;

                    $request = searchAdminMovies($key,$value);

                    if (!$request) {
                        echo $conn->error;
                    } else {
                        echo "Movie successfully deleted";
                    }
                }
            }
            ?>
        </div>
    </section>

<!-- USER MANAGEMENT SECTION -->
        
    <section>
        <div>
            <h2>User Management</h2>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="deleteuserform">
                <input type="submit" name="useradmin" value="Submit / Reset Section">
            </form>
            <br>
            

            <?php

            if (isset($_POST['useradmin'])) {
                echo "<form method=\"POST\" name=\"userDetailsForm\">
                <input type=\"text\" name=\"userID\"  value=\"\" placeholder=\"Enter User ID\">
                <input type=\"submit\" name=\"getuserbtn\" value=\"Retrieve User Details\">
                <input type=\"reset\" name=\"resetuserform\">
                </form>
                ";
            }

            if (isset($_POST['getuserbtn'])) {

                $key = "user";
                $value = $_POST['userID'];                
                $results = searchAdminUsers($key,$value);

                if(!$results){
                    echo $conn->error;
                    echo "No User Found";
                } else {
                    echo "<form method=\"POST\" name=\"userDetails\">";
                    foreach ($results as $key => $value) {
                        echo "                               
                    <input type=\"text\" name=\"{$key}_r\"  value=\"{$key}\" readonly disabled>
                    <input type=\"text\" name=\"{$key}_user\" id=\"{$value}\" value=\"{$value}\"><br><br>
                    ";
                    }
                    echo "
                    <input type=\"submit\" name=\"edituserbtn\" value=\"Submit Edited Details\">
                    <input type=\"submit\" name=\"deluserbtn\" value=\"Delete User\">
                    </form>
                    ";
                }
            }

            if (isset($_POST['edituserbtn'])){

                $userID = $_POST['id_user'];
                $first = $_POST['first_name_user'];
                $last = $_POST['last_name_user'];
                $email = $_POST['email_user'];    

                $endpoint = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/admin/adminuser.php";

                $posteddata = http_build_query(
                    array(
                        
                        'id' => $userID,
                        'first_name' => $first,
                        'last_name' => $last,
                        'email'=> $email
                    )
                );

                $opts = array(
                    'http' => array(
                        'method' => 'PUT',
                        'header' => 'Content-Type: application/x-www-form-urlencoded',
                        'content' => $posteddata
                    )
                );

                $context = stream_context_create($opts);

                $result = file_get_contents($endpoint, false, $context);

                echo $result;

            }

            if (isset($_POST['deluserbtn'])){

                $key = "delete";
                $value = $_POST['id_user'];
                
                $request = searchAdminUsers($key,$value);

                if (!$request) {
                    echo $conn->error;
                } else if ($request == 1) {
                    echo "User successfully deleted";
                }                               

            }


            ?>
        </div>
    </section>







    <script src="../js/admin.js"></script>
</body>

</html>