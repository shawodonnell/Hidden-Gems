<?php
session_start();

include("../config/functions.php");
$userID = $_SESSION['userid'];
$key = $_SESSION['key'];

if(isset($_POST['favbtn'])){
    $imdbID = $_POST['favbtn'];
    $TMDBid = $_POST['TMDBid'];    
    echo $imdbID. " ".$userID." " .$TMDBid. " ". $key;
    addFavourite($imdbID,$userID, $TMDBid, $key);               
    header("location:../user/profile.php");
} else if(isset($_POST['deletebtn'])){
    $query = "delete";
    $param = $_POST['favID'];        
    searchAPI($query,$param,$key);
    header("location:../user/profile.php");
} else {
    header("location: main.php");
}



?>