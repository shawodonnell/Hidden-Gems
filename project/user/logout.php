<?php
/* Log out process, unsets and destroys session variables */
session_start();

if (!isset($_SESSION['key'])) {
  echo '<script language="javascript">';
  echo 'alert("You must be logged in to see this page")';
  echo '</script>';
  header("location: ../webpages/main.php");    
} else {

session_unset();
session_destroy(); 
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Log Out</title>
  <?php include 'css/css.html'; ?>
</head>

<body>
    <div class="form">
          <h1>GoodBye! Cant wait to see you again!</h1>
              
          <p><?= 'logged out successfully'; ?></p>
          
          <a href="../webpages/main.php"><button class="button button-block">Home</button></a>

    </div>
</body>
</html>
