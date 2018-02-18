<?php
  class Prod
  {
    public $id;
    public $name;
    public $price;
    public $piece;
    public $pic;
    public $typee;
  }

  include 'config.php';
  session_start();
  $p = new Prod();
  
  if(!isset($_SESSION["adname"]))
    header("Location: index.php");
  
  if(isset($_GET["logout"]) && $_GET["logout"] == true)
  {
    unset($_SESSION["adminid"]);
    unset($_SESSION["adname"]);
    unset($_SESSION["adpass"]);
    unset($_SESSION["get_item"]);
    unset($_SESSION["get_page"]);
    unset($_SESSION["prod_id"]);
    
    header("Location: adminlogin.php");
  }
  
  $que = "select * from product where productId = " . $_SESSION["prod_id"];
  $res = $conn->query($que, PDO::FETCH_ASSOC);
  if($res->rowCount() > 0)
  {
    foreach($res as $r)
    {
      $p->id = $r["productId"];
      $p->name = $r["product"];
      $p->price = $r["price"];
      $p->piece = $r["piece"];
      $p->pic = $r["productPic"];
      $p->typee = $r["typee"];
    }
  }
  
  if(isset($_POST["pname"]) && isset($_POST["pprice"]) && isset($_POST["ppiece"]) && isset($_POST["ptype"]))
  {
    if(!empty($_POST["pname"]) && !empty($_POST["pprice"]) && !empty($_POST["ppiece"]) && !empty($_POST["ptype"]) && file_exists($_FILES["picture"]["tmp_name"]))
    {
      $f = "products/" . $_POST["pname"] . "." . pathinfo($_FILES["picture"]["name"])["extension"];
      
      $que = "update product set product = '" . $_POST["pname"] . "', price = " . $_POST["pprice"] . ", piece = " . $_POST["ppiece"] . ", productPic = '" . $f . "', typee = '" . $_POST["ptype"] . "' where productId = " . $_SESSION["prod_id"];
      $conn->exec($que);
      unlink($p->pic);
      move_uploaded_file($_FILES["picture"]["tmp_name"], $f);
      
      unset($_SESSION["prod_id"]);
      header("Location: admin.php");
    }
  }
  
  $conn = null;
?>

<html>
  <head>

    <title>Look & Buy - Update Product - !ADMIN!</title>
    <link rel="stylesheet" type="text/css" href="styles/ss.css">

  </head>
  <body>

    <div class="header"></div>

    <div class="headermenu">
      <div class="sign_up"><a href="admin.php?logout=true" style="text-decoration: none; color: black; font-family: FontAwesome;">Logout </a></div>
      <div class="sign_up1"><a href="admin.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Go Products </a></div>
      <div class="sign_up2" style="font-family: FontAwesome;"><?php echo $_SESSION["adname"]; ?> </div>
    </div>
    
    <div class="body1">
      <form action="update.php" method="post" enctype="multipart/form-data" name="myForm" onsubmit="return validateForm()">
        <table style="width: 100%; height: 100%;">
          <tr>
            <td colspan="2" style="text-align: center; font-weight: bold; border-right: 2px solid #3399ff;">Old Values</td>
            
            <td colspan="2" style="text-align: center; font-weight: bold;">New Values</td>
          </tr>
          <tr>
            <td style="text-align: right; font-weight: bold;">Name:</td>
            <td style="text-align: left; border-right: 2px solid #3399ff;"><?php echo $p->name; ?></td>
            
            <td style="text-align: right; font-weight: bold;">Name:</td>
            <td style="text-align: left;">
              <input type="text" name="pname">
            </td>
          </tr>
          <tr>
            <td style="text-align: right; font-weight: bold;">Price:</td>
            <td style="text-align: left; border-right: 2px solid #3399ff;"><?php echo $p->price; ?></td>
            
            <td style="text-align: right; font-weight: bold;">Price:</td>
            <td style="text-align: left;">
              <input type="text" name="pprice">
            </td>
          </tr>
          <tr>
            <td style="text-align: right; font-weight: bold;">Piece:</td>
            <td style="text-align: left; border-right: 2px solid #3399ff;"><?php echo $p->piece; ?></td>
            
            <td style="text-align: right; font-weight: bold;">Piece:</td>
            <td style="text-align: left;">
              <input type="text" name="ppiece">
            </td>
          </tr>
          <tr>
            <td style="text-align: right; font-weight: bold;">Type:</td>
            <td style="text-align: left; border-right: 2px solid #3399ff;"><?php echo $p->typee; ?></td>
            
            <td style="text-align: right; font-weight: bold;">Type:</td>
            <td style="text-align: left;">
              <input type="text" name="ptype">
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center; border-right: 2px solid #3399ff;">
              <img src="<?php echo $p->pic; ?>" alt="" width="128px" height="128px">
            </td>
            
            <td style="text-align: right; font-weight: bold;">Picture:</td>
            <td style="text-align: left;">
              <label for="picfile" class="filelabel">Upload</label>
              <input type="file" name="picture" id="picfile" accept=".jpg, .png, .gif, .JPEG" style="display: none;">
            </td>
          </tr>
          <tr>
            <td colspan="4" style="text-align: center;">
              <input type="submit" value="Update">
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
      var x = document.forms["myForm"]["pname"].value;
      var y = document.forms["myForm"]["pprice"].value;
      var z = document.forms["myForm"]["ppiece"].value;
      var t = document.forms["myForm"]["ptype"].value;
      if (x == "" || y == "" || z == "" || t == "")
      {
        alert("Please fill all the fields!");
        return false;
      }
    }
  </script>
</html>










