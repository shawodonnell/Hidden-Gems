<?php

header('Content-Type: application/json');
include("../config/connection.php");

//****INDIVDUAL MOVIE SEARCH RESULTS BY TITLE
if (isset($_GET['title'])) {   

    $key = $_GET['key'];
    $movietitle = $conn->real_escape_string(trim($_GET['title']));
    $where = "WHERE CSC7062_MovieDataSet.title LIKE '%{$movietitle}%'";
    $results = databaseSearch($where, $key);
    echo $results;
}

//****INDIVDUAL MOVIE SEARCH RESULTS BY IMDB REFERENCE   
if (isset($_GET['ref'])) {
    
    $key = $_GET['key'];
    $imdbref = $conn->real_escape_string(trim($_GET['ref']));
    $where = "where CSC7062_MovieDataSet.imdb_title_id = '{$imdbref}'";
    $results = databaseSearch($where, $key);
    echo $results;
} 

//****MOVIE DIRECTOR SEARCH SECTION
if (isset($_GET['director'])) {

    $key = $_GET['key'];
    $directorsearch = $conn->real_escape_string(trim($_GET['director']));
    $where = "WHERE CSC7062_MovieDataSet.director LIKE '%{$directorsearch}%'";
    $results = databaseSearch($where, $key);
    echo $results;
}

//****MOVIE ACTOR SEARCH SECTION
if (isset($_GET['actor'])) {

    $key = $_GET['key'];
    $actorsearch = $conn->real_escape_string(trim($_GET['actor']));
    $where = "WHERE CSC7062_MovieDataSet.cast LIKE '%{$actorsearch}%'";
    $results = databaseSearch($where, $key);
    echo $results;
}
//****RANDOM MOVIE SEARCH SECTION */
if(isset($_GET['random'])){
    $key = $_GET['key'];
    $randomsearch = $conn->real_escape_string((trim($_GET['random'])));
    $where = "ORDER BY RAND() LIMIT 1";
    $results = databaseSearch($where, $key);
    echo $results;
}

//**********DATABASE SEARCH FUNCTION - MOVIES
function databaseSearch($where, $key){  
    
    include("../config/connection.php");

    $apiquery = "SELECT `tokenkey` FROM `CSC7062_Users` WHERE `tokenkey` = '{$key}'";
    $apiresult = $conn->query($apiquery);
    $num = $apiresult->num_rows;
    if($num>0){
        $sql = "SELECT CSC7062_MovieDataSet.imdb_title_id, CSC7062_MovieDataSet.title, CSC7062_MovieDataSet.duration, CSC7062_MovieDataSet.director, 
            CSC7062_MovieDataSet.writer, CSC7062_MovieDataSet.production_company, CSC7062_MovieDataSet.cast, CSC7062_MovieDataSet.description, 
            CSC7062_MovieDataSet.ave_votes, CSC7062_MovieDataSet.votes, CSC7062_MovieDataSet.budget, CSC7062_MovieDataSet.usa_gross, 
            CSC7062_MovieDataSet.world_gross, CSC7062_MovieDataSet.metascore, CSC7062_MovieDataSet.user_reviews, 
            CSC7062_MovieDataSet.critic_reviews, CSC7062_Genre.genre, CSC7062_Year.year,CSC7062_Language.language, CSC7062_Country.CountryName 
            FROM CSC7062_MovieDataSet
            INNER JOIN CSC7062_Movie_Genre on CSC7062_MovieDataSet.imdb_title_id = CSC7062_Movie_Genre.imdb_title_id
            INNER JOIN CSC7062_Genre on CSC7062_Movie_Genre.genreID = CSC7062_Genre.genreID
            INNER JOIN CSC7062_Year on CSC7062_Year.yearID = CSC7062_MovieDataSet.yearID
            INNER JOIN CSC7062_Language on CSC7062_Language.languageID = CSC7062_MovieDataSet.languageID
            INNER JOIN CSC7062_Country on CSC7062_Country.CountryID = CSC7062_MovieDataSet.countryID "
            . $where;

    $result = $conn->query($sql);

    if (!$result) {
        echo $conn->error;
    }

    while ($row = $result->fetch_assoc()) {
        extract($row);
        $details = array(
            'imdb_title_id' => $imdb_title_id,
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
            'genre' => $genre,
            'year' => $year,
            'language' => $language,
            'CountryName' => $CountryName
        );
    }
    return json_encode(mb_convert_encoding($details, "HTML-ENTITIES", "UTF-8"));

    } else {
        return "DB SEARCH: Error with API Key";
    }

    
}



//****GENRE SEARCH RESULTS BY GENRE - RANDOM 

if (isset($_GET['genre'])) {

    $key = $_GET['key'];
    $genre = $conn->real_escape_string(trim($_GET['genre']));
    
    $where = "WHERE CSC7062_Genre.genre = '{$genre}' AND CSC7062_MovieDataSet.metascore <> 1
        ORDER BY RAND()
        LIMIT 10";   

    $results = genreDBSearch($where, $key);

    echo $results;
}

//****GENRE SEARCH RESULTS BY GENRE - TOP TEN 

if (isset($_GET['topten'])) {

    $key = $_GET['key'];
    $genre = $conn->real_escape_string(trim($_GET['topten']));

    $where = "WHERE CSC7062_Genre.genre ='{$genre}' AND CSC7062_MovieDataSet.metascore <> 1
        ORDER BY CSC7062_MovieDataSet.metascore DESC
        LIMIT 10";    

    $results = genreDBSearch($where,$key);
    echo $results;
}

//****DATABASE SEARCH FUNCTION - GENRES
function genreDBSearch($where, $key){
    include("../config/connection.php");

    $apiquery = "SELECT `tokenkey` FROM `CSC7062_Users` WHERE `tokenkey` = '{$key}'";
    $apiresult = $conn->query($apiquery);
    $num = $apiresult->num_rows;
    if($num>0){

    $query = "SELECT DISTINCT CSC7062_MovieDataSet.imdb_title_id, CSC7062_MovieDataSet.title, CSC7062_MovieDataSet.director, CSC7062_MovieDataSet.writer, CSC7062_MovieDataSet.cast, CSC7062_MovieDataSet.description, CSC7062_MovieDataSet.metascore, CSC7062_Genre.genre, CSC7062_Year.year, CSC7062_Language.language 
        FROM CSC7062_MovieDataSet
        INNER JOIN CSC7062_Movie_Genre on CSC7062_MovieDataSet.imdb_title_id = CSC7062_Movie_Genre.imdb_title_id
        INNER JOIN CSC7062_Genre on CSC7062_Movie_Genre.genreID = CSC7062_Genre.genreID
        INNER JOIN CSC7062_Language on CSC7062_Language.languageID = CSC7062_MovieDataSet.languageID
        INNER JOIN CSC7062_Year on CSC7062_Year.yearID = CSC7062_MovieDataSet.yearID "
        . $where;

    $result = $conn->query($query);

    if (!$result) {
        echo $conn->error;
    }

    $moviearraytemp = array();
    $movie_array = array();

    while ($row = $result->fetch_assoc()) {
        extract($row);
        $genreresults = array(
            'imdb_title_id' => $imdb_title_id,
            'title' => $title,
            'director' => $director,
            'writer' => $writer,
            'cast' => $cast,
            'description' => $description,
            'metascore' => $metascore,
            'genre' => $genre,
            'year' => $year,
            'language' => $language
        );
        array_push($movie_array, $genreresults);
    }
    return json_encode(mb_convert_encoding($movie_array, "HTML-ENTITIES", "UTF-8"));
} else {
    return "GENRE DB SEARCH: Error with API key";
}
}




//****RETREIVING FAVOURITES SECTION
if(isset($_GET['favourite'])){

    $key = $_GET['key'];
    $userid = $_GET['favourite'];   

    $apiquery = "SELECT `tokenkey` FROM `CSC7062_Users` WHERE `tokenkey` = '{$key}'";
    $apiresult = $conn->query($apiquery);
    $num = $apiresult->num_rows;
    if($num>0) {
        $favSQL = "SELECT * FROM CSC7062_Favourite where CSC7062_Favourite.userID ='{$userid}'";

    $result = $conn->query($favSQL);

    if (!$result) {
        echo $conn->error;
    }

    $favourites = array();

    while ($row = $result->fetch_assoc()) {
         extract($row);
         $details = array(
             'favID' => $favID,
             'imdbID' => $imdbID,
             'userID' => $userID,
             'TMDB' => $TMDBid
        );
        array_push($favourites, $details);
    }
    $data = json_encode(mb_convert_encoding($favourites, "HTML-ENTITIES", "UTF-8"));
    echo $data;
    return $data;

    } else {
        echo "GET FAVS: Error with API key";
    }

}

//ADDING FAVOURITES TO DATABASE
if($_SERVER["REQUEST_METHOD"]==="POST"){

    $imdb_title_id = $_POST['imdbID'];
    $userID = $_POST['userID'];
    $TMDBid = $_POST['TMDBid'];
    $key = $_POST['apikey'];

    $apiquery = "SELECT `tokenkey` FROM `CSC7062_Users` WHERE `tokenkey` = '{$key}'";
    $apiresult = $conn->query($apiquery);
    $num = $apiresult->num_rows;
    if($num>0){

    $addFav = "INSERT INTO `CSC7062_Favourite`(`favID`, `userID`, `imdbID`, `TMDBid`) VALUES (NULL,'{$userID}','{$imdb_title_id}','{$TMDBid}')";
    
    $result = $conn->query($addFav);
   
    if(!$result){
       echo $conn->error;
   } else {
       return "Favourite Added";
   }
} else {
    echo "POST: Error with API Key";
}
}

//DELETING FAVOURITES
if (isset($_GET['delete'])){

    $key = $_GET['key'];
    $param = $conn->real_escape_string(trim($_GET['delete']));

    $apiquery = "SELECT `tokenkey` FROM `CSC7062_Users` WHERE `tokenkey` = '{$key}'";
    $apiresult = $conn->query($apiquery);
    $num = $apiresult->num_rows;
    if($num>0){

        $deletesql = "DELETE FROM `CSC7062_Favourite` WHERE `favID` = '{$param}'";
    
        $result = $conn->query($deletesql);
    
        if(!$result){
            echo $conn->error;        
        } else {
            echo $result;
        }  
    } else {
        echo "DELETE FAV: Error with API KEY";
    }  
    

}

?>