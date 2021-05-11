<?php
/* The password reset form, the link to this page is included
   from the forgot.php email message
*/
include("../config/connection.php");
session_start();

if (!isset($_SESSION['userlogin'])) {
  echo '<script language="javascript">';
  echo 'alert("You must be logged in to see this page")';
  echo '</script>';
  header("location: index.php");    
}

// Make sure email and hash variables aren't empty
if( isset($_GET['email']) && !empty($_GET['email']))
{
    $email = $conn->escape_string($_GET['email']);     

    // Make sure user email with matching hash exist
    $result = $conn->query("SELECT * FROM CSC7062_Users WHERE email='$email'");

    if ( $result->num_rows == 0 )
    { 
        echo "You have entered invalid URL for password reset!";
        
    }
}
else {
    echo "Sorry, verification failed, try again!";    
}
?>
<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Reset Your Password</title>
  <?php include 'css/css.html'; ?>
</head>

<body>
    <div class="form">

          <h1>Choose Your New Password</h1>
          
          <form action="reset_password.php" method="post">
              
          <div class="field-wrap">
            <label>
              New Password<span class="req">*</span>
            </label>
            <input type="password"required name="newpassword" autocomplete="off"/>
          </div>
              
          <div class="field-wrap">
            <label>
              Confirm New Password<span class="req">*</span>
            </label>
            <input type="password"required name="confirmpassword" autocomplete="off"/>
          </div>
          
          <!-- This input field is needed, to get the email of the user -->
          <input type="hidden" name="email" value="<?= $email ?>">          
              
          <button class="button button-block"/>Apply</button>
          
          </form>

    </div>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>

</body>
</html>
