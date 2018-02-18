<?php 
  include 'config.php';
  session_start();

  if(isset($_POST["username"]) && isset($_POST["passwrd"]))
  {
    $que = "select * from users where userName='" . $_POST["username"] . "' and passwrd='" . $_POST["passwrd"] . "'";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      $_SESSION["usname"] = $_POST["username"];
      $_SESSION["pass"] = $_POST["passwrd"];
      
      foreach($res as $r)
      {
        $_SESSION["prof_pic"] = $r["picture"];
        $_SESSION["userid"] = $r["userId"];
        $_SESSION["totalcoin"] = $r["coin"];
        $_SESSION["email"] = $r["email"];
      }
      
      unset($_POST["username"]);
      unset($_POST["passwrd"]);
      header("location: mainpage.php");
    }
  }

  $conn = null;
?>

<html>
  <head>

    <title>Look & Buy - Log In</title>
    <link rel="stylesheet" type="text/css" href="styles/ss.css">

  </head>
  <body>

    <div class="header"></div>
    
    <div class="headermenu">
      <div class="sign_up"><a href="signup.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Sign Up <a></div>
    </div>
    
    <div class="login_box">
      <form name="myForm" action="index.php" method="post" onsubmit="return validateForm()">
        <table class="table_login">
          <tr>
            <td colspan="2" class="title_cell" style="font-family: FontAwesome;">Log In </td>
          </tr>

          <tr>
            <td class="up_cell">User Name:</td>
            <td><input id="asd" type="text" name="username"></td>
          </tr>

          <tr>
            <td class="up_cell">Password:</td>
            <td><input type="password" name="passwrd"></td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center;">
              <input type="submit" value="Log In">
            </td>
          </tr>
        </table>
      </form>
    </div>
    
    <div class="adminlog">
      <a style="text-decoration: none; line-height: 40px; text-align: center; color: black; font-family: FontAwesome;" href="adminlogin.php" >Admin Control </a>
    </div>

    <div class="footer">Look & Buy Corporation | Created by Simay Leblebicioğlu &copy All Rights Reserved</div>
  </body>
  
  <script>
    function validateForm() 
    {
      var x = document.forms["myForm"]["username"].value;
      var y = document.forms["myForm"]["passwrd"].value;
      if (x == "" || y == "")
      {
        alert("Please fill all the fields!");
        return false;
      }
    }
  </script>
</html>
















