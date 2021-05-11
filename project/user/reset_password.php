<?php
/* Password reset process, updates database with new user password */
include("../config/connection.php");
session_start();

if (!isset($_SESSION['userlogin'])) {
    echo '<script language="javascript">';
    echo 'alert("You must be logged in to see this page")';
    echo '</script>';
    header("location: index.php");    
  }
  
// Make sure the form is being submitted with method="post"
if ($_SERVER['REQUEST_METHOD'] == 'POST') { 

    // Make sure the two passwords match
    if ( $_POST['newpassword'] == $_POST['confirmpassword'] ) { 

        $new_password = password_hash($_POST['newpassword'], PASSWORD_BCRYPT);
        
        // We get $_POST['email'] and $_POST['hash'] from the hidden input field of reset.php form
        $email = $conn->real_escape_string(trim($_POST['email']));
        
        $sql = "UPDATE CSC7062_Users SET password='$new_password' WHERE email='$email'";

        if ( $conn->query($sql) ) {

        $_SESSION['message'] = "Your password has been reset successfully!";
        header("location: profile.php");  
        }

    }
    else {
        echo "Two passwords you entered don't match, try again!";           
    }

}
?>