<?php
  class Bas
  {
    public $pid;
    public $pname;
    public $pprice;
    public $ppiece;
    public $ppic;
    public $ptypee;
    
    public $bid;
    public $bpiece;
    public $bsituation;
  }

  include 'config.php';
  session_start();
  define("MAX_ITEM", 30);
  $bass = array();
  $i = 0;
  $pages = 0;
  $total_item = 0;
  
  if(!isset($_SESSION["usname"]))
    header("Location: index.php");
  
  if(isset($_GET["logout"]) && $_GET["logout"] == true)
  {
    unset($_SESSION["usname"]);
    unset($_SESSION["pass"]);
    unset($_SESSION["prof_pic"]);
    unset($_SESSION["userid"]);
    unset($_SESSION["totalcoin"]);
    unset($_SESSION["email"]);
    unset($_SESSION["get_ppage"]);
    
    header("Location: index.php");
  }
  
  if(isset($_SESSION["get_item"]))
    unset($_SESSION["get_item"]);
  
  if(isset($_SESSION["get_page"]))
    unset($_SESSION["get_page"]);
  
  if(isset($_SESSION["get_bpage"]))
    unset($_SESSION["get_bpage"]);
  
  $que = "select count(*) as total_item from basket where userId = " . $_SESSION["userid"] . " and situation = 1";
  $res = $conn->query($que, PDO::FETCH_ASSOC);
  if($res->rowCount() > 0)
  {
    foreach($res as $r)
    {
      $total_item = $r["total_item"];
    }
  }
    
  $pages = ceil($total_item / MAX_ITEM);
  
  if(!isset($_GET["ppage"]))
  {
    $que = "select *, product.piece as ppiece, basket.piece as bpiece from product, basket where basket.productId = product.productId and basket.situation = 1 and basket.userId = " . $_SESSION["userid"] . " limit " . MAX_ITEM;
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $b = new Bas();
        $b->pid = $r["productId"];
        $b->pname = $r["product"];
        $b->pprice = $r["price"];
        $b->ppiece = $r["ppiece"];
        $b->ppic = $r["productPic"];
        $b->ptypee = $r["typee"];
        $b->bid = $r["basketId"];
        $b->bpiece = $r["bpiece"];
        $b->bsituation = $r["situation"];
        $bass[$i] = $b;
        
        $i++;
      }
    }
  }
  else if(isset($_GET["ppage"]))
  {
    $_SESSION["get_ppage"] = $_GET["ppage"];
    
    $que = "select *, product.piece as ppiece, basket.piece as bpiece from product, basket where basket.productId = product.productId and basket.situation = 1 and basket.userId = " . $_SESSION["userid"] . " limit " . MAX_ITEM . " offset " . (($_SESSION["get_ppage"] - 1) * MAX_ITEM);
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $b = new Bas();
        $b->pid = $r["productId"];
        $b->pname = $r["product"];
        $b->pprice = $r["price"];
        $b->ppiece = $r["ppiece"];
        $b->ppic = $r["productPic"];
        $b->ptypee = $r["typee"];
        $b->bid = $r["basketId"];
        $b->bpiece = $r["bpiece"];
        $b->bsituation = $r["situation"];
        $bass[$i] = $b;
        
        $i++;
      }
    }
  }
  
  $i--;
  
  $conn = null;
?>

<html>
  <head>

    <title>Look & Buy - <?php echo $_SESSION["usname"]; ?>'s Profile</title>
    <link rel="stylesheet" type="text/css" href="styles/ss.css">

  </head>
  <body>

    <div class="header"></div>

    <div class="headermenu">
      <div class="sign_up"><a href="mainpage.php?logout=true" style="text-decoration: none; color: black; font-family: FontAwesome;">Logout </a></div>
      <div class="sign_up1"><a href="mainpage.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Main Page </a></div>
      <div class="sign_up2"><a href="basket.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Basket </a></div>
    </div>
    
    <div class="menu1">
      <img src="<?php echo $_SESSION["prof_pic"]; ?>" alt="" width="128px" height="128px" style="display: block; margin-left: auto; margin-right: auto;">
      <h3 style="text-align: center; font-family: FontAwesome;"> <?php echo $_SESSION["usname"]; ?></h3>
      <h4 style="text-align: center; font-family: FontAwesome;"> <?php echo $_SESSION["totalcoin"]; ?></h4>
    </div>
    
    <div class="body">
      <div style="display: inline-block; border: 2px solid #3399ff; margin-left: 2px; margin-bottom: 3px;">&nbsp&nbsp
        <?php
          for($k = 1; $k <= $pages; $k++)
          {
            echo
            "<a style=\"text-decoration: none; color: #3399ff;\" href=\"profile.php?ppage=$k\">" . $k . "</a>&nbsp&nbsp&nbsp";
          }
        ?>
      </div>
      <table>
        <tr><th><?php echo $_SESSION["usname"]; ?>, you purchased:</th></tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff; width:\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              </td>";
              $i--;
            }
          ?>
        </tr>
      </table>
    </div>

    <div class="footer">Look & Buy Corporation | Created by Simay Leblebicioğlu &copy All Rights Reserved</div>
  </body>
</html>










