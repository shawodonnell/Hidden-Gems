<?php

//FUNCTION 1
function array_flatten($array)
{

    $return = array();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $return = array_merge($return, array_flatten($value));
        } else {
            $return[$key] = $value;
        }
    }
    return $return;
}
//FUNCTION 2
function addGenresToGenreTable($array)
{
    foreach ($array as $key => $value) {
        $genreSql = "INSERT INTO CSC7062_Genre (genreID, genre) VALUES (NULL, '{$value}')";
    }
}
//FUNCTION 3
function genreList()
{
    include("connection.php");

    $sql = "SELECT CSC7062_Genre.genre FROM CSC7062_Genre;";

    $result = $conn->query($sql);

    return $result;
}
//FUNCTION 4
function searchAPIadmin($query, $param)
{
    include("connection.php");

    $endpoint = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/api/api.php?{$query}={$param}";
    $result = file_get_contents($endpoint);
    if (!$result) {
        echo $conn->error;
        echo "Error:";
    } else {
        $data = json_decode($result, true);
    }
    return (array)$data;
}
//FUNCTION 4-2

function searchAPI($query, $param, $apikey)
{    
    include("connection.php");
    $endpoint = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/api/api.php?key={$apikey}&{$query}={$param}";
    $result = file_get_contents($endpoint);
    if (!$result) {
        echo $conn->error;
        echo "Error:";
    } else {
        $data = json_decode($result, true);
    }
    return (array)$data;
}


//FUNCTION 5
function categoryList()
{

    include("connection.php");

    $sql = "SELECT *
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = N'CSC7062_MovieDataSet';";

    $result = $conn->query($sql);

    $columnArray = array();

    while ($row = $result->fetch_assoc()) {
        foreach ($row as $key => $value) {
            array_push($columnArray, $row['COLUMN_NAME']);
        }
    }

    $columnArray = array_unique($columnArray);

    return $columnArray;
}
//FUNCTION 6
function yearChange($year)
{

    switch ($year) {
        case '2021':
            $year = 1;
            break;
        case '2020':
            $year = 2;
            break;
        case '2019':
            $year = 3;
            break;
        case '2018':
            $year = 4;
            break;
        case '2017':
            $year = 5;
            break;
        case '2016':
            $year = 6;
            break;
        case '2015':
            $year = 7;
            break;
        case '2014':
            $year = 8;
            break;
        case '2013':
            $year = 9;
            break;
        case '2012':
            $year = 10;
            break;
        case '2011':
            $year = 11;
            break;
        case '2010':
            $year = 12;
            break;
    }
    return $year;
}
//FUNCTION 7
function countryChange($country)
{
    switch ($country) {
        case 'USA':
            $country = 1;
            break;
    }
    return $country;
}
//FUNCTION 8
function langChange($lang)
{
    switch ($lang) {
        case 'English':
            $lang = 1;
            break;
    }
    return $lang;
}
//FUNCTION 9
function genreChange($genreName)
{

    include("connection.php");

    $query = "SELECT * FROM `CSC7062_Genre`";

    $result = $conn->query($query);

    if (!$result) {
        echo $conn->error;
    }

    while ($row = $result->fetch_assoc()) {
        if ($row['genre'] == $genreName) {
            $genreNum = $row['genreID'];
            return $genreNum;
        }
    }
}

//FUNCTION 10
function searchAdminUsers($key, $value)
{
    include("connection.php");
    $endpoint = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/admin/adminuser.php?{$key}={$value}";
    $result = file_get_contents($endpoint);
    if (!$result) {
        echo $conn->error;
        echo "Error:";
    } else {
        $data = json_decode($result, true);
    }
    return (array)$data;
}

//FUNCTION 11

function searchAdminMovies($key, $value) 
{
    include("connection.php");
    $endpoint = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/admin/adminmovie.php?{$key}={$value}";
    $result = file_get_contents($endpoint);
    if (!$result) {
        echo $conn->error;
        echo "Error:";
    } else {
        $data = json_decode($result, true);
    }
    return (array)$data;
}

//FUNCTION 12
function cleanseData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//FUNCTION 13
function generateToken()
{
    $token = bin2hex(openssl_random_pseudo_bytes(20));
    return $token;
}

//FUNCTION 14
function getDetailsTMDB($id,$TMDBkey){

    include("connection.php");
    $endpoint = "https://api.themoviedb.org/3/movie/{$id}?api_key={$TMDBkey}&language=en-US";
    $result = file_get_contents($endpoint);
    if (!$result) {
         echo "Error: ".  $conn->error;       
    } else {
        $data = json_decode($result, true);
    }
    return (array)$data;
}

//FUNCTION 15
function getCastTMDB($id,$TMDBkey){    
    
    include("connection.php");
    $endpoint = "https://api.themoviedb.org/3/movie/{$id}/credits?api_key={$TMDBkey}&language=en-US";
    $result = file_get_contents($endpoint);
    if (!$result) {
        echo "Error: ".  $conn->error;       
    } else {
        $data = json_decode($result, true);
    }

    //Main Cast
    $cast = $data['cast'];  
    $crew = $data['crew'];  
    $maincast = array();
    
    $keys = array_column($cast, 'popularity');
    array_multisort($keys, SORT_DESC, $cast);

    $count = count($cast);
    if($count>10){
        $count = 10;
    }

    for ($i=0; $i <= $count ; $i++) {
        array_push($maincast, $cast[$i],);
    }    

    for ($i=0; $i < count($crew); $i++) { 
    $dept = $crew[$i]['job'];
    if($dept=="Director"){
        array_push($maincast,$crew[$i]);
    }
    }
    
    return (array)$maincast;

}

//FUNCTION 16
function getRecosTMDB($id,$TMDBkey){

    include("connection.php");
    $endpoint = "https://api.themoviedb.org/3/movie/{$id}/recommendations?api_key={$TMDBkey}&language=en-US&page=1";
    $result = file_get_contents($endpoint);
    if (!$result) {
         echo "Error: ".  $conn->error;       
    } else {
        $data = json_decode($result, true);
    }

    $reco = $data['results'];       
    $recos = array();
    $keys = array_column($reco, 'popularity');
    array_multisort($keys, SORT_DESC, $reco);
    $count = count($reco);
    if($count>10){
        $count = 10;
    } 
    for ($i=0; $i <= $count; $i++) {   
        array_push($recos,$reco[$i]);
  }
  return (array)$recos;

}

//FUNCTION 17
function getProvidersTMDB($id,$TMDBkey){
    error_reporting(0);
    include("connection.php");
    $endpoint = "https://api.themoviedb.org/3/movie/{$id}/watch/providers?api_key={$TMDBkey}";
    $result = file_get_contents($endpoint);
    if (!$result) {
         echo "Error: ".  $conn->error;       
    } else {
        $data = json_decode($result, true);
    }
    
    $rent = $data['results']['GB']['rent'];
    $stream = $data['results']['GB']['flatrate'];    
    $providers = array();

    if(isset($rent[0]) && is_array($rent[0])){
        for ($i=0; $i < count($rent) ; $i++) { 
            array_push($providers, $rent[$i]);
        }    
    } 
    if(isset($stream[0]) && is_array($stream[0])){
        for ($i=0; $i < count($stream) ; $i++) { 
            array_push($providers, $stream[$i]);
        }    
    } 

    return (array)$providers;
}

//FUNCTION 18
function getPeopleTMDB($id,$TMDBkey){
    
    include("connection.php");
    $endpoint = "https://api.themoviedb.org/3/person/{$id}?api_key={$TMDBkey}&language=en-US";
    $result = file_get_contents($endpoint);
    if (!$result) {
         echo "Error: ".  $conn->error;       
    } else {
        $data = json_decode($result, true);
    }
    
    return (array)$data;
    

}

//FUNCTION 19
function getMovieCreditsTMDB($id,$TMDBkey){
    include("connection.php");
    $endpoint = "https://api.themoviedb.org/3/person/{$id}/movie_credits?api_key={$TMDBkey}&language=en-US";
    $result = file_get_contents($endpoint);
    if (!$result) {
         echo "Error: ".  $conn->error;       
    } else {
        $data = json_decode($result, true);
    }

    $credit = $data['cast'];       
    $credits = array();
    $keys = array_column($credit, 'popularity');
    array_multisort($keys, SORT_DESC, $credit);
    $count = count($credit);
    if($count>10){
        $count = 10;
    } 
    for ($i=0; $i <= $count; $i++) {   
        array_push($credits,$credit[$i]);
  }
  return (array)$credits;

}

//FUNCTION 20
function getTMDBkey(){

    include("connection.php");
    $sql = "SELECT password FROM `CSC7062_Admin` WHERE `adminID` = 1";
    $result = $conn->query($sql);
    if (!$result) {
        echo $conn->error;
    } 
    $row = $result->fetch_assoc();
    $TMDBkey = $row['password'];
    return $TMDBkey;
}

//FUNCTION 21
function addFavourite($imdbID, $userID, $TMDBid, $apikey){

    $addFavURL = "http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/api/api.php";

    $posteddata = http_build_query(
        array(
            'imdbID' => $imdbID,
            'userID' => $userID,
            'TMDBid' => $TMDBid,
            'apikey' => $apikey
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
    $result = file_get_contents($addFavURL, false, $context);
    echo $result;
}
