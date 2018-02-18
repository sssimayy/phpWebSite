<?php 
  include 'config.php';
  session_start();

  if(isset($_POST["username"]) && isset($_POST["passwrd"]))
  {
    $que = "select * from admin where adminName='" . $_POST["username"] . "' and adminPass='" . $_POST["passwrd"] . "'";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      $_SESSION["adname"] = $_POST["username"];
      $_SESSION["adpass"] = $_POST["passwrd"];
      
      foreach($res as $r)
      {
        $_SESSION["adminid"] = $r["adminId"];
      }
      
      unset($_POST["username"]);
      unset($_POST["passwrd"]);
      header("location: admin.php");
    }
  }

  $conn = null;
?>

<html>
  <head>

    <title>Look & Buy - Admin Log In</title>
    <link rel="stylesheet" type="text/css" href="styles/ss.css">

  </head>
  <body>

    <div class="header"></div>

    <div class="headermenu">
      <div class="sign_up"><a href="signup.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Sign Up <a></div>
      <div class="sign_up1"><a href="index.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Log In <a></div>
    </div>

    <div class="login_box">
      <form action="adminlogin.php" method="post">
        <table class="table_login">
          <tr>
            <td colspan="2" class="title_cell" style="font-family: FontAwesome;">Admin Log In </td>
          </tr>

          <tr>
            <td class="up_cell">Admin Name:</td>
            <td><input type="text" name="username"></td>
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
    
    <div class="footer">Look & Buy Corporation | Created by Simay Leblebicioğlu &copy All Rights Reserved</div>
  </body>
</html>
















