<?php 
  include 'config.php';
  session_start();

  if(isset($_POST["username"]) && isset($_POST["passwrd1"]) && isset($_POST["passwrd2"]) && isset($_POST["email"]))
  {
    if(!empty($_POST["username"]) && !empty($_POST["passwrd1"]) && !empty($_POST["passwrd2"]) && !empty($_POST["email"]))
    {
      if(filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
      {
        if($_POST["passwrd1"] == $_POST["passwrd2"] && !file_exists($_FILES["picture"]["tmp_name"]))
        {
          $que = "select * from users where userName = '" . $_POST["username"] . "'";
          $res = $conn->query($que, PDO::FETCH_ASSOC);
          if($res->rowCount() <= 0)
          {
            $que = "insert into users(userName, passwrd, email) values('" . $_POST["username"] . "', '" .$_POST["passwrd1"] . "', '" . $_POST["email"] . "')";
            $conn->exec($que);
            
            $que = "select * from users where userName='" . $_POST["username"] . "' and passwrd='" . $_POST["passwrd1"] . "'";
            $res = $conn->query($que, PDO::FETCH_ASSOC);
            if($res->rowCount() > 0)
            {
              $_SESSION["usname"] = $_POST["username"];
              $_SESSION["pass"] = $_POST["passwrd1"];
              
              foreach($res as $r)
              {
                $_SESSION["prof_pic"] = $r["picture"];
                $_SESSION["userid"] = $r["userId"];
                $_SESSION["totalcoin"] = $r["coin"];
                $_SESSION["email"] = $r["email"];
              }
              
              unset($_POST["username"]);
              unset($_POST["passwrd1"]);
              unset($_POST["passwrd2"]);
              unset($_FILES["picture"]);
              header("location: mainpage.php");
            }
          }
        }
        else if($_POST["passwrd1"] == $_POST["passwrd2"] && file_exists($_FILES["picture"]["tmp_name"]) && $_FILES["picture"]["size"] <= 10485760)
        {
          $que = "select * from users where userName = '" . $_POST["username"] . "'";
          $res = $conn->query($que, PDO::FETCH_ASSOC);
          if($res->rowCount() <= 0)
          {
            $f = "profile_pics/" . $_POST["username"] . "." . pathinfo($_FILES["picture"]["name"])["extension"];
            $que = "insert into users(userName, passwrd, email, picture) values('" . $_POST["username"] . "', '" . $_POST["passwrd1"] . "', '" . $_POST["email"] . "', '" . $f . "')";
            $conn->exec($que);
            move_uploaded_file($_FILES["picture"]["tmp_name"], $f);
            
            $que = "select * from users where userName='" . $_POST["username"] . "' and passwrd='" . $_POST["passwrd1"] . "'";
            $res = $conn->query($que, PDO::FETCH_ASSOC);
            if($res->rowCount() > 0)
            {
              $_SESSION["usname"] = $_POST["username"];
              $_SESSION["pass"] = $_POST["passwrd1"];
              
              foreach($res as $r)
              {
                $_SESSION["prof_pic"] = $r["picture"];
                $_SESSION["userid"] = $r["userId"];
                $_SESSION["totalcoin"] = $r["coin"];
                $_SESSION["email"] = $r["email"];
              }
              
              unset($_POST["username"]);
              unset($_POST["passwrd1"]);
              unset($_POST["passwrd2"]);
              unset($_FILES["picture"]);
              header("location: mainpage.php");
            }
          }
        }
      }
    }
  }

  $conn = null;
?>

<html>
  <head>

    <title>Look & Buy - Sign Up</title>
    <link rel="stylesheet" type="text/css" href="styles/ss.css">

  </head>
  <body>

    <div class="header"></div>
    
    <div class="headermenu">
      <div class="sign_up"><a href="index.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Log In <a></div>
    </div>
    
    <div class="login_box">
      <form action="signup.php" method="post" enctype="multipart/form-data" name="myForm" onsubmit="return validateForm()">
        <table class="table_login">
          <tr>
            <td colspan="2" class="title_cell" style="font-family: FontAwesome;">Sign Up </td>
          </tr>

          <tr>
            <td class="up_cell">User Name:</td>
            <td><input type="text" name="username"></td>
          </tr>

          <tr>
            <td class="up_cell">Password:</td>
            <td><input type="password" name="passwrd1"></td>
          </tr>

          <tr>
            <td class="up_cell">Confirm Password:</td>
            <td><input type="password" name="passwrd2"></td>
          </tr>

          <tr>
            <td class="up_cell">Email:</td>
            <td><input type="text" name="email"></td>
          </tr>

          <tr>
            <td class="up_cell">Profile Picture:</td>
            <td>
              <label for="picfile" class="filelabel">Upload</label>
              <input type="file" name="picture" id="picfile" accept=".jpg, .png, .gif, .JPEG" style="display: none;">
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center;">
              <input type="submit" value="Sign Up">
            </td>
          </tr>
        </table>
      </form>
    </div>

    <div class="footer">Look & Buy Corporation | Created by Simay Leblebicioğlu &copy All Rights Reserved</div>
  </body>
  
  <script>
    function validateForm() 
    {
      var x = document.forms["myForm"]["username"].value;
      var y = document.forms["myForm"]["passwrd1"].value;
      var z = document.forms["myForm"]["passwrd2"].value;
      var t = document.forms["myForm"]["email"].value;
      if (x == "" || y == "" || z == "" || t == "")
      {
        alert("Please fill all the fields!");
        return false;
      }
    }
  </script>
</html>



















