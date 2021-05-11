<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include("../config/connection.php");

if(empty($_SESSION['loggedin'])){
    exit;
}   

if(isset($_GET['delete'])){
    $param = $conn->real_escape_string(trim($_GET['delete']));

    $deletesql = "DELETE FROM `CSC7062_MovieDataSet` WHERE `imdb_title_id` = '{$param}'";

    $result = $conn->query($deletesql);

    if(!$result){
        echo $conn->error;        
    } else {
        echo $result;
    }     
    
}

if(isset($_GET['title']) || isset($_GET['ref'])){
    
    if(isset($_GET['title'])){
        $param = $conn->real_escape_string(trim($_GET['title']));
        $where = "WHERE CSC7062_MovieDataSet.title LIKE '%{$param}%'";
    } else if(isset($_GET['ref'])){
        $param = $conn->real_escape_string(trim($_GET['ref']));
        $where = "WHERE CSC7062_MovieDataSet.imdb_title_id = '{$param}'";
    }
    
    $sql = "SELECT CSC7062_MovieDataSet.imdb_title_id, CSC7062_MovieDataSet.title, CSC7062_MovieDataSet.duration, CSC7062_MovieDataSet.director, 
            CSC7062_MovieDataSet.writer, CSC7062_MovieDataSet.production_company, CSC7062_MovieDataSet.cast, CSC7062_MovieDataSet.description, 
            CSC7062_MovieDataSet.ave_votes, CSC7062_MovieDataSet.votes, CSC7062_MovieDataSet.budget, CSC7062_MovieDataSet.usa_gross, 
            CSC7062_MovieDataSet.world_gross, CSC7062_MovieDataSet.metascore, CSC7062_MovieDataSet.user_reviews, 
            CSC7062_MovieDataSet.critic_reviews, CSC7062_Genre.genre, CSC7062_Year.year,CSC7062_Language.language, CSC7062_Country.CountryName 
            FROM CSC7062_MovieDataSet
            INNER JOIN CSC7062_Movie_Genre on CSC7062_MovieDataSet.imdb_title_id = CSC7062_Movie_Genre.imdb_title_id
            INNER JOIN CSC7062_Genre on CSC7062_Movie_Genre.genreID = CSC7062_Genre.genreID
            INNER JOIN CSC7062_Year on CSC7062_Year.yearID = CSC7062_MovieDataSet.yearID
            INNER JOIN CSC7062_Language on CSC7062_Language.LanguageID = CSC7062_MovieDataSet.languageID
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
    $data = json_encode(mb_convert_encoding($details, "HTML-ENTITIES", "UTF-8"));
    echo $data;
   } 

//************************************POSTING NEW MOVIE TO DATABASE */
if($_SERVER["REQUEST_METHOD"]==="POST"){

    $imdb_title_id = $_POST['ref'];
    $title = $_POST['title'];
    $duration = $_POST['duration'];
    $director = $_POST['director'];
    $writer = $_POST['writer'];
    $production_company = $_POST['production_company'];
    $cast = $_POST['cast'];
    $description = $_POST['description'];
    $ave_votes = $_POST['ave_votes'];
    $votes = $_POST['votes'];
    $budget = $_POST['budget'];
    $usa_gross = $_POST['usa_gross'];
    $world_gross = $_POST['world_gross'];
    $metascore = $_POST['metascore'];
    $user_reviews = $_POST['user_reviews'];
    $critic_reviews = $_POST['critic_reviews'];
    $year = $_POST['year'];
    $language = $_POST['language'];
    $CountryID = $_POST['country'];
    $genre = $_POST['genre'];

    $explode = explode(",",$genre);
    $genre1 = $explode[0];
    $genre2 = $explode[1];
    $genre3 = $explode[2];

    $addmoviemain = "INSERT INTO `CSC7062_MovieDataSet`(`imdb_title_id`, `title`, `yearID`, `duration`, `countryID`, `languageID`, `director`, `writer`, `production_company`, `cast`, `description`, `ave_votes`, `votes`, `budget`, `usa_gross`, `world_gross`, `metascore`, `user_reviews`, `critic_reviews`) VALUES ('{$imdb_title_id}','{$title}','{$year}','{$duration}','{$CountryID}','{$language}','{$director}','{$writer}','{$production_company}','{$cast}','{$description}','{$ave_votes}','{$votes}','{$budget}','{$usa_gross}','{$world_gross}','{$metascore}','{$user_reviews}','{$critic_reviews}')";

    $result = $conn->query($addmoviemain);
   
    if(!$result){
       echo $conn->error;
   } 

   //Genre Add
   if(!empty($genre3)){
    $genreadd = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$imdb_title_id}','{$genre1}')";
    $result = $conn->query($genreadd);

    $genreadd2 = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$imdb_title_id}','{$genre2}')";
    $result = $conn->query($genreadd2);

    $genreadd3 = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$imdb_title_id}','{$genre3}')";
    $result = $conn->query($genreadd3);

   } else if (empty($genre3) && !empty($genre2)){

    $genreadd = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$imdb_title_id}','{$genre1}')";
    $result = $conn->query($genreadd);

    $genreadd2 = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$imdb_title_id}','{$genre2}')";
    $result = $conn->query($genreadd2);    

   } else if (empty($genre2) && !empty($genre1)){
    $genreadd = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$imdb_title_id}','{$genre1}')";   
    $result = $conn->query($genreadd);
 
   } else {
       
   }
   echo $title." has been successfully added to database";
}

//***************** UPDATE MOVIE WITH PUT REQUEST
if($_SERVER['REQUEST_METHOD']==='PUT'){

    parse_str(file_get_contents("php://input"),$data);
        
    $ref = $data['ref'];     
    $title = $data['title'];
    $duration = $data['duration'];
    $director = $data['director'];
    $writer = $data['writer'];
    $production_company = $data['production_company'];
    $cast = $data['cast'];
    $description = $data['description'];
    $ave_votes = $data['ave_votes'];
    $votes = $data['votes'];
    $budget = $data['budget'];
    $usa_gross = $data['usa_gross'];
    $world_gross = $data['world_gross'];
    $metascore = $data['metascore'];
    $user_reviews = $data['user_reviews'];
    $critic_reviews = $data['critic_reviews'];
    $year = $data['year'];
    $language = $data['language'];
    $CountryName = $data['country'];
    $genre = $data['genre'];
    
    $movieUpdate = "    
        UPDATE `CSC7062_MovieDataSet` SET 
        `imdb_title_id`='{$ref}',
        `title`='{$title}',
        `yearID`='{$year}',
        `duration`='{$duration}',
        `countryID`='{$CountryName}',
        `languageID`='{$language}',
        `director`='{$director}',
        `writer`='{$writer}',
        `production_company`='{$production_company}',
        `cast`='{$cast}',
        `description`='{$description}',
        `ave_votes`='{$ave_votes}',
        `votes`='{$votes}',
        `budget`='{$budget}',
        `usa_gross`='{$usa_gross}',
        `world_gross`='{$world_gross}',
        `metascore`='{$metascore}',
        `user_reviews`='{$user_reviews}',
        `critic_reviews`='{$critic_reviews}' WHERE CSC7062_MovieDataSet.imdb_title_id = '{$ref}'";
        
    $genreUpdate = "UPDATE `CSC7062_Movie_Genre` SET `imdb_title_id`='{$ref}',`genreID`='$genre' WHERE imdb_title_id = '{$ref}';";
    
    

    $updateresult = $conn->query($movieUpdate);
    
        if(!$updateresult){
            echo $conn->error;
        } else {
            $updateresult2 = $conn->query($genreUpdate);
            if(!$updateresult2){
                echo $conn->error;
            }else{
                echo "Database Updated Successfully for ".$title;                
            }
        }
    }


?>