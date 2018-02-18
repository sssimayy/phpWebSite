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
  define("MAX_ITEM", 30);
  $prods = array();
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
    unset($_SESSION["get_item"]);
    unset($_SESSION["get_page"]);
    
    header("Location: index.php");
  }
  
  if(isset($_SESSION["get_bpage"]))
    unset($_SESSION["get_bpage"]);
  
  if(isset($_SESSION["get_ppage"]))
    unset($_SESSION["get_ppage"]);
  
  if(!isset($_GET["item"]) && !isset($_GET["page"]) && isset($_SESSION["get_item"]) && isset($_SESSION["get_page"]))
    header("Location: mainpage.php?item=" . $_SESSION["get_item"] . "&page=" . $_SESSION["get_page"]);
  else if(!isset($_GET["item"]) && !isset($_GET["page"]) && !isset($_SESSION["get_item"]) && isset($_SESSION["get_page"]))
    header("Location: mainpage.php?page=" . $_SESSION["get_page"]);
  else if(!isset($_GET["item"]) && !isset($_GET["page"]) && isset($_SESSION["get_item"]) && !isset($_SESSION["get_page"]))
    header("Location: mainpage.php?item=" . $_SESSION["get_item"]);
  
  if((!isset($_GET["item"]) && !isset($_GET["page"])) || (isset($_GET["item"]) && !isset($_GET["page"]) && $_GET["item"] == "all"))
  {
    if(isset($_GET["item"]))
      $_SESSION["get_item"] = $_GET["item"];
    
    $que = "select count(*) as total_item from product";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $total_item = $r["total_item"];
      }
    }
    
    $pages = ceil($total_item / MAX_ITEM);
    
    $que = "select * from product limit " . MAX_ITEM;
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $p = new Prod();
        $p->id = $r["productId"];
        $p->name = $r["product"];
        $p->price = $r["price"];
        $p->piece = $r["piece"];
        $p->pic = $r["productPic"];
        $p->typee = $r["typee"];
        $prods[$i] = $p;
        
        $i++;
      }
    }
  }
  else if(isset($_GET["item"]) && !isset($_GET["page"]))
  {
    $_SESSION["get_item"] = $_GET["item"];
    
    $que = "select count(*) as total_item from product where typee = '" . $_SESSION["get_item"] . "'";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $total_item = $r["total_item"];
      }
    }
    
    $pages = ceil($total_item / MAX_ITEM);
    
    $que = "select * from product where typee =  '" . $_SESSION["get_item"] . "'";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $p = new prod();
        $p->id = $r["productId"];
        $p->name = $r["product"];
        $p->price = $r["price"];
        $p->piece = $r["piece"];
        $p->pic = $r["productPic"];
        $p->typee = $r["typee"];
        $prods[$i] = $p;
        
        $i++;
      }
    }
  }
  else if(isset($_GET["page"]) && !isset($_GET["item"]))
  {
    $_SESSION["get_page"] = $_GET["page"];
    
    $que = "select count(*) as total_item from product";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $total_item = $r["total_item"];
      }
    }
    
    $pages = ceil($total_item / MAX_ITEM);
    
    $que = "select * from product  limit " . MAX_ITEM . " offset " . (($_SESSION["get_page"] - 1) * MAX_ITEM);
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $p = new prod();
        $p->id = $r["productId"];
        $p->name = $r["product"];
        $p->price = $r["price"];
        $p->piece = $r["piece"];
        $p->pic = $r["productPic"];
        $p->typee = $r["typee"];
        $prods[$i] = $p;
        
        $i++;
      }
    }
  }
  else if(isset($_GET["page"]) && isset($_GET["item"]) && $_GET["item"] == "all")
  {
    $_SESSION["get_page"] = $_GET["page"];
    $_SESSION["get_item"] = $_GET["item"];
    
    $que = "select count(*) as total_item from product";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $total_item = $r["total_item"];
      }
    }
    
    $pages = ceil($total_item / MAX_ITEM);
    
    $que = "select * from product limit " . MAX_ITEM . " offset " . (($_SESSION["get_page"] - 1) * MAX_ITEM);
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $p = new prod();
        $p->id = $r["productId"];
        $p->name = $r["product"];
        $p->price = $r["price"];
        $p->piece = $r["piece"];
        $p->pic = $r["productPic"];
        $p->typee = $r["typee"];
        $prods[$i] = $p;
        
        $i++;
      }
    }
  }
  else if(isset($_GET["page"]) && isset($_GET["item"]))
  {
    $_SESSION["get_page"] = $_GET["page"];
    $_SESSION["get_item"] = $_GET["item"];
    
    $que = "select count(*) as total_item from product where typee = '" . $_SESSION["get_item"] . "'";
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $total_item = $r["total_item"];
      }
    }
    
    $pages = ceil($total_item / MAX_ITEM);
    
    $que = "select * from product where typee = '" . $_SESSION["get_item"] . "' limit " . MAX_ITEM . " offset " . (($_SESSION["get_page"] - 1) * MAX_ITEM);
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $p = new prod();
        $p->id = $r["productId"];
        $p->name = $r["product"];
        $p->price = $r["price"];
        $p->piece = $r["piece"];
        $p->pic = $r["productPic"];
        $p->typee = $r["typee"];
        $prods[$i] = $p;
        
        $i++;
      }
    }
  }
  
  if(isset($_POST["prodid"]))
  {
    $remain = 0;
    $que = "select * from product where productId = " .$_POST["prodid"];
    $res = $conn->query($que, PDO::FETCH_ASSOC);
    if($res->rowCount() > 0)
    {
      foreach($res as $r)
      {
        $remain = $r["piece"];
      }
    }
    
    if($remain > $_POST["buypiece"])
    {
      $que = "insert into basket(userId, productId, piece, situation) values(" . $_SESSION["userid"] . ", " . $_POST["prodid"] . ", " . $_POST["buypiece"] . ", 0)";
      $conn->exec($que);
      
      $remain -= $_POST["buypiece"];
      $que = "update product set piece = " . $remain . " where productId = " . $_POST["prodid"];
      $conn->exec($que);
      
      unset($_POST["prodid"]);
    }
  }
  
  $i--;
  
  $conn = null;
?>

<html>
  <head>

    <title>Look & Buy - Main Page</title>
    <link rel="stylesheet" type="text/css" href="styles/ss.css">

  </head>
  <body>

    <div class="header"></div>

    <div class="headermenu">
      <div class="sign_up"><a href="mainpage.php?logout=true" style="text-decoration: none; color: black; font-family: FontAwesome;">Logout </a></div>
      <div class="sign_up1"><a href="basket.php" style="text-decoration: none; color: black; font-family: FontAwesome;">Basket </a></div>
      <div class="sign_up2"><a href="profile.php" style="text-decoration: none; color: black; font-family: FontAwesome;"><?php echo $_SESSION["usname"]; ?> </a></div>
      <div class="sign_up3"><img src="<?php echo $_SESSION["prof_pic"]; ?>" alt="" width="64px" height="64px"></div>
    </div>

    <div class="menu">
      <div class="all"><a href="mainpage.php?item=all" style="text-decoration: none; color: black; margin-left: 5px;">All</a></div>
      <div class="all"><a href="mainpage.php?item=computer" style="text-decoration: none; color: black; margin-left: 5px;">Computers</a></div>
      <div class="all"><a href="mainpage.php?item=saat" style="text-decoration: none; color: black; margin-left: 5px;">Watches</a></div>
      <div class="all"><a href="mainpage.php?item=phone" style="text-decoration: none; color: black; margin-left: 5px;">Phones</a></div>
      <div class="all"><a href="mainpage.php?item=car" style="text-decoration: none; color: black; margin-left: 5px;">Cars</a></div>
      <div class="all"><a href="mainpage.php?item=ha" style="text-decoration: none; color: black; margin-left: 5px;">Household Appliances</a></div>
    </div>
    
    <div class="body">
      <div style="display: inline-block; border: 2px solid #3399ff; margin-left: 2px; margin-bottom: 3px;">&nbsp&nbsp
        <?php
          for($k = 1; $k <= $pages; $k++)
          {
            if(!isset($_SESSION["get_item"]))
            {
              echo
              "<a style=\"text-decoration: none; color: #3399ff;\" href=\"mainpage.php?page=$k\">" . $k . "</a>&nbsp&nbsp&nbsp";
            }
            else
            {
              $itemm = $_SESSION["get_item"];
              echo
              "<a style=\"text-decoration: none; color: #3399ff;\" href=\"mainpage.php?item=$itemm&page=$k\">" . $k . "</a>&nbsp&nbsp&nbsp";
            }
          }
        ?>
      </div>
      <table>
        <tr>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
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
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
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
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
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
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
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
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
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
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
              </td>
              </form>";
              $i--;
            }
          ?>
          <?php 
            if($i >= 0)
            {
              echo
              "<form action=\"mainpage.php\" method=\"post\">
              <td style=\"border: 2px solid #3399ff;\">
              <img src=\"" . $prods[$i]->pic . "\" alt=\"\" width=\"128px\" height=\"128px\"><br><br>" .
              $prods[$i]->name . "<br><br> Stock: " . $prods[$i]->piece . "<br><br> Price: " . $prods[$i]->price . " Coin"
              . "<br><br>
              Piece: <input type=\"number\" name=\"buypiece\" min=\"1\" value=\"1\"><br><br>
              <input type=\"submit\" value=\"Add to Basket\">
              <input type=\"number\" name=\"prodid\" value=" . $prods[$i]->id . " style=\"visibility: hidden;\">
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










