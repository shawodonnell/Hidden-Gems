<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include("../config/connection.php");

if(!isset($_SESSION['loggedin'])){
  exit;
} 


//*********GET USER DETAILS FROM DATABASE */
if(isset($_GET['user'])){

    $userID = $conn->real_escape_string(trim($_GET['user']));

    $usersql = "SELECT  `id`, `first_name`, `last_name`, `email` FROM `CSC7062_Users` WHERE `id` = '{$userID}'";

    $result = $conn->query($usersql);

    if(!$result){
        echo $conn->error;
    } 

    while ($row = $result->fetch_assoc()) {
        extract($row);
        $details = array(
           'id' => $id,
           'first_name' => $first_name,
           'last_name' => $last_name,
           'email' => $email         
        );
    }    
    $data = json_encode(mb_convert_encoding($details, "HTML-ENTITIES", "UTF-8"));
    echo $data;

}

if(isset($_GET['delete'])){
    
    $param = $conn->real_escape_string(trim($_GET['delete']));

    $deletesql = "DELETE FROM `CSC7062_Users` WHERE `id` = '{$param}'";

    $result = $conn->query($deletesql);

    if(!$result){
        echo $conn->error;        
    } else {
        echo $result;
    }     
    
}

//************PUTTING AMENDED USER DATA */
if($_SERVER["REQUEST_METHOD"]==="PUT"){

    parse_str(file_get_contents("php://input"),$data);

    $userID = $data['id'];
    $first = $data['first_name'];
    $last = $data['last_name'];
    $email = $data['email'];

    $sql = "UPDATE `CSC7062_Users` SET `id`='{$userID}',`first_name`='{$first}',`last_name`='{$last}',`email`='{$email}' WHERE `id` = '{$userID}'";

    $result = $conn->query($sql);

    if(!$result){
        echo $conn->error;
        echo "User details could not be updated";
    } else {
        echo "User details successfully updated";
    }

}
