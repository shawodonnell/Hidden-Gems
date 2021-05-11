<?php
/* Displays all successful messages */
session_start();

if (!isset($_SESSION['userlogin'])) {
  echo '<script language="javascript">';
  echo 'alert("You must be logged in to see this page")';
  echo '</script>';
  header("location: index.php");    
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Success</title>
  <?php include 'css/css.html'; ?>
</head>
<body>
<div class="form">
    <h1>SUCCESSFULLY REGISTERED FOR HIDDENGEMS</h1>
    </p>
    <a href="index.php"><button class="button button-block"/>Home</button></a>
</div>
</body>
</html>
