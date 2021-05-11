<?php
/* Main page with two forms: sign up and log in */
include("../config/connection.php");
session_start();
?>
<!DOCTYPE html>
<html>

<head>
  <title>Sign-Up/Login Form</title>
  <?php include 'css/css.html'; ?>
</head>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

  //*****LOGGING IN EXISTSING USER SECTION */
  if (isset($_POST['login'])) { 

    $email = $conn->real_escape_string(trim($_POST['email']));
    $result = $conn->query("SELECT * FROM CSC7062_Users WHERE email='$email'");

    if ($result->num_rows == 0) { 
      echo "<script language=\"javascript\">
      alert(\"No Details Found! Check email and password and try again\");
      </script>;";
      
    } else { 
      $user = $result->fetch_assoc();

      if (password_verify($_POST['password'], $user['password'])) {

        $_SESSION['userid'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name'] = $user['last_name'];
        $_SESSION['key'] = $user['tokenkey'];
        $_SESSION['userlogin'] = "yes";

      } 
      else 
      {
      echo "<script language=\"javascript\">
      alert(\"You have entered wrong password, try again!\");
      </script>;";        
      }

    }
    echo "<script language=\"javascript\">
    sessionStorage.setItem(\"loggedin\", \"true\");
    window.location.replace(\"http://sodonnell26.lampt.eeecs.qub.ac.uk/assignment/project/webpages/main.php#Main\");
    </script>;";

    
  //*****REGISTERING NEW USER SECTION */
  } elseif (isset($_POST['register'])) { 
    include("../config/functions.php");

    $first_name = $conn->real_escape_string(trim($_POST['firstname']));
    $last_name = $conn->real_escape_string(trim($_POST['lastname']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $password = $conn->real_escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));

    $result = $conn->query("SELECT * FROM CSC7062_Users WHERE email='$email'");
    if (!$result) {
      $conn->error;
    }

    if ($result->num_rows > 0) {
      echo "<script language=\"javascript\">
      alert(\"This email address has already been registered!\");
      </script>;";      
    } else {

      $token = generateToken();
      $sql = "INSERT INTO `CSC7062_Users`(`id`, `first_name`, `last_name`, `email`, `password`,`tokenkey`) VALUES (NULL,'$first_name','$last_name','$email','$password','$token')";

      $adduser = $conn->query($sql);

      if (!$adduser) {
        echo $conn->error;
      } else {
        echo "<script language=\"javascript\">
      alert(\"Successfully Registered!\");
      </script>;";
      }

    }

  }
}
?>

<body>
  <div class="form">

    <ul class="tab-group">
      <li class="tab"><a href="#signup">Sign Up</a></li>
      <li class="tab active"><a href="#login">Log In</a></li>
    </ul>

    <div class="tab-content">

      <div id="login">
        <h1>Welcome Back!</h1>

        <form action="index.php" method="post" autocomplete="off">

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email" required autocomplete="off" name="email" />
          </div>

          <div class="field-wrap">
            <label>Password<span class="req">*</span></label>
            <input type="password" required autocomplete="off" name="password" />
          </div>  
          <p class="forgot"><a href="forgot.php">Forgot Password?</a></p>
          
          <button class="button button-block" name="login">Log In</button>

        </form>

      </div>

      <div id="signup">
        <h1>Sign Up for Free</h1>

        <form action="index.php" method="post" autocomplete="off">

          <div class="top-row">
            <div class="field-wrap">
              <label>
                First Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name='firstname' />
            </div>

            <div class="field-wrap">
              <label>
                Last Name<span class="req">*</span>
              </label>
              <input type="text" required autocomplete="off" name='lastname' />
            </div>
          </div>

          <div class="field-wrap">
            <label>
              Email Address<span class="req">*</span>
            </label>
            <input type="email" required autocomplete="off" name='email' />
          </div>

          <div class="field-wrap">
            <label>
              Set A Password<span class="req">*</span>
            </label>
            <input type="password" required autocomplete="off" name='password' />
          </div>

          <button type="submit" class="button button-block" name="register" />Register</button>

        </form>

      </div>

    </div>

  </div>
  <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

  <script src="js/index.js"></script>

</body>

</html>