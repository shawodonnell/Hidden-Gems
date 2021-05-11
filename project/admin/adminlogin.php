<?php
session_start();

include("../config/connection.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
</head>
<body>

<h2>Admin Login</h2>

<form method="POST" name="loginform">
    <input type="submit" name="existing" value="Admin Login" autocomplete="off">
    <input type="submit" name="registerNew" value="Register new Admin" autocomplete="off">
</form>

<?php

if(isset($_POST['existing'])){
    echo "
    <form method=\"POST\" name=\"loginform\">
    <input type=\"text\" name=\"username\" placeholder=\"Enter Username\" autocomplete=\"off\" required>
    <input type=\"text\" name=\"password\" placeholder=\"Enter Password\" autocomplete=\"off\" required>
    <input type=\"submit\" name=\"loginsubmit\" >
</form>
    ";
}

if(isset($_POST['loginsubmit'])){

    $username = $conn->real_escape_string(trim($_POST['username']));

    $result = $conn->query("SELECT * FROM CSC7062_Admin WHERE username ='$username'");

    if ( $result->num_rows == 0 ){ 
        echo "Admin details not found";        
    }
    else 
    { // User exists
        $user = $result->fetch_assoc();
        $inputpassword = $_POST['password'];
        $dbpassword = $user['password'];
    
        if ( password_verify($inputpassword,$dbpassword) ) {
            $_SESSION['loggedin'] = $username;            
            header("location: adminindex.php");
        }
        else {
            echo "Wrong Password";
        }
    }
}


if(isset($_POST['registerNew'])){
    echo "
    <form method=\"POST\" name=\"loginform\">
    <input type=\"text\" name=\"firstname\" placeholder=\"Enter First Name\" autocomplete=\"off\" required>
    <input type=\"text\" name=\"lastname\" placeholder=\"Enter Last Name\" autocomplete=\"off\" required>
    <input type=\"text\" name=\"username\" placeholder=\"Enter Username\" autocomplete=\"off\" required>
    <input type=\"text\" name=\"password\" placeholder=\"Enter Password\" autocomplete=\"off\" required>
    <input type=\"submit\" name=\"registersubmit\" >
</form>
    ";
}

if(isset($_POST['registersubmit'])){
    $first_name = $conn->real_escape_string(trim($_POST['firstname']));
    $last_name = $conn->real_escape_string(trim($_POST['lastname']));
    $uname = $conn->real_escape_string(trim($_POST['username']));
    $password = $conn->real_escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));

    // Check if user with that email already exists
    $result = $conn->query("SELECT * FROM CSC7062_Admin WHERE username ='$uname'");
    if(!$result){
        $conn->error;
    }

    // We know user email exists if the rows returned are more than 0
    if ( $result->num_rows > 0 ) {
        
        $_SESSION['message'] = 'Admin details already exist';       
        
    }
    else { // Email doesn't already exist in a database, proceed...

        $sql = "INSERT INTO `CSC7062_Admin`(`adminID`, `first_name`, `last_name`, `username`, `password`) VALUES (NULL,'$first_name','$last_name','$uname','$password')";

        $addadmin = $conn->query($sql);
        
        if (!$addadmin) {
            echo $conn->error;
        } else {
            echo "Successfully Registered";
            header("location: adminlogin.php");
        }
    
    }

}

?>








    
</body>
</html>