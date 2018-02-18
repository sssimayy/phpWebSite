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
    unset($_SESSION["get_bpage"]);
    
    header("Location: index.php");
  }
  
  if(isset($_SESSION["get_item"]))
    unset($_SESSION["get_item"]);
  
  if(isset($_SESSION["get_page"]))
    unset($_SESSION["get_page"]);
  
  if(isset($_SESSION["get_ppage"]))
    unset($_SESSION["get_ppage"]);
  
  $que = "select count(*) as total_item from basket where situation = 0";
  $res = $conn->query($que, PDO::FETCH_ASSOC);
  if($res->rowCount() > 0)
  {
    foreach($res as $r)
    {
      $total_item = $r["total_item"];
    }
  }
    
  $pages = ceil($total_item / MAX_ITEM);
  
  if(!isset($_GET["bpage"]))
  {
    $que = "select *, product.piece as ppiece, basket.piece as bpiece from product, basket where basket.productId = product.productId and basket.situation = 0 and basket.userId = " . $_SESSION["userid"] . " limit " . MAX_ITEM;
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
  else if(isset($_GET["bpage"]))
  {
    $_SESSION["get_bpage"] = $_GET["bpage"];
    
    $que = "select *, product.piece as ppiece, basket.piece as bpiece from product, basket where basket.productId = product.productId and basket.situation = 0 and basket.userId = " . $_SESSION["userid"] . " limit " . MAX_ITEM . " offset " . (($_SESSION["get_bpage"] - 1) * MAX_ITEM);
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
  
  if(isset($_POST["prodid"]))
  {
    $que = "update product set piece = " . ($bass[$_POST["prodid"]]->bpiece + $bass[$_POST["prodid"]]->ppiece) . " where productId = " . $bass[$_POST["prodid"]]->pid;
    $conn->exec($que);
    
    $que = "delete from basket where basketId = " . $bass[$_POST["prodid"]]->bid;
    $conn->exec($que);
    
    unset($_POST["prodid"]);
    header("Location: basket.php");
  }
  
  if(isset($_POST["buyall"]) && sizeof($bass) > 0)
  {
    foreach($bass as $b)
    {
      if($_SESSION["totalcoin"] >= ($b->pprice * $b->bpiece))
      {
        $_SESSION["totalcoin"] -= ($b->pprice * $b->bpiece);
        
        $que = "update users set coin = " . $_SESSION["totalcoin"] . " where userName = '" . $_SESSION["usname"] . "'";
        $conn->exec($que);
        
        $que = "update basket set situation = 1 where basketId = " . $b->bid;
        $res = $conn->query($que, PDO::FETCH_ASSOC);
        if($res->rowCount() <= 0)
          break;
      }
    }
    
    unset($_POST["buyall"]);
    header("Location: basket.php");
  }
  
  $i--;
  
  $conn = null;
?>

<html>
  <head>

    <title>Look & Buy - <?php echo $_SESSION["usname"]; ?>'s Basket</title>
    <link rel="stylesheet" type="text/css" href="styles/ss.css">

  </head>
  <body>

    <div class="header"></div>

    <div class="headermenu">
      <div class="sign_up"><a href="mainpage.php?logout=true" style="text-decoration: none; color: black; font-family: FontAwesome;">Logout </a></div>
      <div class="sign_up1"><a href="mainpage.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Main Page </a></div>
      <div class="sign_up2"><a href="profile.php" style="text-decoration: none; color: black; font-family: FontAwesome;"><?php echo $_SESSION["usname"]; ?> </a></div>
      <div class="sign_up3"><img src="<?php echo $_SESSION["prof_pic"]; ?>" alt="" width="64px" height="64px"></div>
    </div>
    
    <div class="menu1">
      <form action="basket.php" method="post">
        <input type="text" name="buyall" value="Buy All" style="visibility: hidden;"><br>
        <input type="submit" value="Buy All">
      </form>
    </div>
    
    <div class="body">
      <div style="display: inline-block; border: 2px solid #3399ff; margin-left: 2px; margin-bottom: 3px;">&nbsp&nbsp
        <?php
          for($k = 1; $k <= $pages; $k++)
          {
            echo
            "<a style=\"text-decoration: none; color: #3399ff;\" href=\"basket.php?bpage=$k\">" . $k . "</a>&nbsp&nbsp&nbsp";
          }
        ?>
      </div>
      <table>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
        </tr>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"basket.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $bass[$i]->ppic . "\" alt=\"\" width=\"64px\" height=\"64px\"><br><br>" .
              $bass[$i]->pname . "<br><br> Count: " . $bass[$i]->bpiece . "<br><br> Price: " . $bass[$i]->pprice . " Coin"
              . "<br><br>
              <input type=\"submit\" value=\"Remove from Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $i . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
        </tr>
      </table>
    </div>

    <div class="footer">Look & Buy Corporation | Created by Simay Leblebicioğlu &copy All Rights Reserved</div>
  </body>
</html>







