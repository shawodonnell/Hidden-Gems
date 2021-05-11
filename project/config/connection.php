<?php

$host = "sodonnell26.lampt.eeecs.qub.ac.uk";
$user = "sodonnell26";
$password = "kvYdq3z4ZcX5YVgq";
$database = "sodonnell26";

$conn = new mysqli($host, $user, $password,$database);

if($conn->connect_error){
   $check = "CONNECTION FAILED ".$conn->connect_error;
} 

/*
else {
   $check = "SUCCESS";
}
*/




?>