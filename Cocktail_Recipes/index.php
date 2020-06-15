<?php
include 'db_connection.php';
session_start();

if (!isset($_SESSION['user'])) {
   $loggedIn = False;
   header("Location: login.php");
} else {
   $loggedIn = True;
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
                  '<li class="right">
                        <span class="display-text">Logged in as ' . '<span class="display-username">' . $_SESSION['user'] . '</span></span>
                        <button type="button" class="button" onclick=location.href="logout.php">Logout</button
                     </li>';
            }
            ?>
         </ul>
      </nav>
      <!--Sign up form-->
      <main class="main">
         <section class="recipe-outerBox">
            <div class="recipe-innerBox">
               <h1>Vodka Martini</h1>
               <img class="cocktail-image" src="./images/Vodka_Martini.jpeg" alt="Vodka Martini">
               <ol>
                  <li>Combine vodka and dry vermouth in a cocktail mixing glass. Fill with ice and stir until chilled. Strain into a chilled martini glass.</li>
                  <li>Garnish with three olives on a toothpick.</li>
                  <ol>
            </div>
         </section>
      </main>
   </div>
</body>

</html>