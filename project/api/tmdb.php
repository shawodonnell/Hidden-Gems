<?php 

include("../config/connection.php");
include("../config/functions.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//TMDB API KEY

include("../config/connection.php");
$sql = "SELECT password FROM `CSC7062_Admin` WHERE `adminID` = 1";

$result = $conn->query($sql);

if (!$result) {
        echo $conn->error;
    } 

while($row = $result->fetch_assoc()){
    extract($row);
    $data = array(
        'TMDBkey' => $password
    );
    
}

echo json_encode(mb_convert_encoding($data, "HTML-ENTITIES", "UTF-8"));



?>