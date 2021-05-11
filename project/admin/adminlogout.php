<?php

if(!isset($_SESSION['loggedin'])){
    header("location: adminlogin.php");
} else {
    unset($_SESSION['loggedin']);
    session_destroy();
    header("location: adminlogin.php");
}


?>