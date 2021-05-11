<?php
require("../connection.php");
include("../functions.php");
echo "<br>";


/* Map Rows and Loop Through Them */
$rows   = array_map('str_getcsv', file('DataSetMain.csv'));
$header = array_shift($rows);
$numofHeaders = count($header);
$csv = array();

//Adding headers and row data together in one array
foreach($rows as $row) {   
    $csv[] = array_combine($header, $row);
}

//*********************ADDING MOVIE DATA TO DATABASE 

foreach($csv as $key => $value){

$year = $csv[$key]['year'];
$yearNum = yearChange($year);

$country = $csv[$key]['country'];
$countryNum = countryChange($country);

$language = $csv[$key]['language'];
$langNum = langChange($language);


$movieSql = "INSERT INTO `CSC7062_MovieDataSet` (`imdb_title_id`, `title`, `yearID`, `duration`, `countryID`, `languageID`, `director`, `writer`, `production_company`, `cast`, `description`, `ave_votes`, `votes`, `budget`, `usa_gross`, `world_gross`, `metascore`, `user_reviews`, `critic_reviews`) VALUES ('{$csv[$key]['imdb_title_id']}', '{$csv[$key]['title']}', '$yearNum', '{$csv[$key]['duration']}', '$country', '$language', '{$csv[$key]['director']}', '{$csv[$key]['writer']}', '{$csv[$key]['production_company']}', '{$csv[$key]['actors']}','{$csv[$key]['description']}', '{$csv[$key]['avg_vote']}','{$csv[$key]['votes']}','{$csv[$key]['budget']}', '{$csv[$key]['usa_gross_income']}', '{$csv[$key]['worlwide_gross_income']}', '{$csv[$key]['metascore']}', '{$csv[$key]['reviews_from_users']}', '{$csv[$key]['reviews_from_critics']}')";
if($conn->query($movieSql) === TRUE) {
    echo "Movie added to database";
 } else {
    echo "Error: " . $conn->error;
}

}
echo "ADDED {$moviecounter} MOVIES TO DATABASE";


//************************************Populating Movie_Genre Table

$genreArray = array();

foreach($csv as $key => $value){
    $genre = $csv[$key]['genre'];
    $explode = explode(",",$genre);

    if(count($explode)==1 && !in_array($explode,$genreArray)){
    array_push($genreArray,$explode);    
} 
}

$genreArray = array_flatten($genreArray);

addGenresToGenreTable($genreArray);


//Populating Movie_Genre table on database
foreach($csv as $key => $value){
    $titleStr = $csv[$key]['imdb_title_id'];
    $genreStr = explode(",",$csv[$key]['genre']);

    if(count($genreStr)==3){    
    $genre1 = genreChange($genreStr[0]);
    $genre2 = genreChange($genreStr[1]);
    $genre3 = genreChange($genreStr[2]);
    $genreadd = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$titleStr}','{$genre1}')";
    $result = $conn->query($genreadd);    
    $genreadd2 = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$titleStr}','{$genre2}')";
    $result = $conn->query($genreadd2);    
    $genreadd3 = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$titleStr}','{$genre3}')";
    $result = $conn->query($genreadd3);

    } 
    else if(count($genreStr)==2){

    $genre1 = genreChange($genreStr[0]);
    $genre2 = genreChange($genreStr[1]);
    $genreadd = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$titleStr}','{$genre1}')";
    $result = $conn->query($genreadd);    
    $genreadd2 = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$titleStr}','{$genre2}')";
    $result = $conn->query($genreadd2);   

    } 
    else if 
    (count($genreStr)==1){


    $genre1 = genreChange($genreStr[0]);       
    $genreadd = "INSERT INTO `CSC7062_Movie_Genre`(`moviegenreID`, `imdb_title_id`, `genreID`) VALUES (NULL,'{$titleStr}','{$genre1}')";   
    $result = $conn->query($genreadd);

    } 
    else 
    {
           echo "DONE";
       }
    } 
    


?>