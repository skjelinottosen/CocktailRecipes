<?php
include 'db_connection.php';
session_start();
$loggedIn = False;
$conn = OpenCon();

function alert($message)
{
   echo '<script type="text/javascript">alert("' . $message . '")</script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

   $stmt = $conn->prepare("SELECT * FROM userAccount  WHERE email = ? ");
   $stmt->bind_param('s', $email);

   //Username and password sent from form
   $email = $_POST['email'];
   $password = $_POST['password'];

   //Rules for valid input
   $checkEpost = preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', $email);
   $checkPassord = preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}$/', $password);

   //Validate input
   if ($checkEpost && $checkPassord) {
      $stmt->execute();
      $result = $stmt->get_result();

      //Check for existing user
      if ($result->num_rows > 0) {
         while ($row = $result->fetch_assoc()) {
            $hash = $row["password"];
         }
         if (password_verify($password, $hash)) {
            $loggedIn = True;
            $_SESSION['user'] = $email;
            $_SESISON['pw'] = $password;
         }
      } else {
         alert("Login failed, please try again.");
      }
   }
   $stmt->close();
   CloseCon($conn);
}
?>
<!DOCTYPE html>
<html>

<head>
   <link rel="stylesheet" href="index.css">
   <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
   <div id="mastergrid">
      <nav class="nav">
         <ul>
            <?php
            if ($loggedIn == False) {
               echo
                  '<li class="right">
                     <button type="button"class="button" onclick=location.href="login.php">Login</button>
                     <button type="button" class="button" onclick=location.href="signup.php">Sign Up</button>
                  </li>';
            } else {
               echo
                  '<li class="right" >
                     <label> Logged in as' . ' <span id="display-username">' . $_SESSION['user'] . '</span></label>
                     <button type="button" class="button"><a href="./logout.php">Logout</a></button>
                  </li>';
               header("Location: ./index.php?");
            }
            ?>
         </ul>
      </nav>
      <!--Sign up form-->
      <main class="main">
         <img class="icon" src="./images/Icon.png" alt="icon">
         <section class="outerBox">
            <h2>Login</h2>
            <h3>Cocktail Recipes</h3>
            <section class="innerBox">
               <form action="" method="post">
                  <label>Email</label>
                  <input class="a-input" type="email" name="email" Placeholder="Email" pattern='^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$' required />
                  <label>Password</label>
                  <input class="a-input" type="password" name="password" Placeholder="Password" pattern='^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}' required />
                  <input class="a-btn" type="submit" value="Login" />
               </form>
            </section>
         </section>
      </main>
   </div>
</body>

</html>