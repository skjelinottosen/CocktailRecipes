<?php
include 'db_connection.php';
$conn = OpenCon();

function checkAge($year, $month, $day)
{
  if (date('Y') - $year > 18) {
    return true;
  } else {
    if (date('Y') - $year = 18) {
      if (date('m') - $month > 0) {
        return true;
      } else {
        if (date('m') - $month = 0) {
          if (date('d') - $day >= 0) {
            return true;
          }
        } else {
          return false;
        }
      }
    }
  }
  return false;
}

function alert($message)
{
  echo '<script type="text/javascript">alert("' . $message . '")</script>';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  //Check if user already exists
  $stmt = $conn->prepare("SELECT * FROM userAccount  WHERE email = ? ");
  $stmt->bind_param('s', $email);

  //Username and password sent from form
  $email = $_POST['email'];
  $dateOfBirth =  $_POST['dateOfBirth'];
  $dateOfBirthSplitted =  explode('-', $dateOfBirth);
  $password = $_POST['password'];
  $year = $dateOfBirthSplitted[0];
  $month  = $dateOfBirthSplitted[1];
  $day  =  $dateOfBirthSplitted[2];
  $isLegal = false;

  if (checkAge($year, $month, $day)) {
    $isLegal = true;
  }

  //Rules for valid input
  $checkEpost = preg_match('/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/', $email);
  $checkPassord = preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}$/', $password);

  //Validate input
  if ($isLegal) {
    if ($checkEpost && $checkPassord) {
      $stmt->execute();
      $result = $stmt->get_result();

      //If user does no exists, set $alreadyExists to false
      if ($result->num_rows === 0) {
        $alreadyExists = False;
      }

      if (isset($alreadyExists) && $alreadyExists == False) {
        //Prepare and bind
        $stmt = $conn->prepare("INSERT INTO userAccount (email, dateOfBirth, password)
        VALUES (?,?,?)");
        $stmt->bind_param('sss', $email, $dateOfBirth, $password);

        $password = password_hash($password, PASSWORD_DEFAULT);
        $stmt->execute();
        $nrows = $stmt->affected_rows;

        if ($nrows) {
          alert("You have registered successfully! Log in and get started with Cocktail Recipes.");
        } else {
          alert("Registration failed, Please try again.");
        }
        $stmt->close();
        $conn->close();
      } else {
        alert("Registration failed, user already exists.");
      }
    }
  } else {
    alert("You are not old enough to use Cocktail Recipes.");
  }
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
      <ul id="meny">
        <?php
        if ($loggedIn == False) {
          echo
            '<li class="right">
                  <button type="button"class="button" onclick=location.href="login.php">Login</button>
                  <button type="button" class="button" onclick=location.href="signup.php">Sign Up</button>
                </li>';
        } else {
          echo '<button type="button" class="button"><a href="./logout.php">Logout</a></button>';
        }
        ?>
      </ul>
    </nav>
    <main class="main">
      <img class="icon" src="./images/Icon.png" alt="icon">
      <section class="main outerBox">
        <h2>Create Account</h2>
        <h3>Cocktail Recipes</h3>
        <section class="innerBox">
          <h4>Age limit 18</h4>
          <!-------Sign up form-------->
          <form action="" method="post">
            <label>Email</label>
            <input class="a-input" type="email" name="email" Placeholder="Email" pattern='^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$' required />
            <label>Date of birth</label>
            <input class="a-input" type="date" name="dateOfBirth" required />
            <abbr class="abbr" title="The password must be a minimum 10 characters including 1 uppercase and 1 number."> Password (info)</abbr>
            <input class="a-input" type="password" name="password" Placeholder="Password" pattern='^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}' required />
            <input class="a-input" type="password" name="passwordRepeat" placeholder="Confirm password" pattern='^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{10,}' required />
            <input class="a-btn" type="submit" value="Sign Up" />
          </form>
        </section>
      </section>
    </main>
  </div>
</body>

</html>