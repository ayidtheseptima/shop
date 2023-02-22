<?php
session_start();
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="../img/top-logo.png" type="image/x-icon"/>
  <title>KaminskyVV DR</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">

  <link rel="manifest" href="../site.webmanifest">
  <link rel="apple-touch-icon" href="../icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="../css/normalize.css">
  <link rel="stylesheet" href="../css/main.css">

  <meta name="theme-color" content="#fafafa">

  <?php
  if (isset($_SESSION['theme'])&&!empty($_SESSION['theme'])) {
    if ($_SESSION['theme']=="white") {
      echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/style_white.css\">";
    }
    elseif ($_SESSION['theme']=="dark") {
      echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/style.css\">";
    }
    else{
      echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/style.css\">";
    }
  }
  else{
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/style.css\">";
  }
  ?>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

</head>

<body>
<?php
  include'functions.php';
  include '../bd_connect.php';
  if(isset($_SESSION['role']) && !empty($_SESSION['role'])){
     do_html_header_logged($_SESSION['user_id'],$_SESSION['role']);
  }
  else{
     do_html_header_unlogged();
  }

  if(isset($_SESSION['role']) && !empty($_SESSION['role']) && isset($_GET['action']) && !empty($_GET['action'])){
    if ($_GET['action']=="confirm") {
      $mysqli = new mysqli($host, $login, $password, $database);
      // check connection
      if ($mysqli->connect_errno) {
        die("Connect failed: ".$mysqli->connect_error);
      }
      $query = "UPDATE `orders` SET `status`='3' WHERE `order_id`= '".$_GET['order_id']."'";
      $result = $mysqli->query($query);
      echo "
          <script type=\"text/javascript\">
            alert('Успішно');
            window.location='index.php?action=orders_list';
          </script>
      ";
    }
    elseif ($_GET['action']=="cancel"){
      $mysqli = new mysqli($host, $login, $password, $database);
      // check connection
      if ($mysqli->connect_errno) {
        die("Connect failed: ".$mysqli->connect_error);
      }
      $query = "UPDATE `orders` SET `status`='101' WHERE `order_id`= '".$_GET['order_id']."'";
      $result = $mysqli->query($query);

      $query = "SELECT * FROM `orders` WHERE `order_id` = '".$_GET['order_id']."'";
      $result = $mysqli->query($query);
      $result= $result->fetch_assoc();
      $quantity_recovered = $result['quantity'];
      $product_id=$result['product_id'];

      $query = "SELECT `quantity_available` FROM `products` WHERE `product_id` = '".$product_id."'";
      $result = $mysqli->query($query);
      $result= $result->fetch_assoc();

      $query = "UPDATE `products` SET `quantity_available`='".$result['quantity_available']+$quantity_recovered."' WHERE `product_id` = '".$product_id."'";
      $result = $mysqli->query($query);
      echo "
          <script type=\"text/javascript\">
            alert('Відмінено успішно');
            window.location='index.php?action=orders_list';
          </script>
      ";
    }
    elseif($_GET['action']=="view_orders"){
      /*displaying orders*/
      if ($_SESSION['role']<2) {
        echo "
          <script type=\"text/javascript\">
            alert('Тільки для адміністаціі');
            window.location='index.php?action=news';
          </script>
        "; 
      }
      $return="<div class=\"content\">
          <div class=\"container\">";
      /*search form by id*/
      $return.="
          <div class=\"order-search\">
            <form action=\"orders.php?action=search_by_id\" method=\"post\">
              <input type=\"number\" name=\"order_id\">
              <input type=\"submit\" value=\"Search\" class=\"button1\">
            </form>
          </div>
      ";

      $return.="
          <div class=\"orders-list\">";

      $mysqli = new mysqli($host, $login, $password, $database);
      // check connection
      if ($mysqli->connect_errno) {
        die("Connect failed: ".$mysqli->connect_error);
      }
      $query = "SELECT * FROM `orders` WHERE `status`= '1'";
      $result = $mysqli->query($query);

      foreach ($result as $key => $result) {
        if ($result['status']=="1") {
          $status="Отримано";
        }
        elseif ($result['status']=="2") {
          $status="Відправлено";
        }
        elseif ($result['status']=="3") {
          $status="Доставлено";
        }
        elseif ($result['status']=="101") {
          $status="Відмінено";
        }
        else{
          $status="Помилка";
        }
        if (isset($result['tracking_number']) && !empty($result['tracking_number'])) {
          $tracking_number = $result['tracking_number'];
        }
        else{
          $tracking_number = "Поки не доступно";
        }
        $products_query ="SELECT `name` FROM `products` WHERE `product_id` ='".$result['product_id']."'";
        $products_result = $mysqli->query($products_query);
        $products_result = $products_result->fetch_assoc();
        $return.= "
          <div class=\"order\">
            <table>
              <tr>
                <td>id замовлення:</td>
                <td>".$result['order_id']."</td>
              </tr>
              <tr>
                <td>id користувача:</td>
                <td>".$result['user_id']."</td>
              </tr>
              <tr>
                <td>id продукту:</td>
                <td>".$result['product_id']."</td>
              </tr>
              <tr>
                <td>Назва продукту:</div>
                <td>".$products_result['name']."</td>
              </tr>
              <tr>
                <td>Кількість:</div>
                <td>".$result['quantity']."</td>
              </tr>
              <tr>
                <td>Час замовлення:</td>
                <td>".gmdate("Y-m-d\t H:i:s", $result['order_time'])."</td>
              </tr>
              <tr>
                <td>Статус</td>
                <td>".$status."</td>
              </tr>
              <tr>
                <td>Адреса:</td>
                <td>".$result['adress']."</td>
              </tr>
              <tr>
                <td>Tracking number:</td>
                <td>".$tracking_number."</td>
              </tr>
            </table>";
          $return.="
            <div class=\"order_buttons\">
              <a href=\"orders.php?action=cancel&order_id=".$result['order_id']."\"><input type=\"button\" class=\"button1\" value=\"Cancel order\"></a>
          ";
          if ($result['status']==1) {
            $return.="
              <form action=\"orders.php?action=add_tracking_number&order_id=".$result['order_id']."\" method=\"post\">
                <input type=\"text\" name=\"tracking_number\">
                <input type=\"submit\" value=\"Add tracking number\" class=\"button1\">
              </form>
            ";
          }
          $return.="</div>";
         
        $return.="</div>";
      }
      $return.="</div></div></div>";
      echo $return;
    }
    elseif($_GET['action']=="add_tracking_number"){
      $return="
        <div class=\"content\">
          <div class=\"container\">
      ";
      $mysqli = new mysqli($host, $login, $password, $database);
      // check connection
      if ($mysqli->connect_errno) {
        die("Connect failed: ".$mysqli->connect_error);
      }
      $query = "UPDATE `orders` SET `status`='2',`sent_time` =".time().", `tracking_number`='".addslashes($_POST['tracking_number'])."' WHERE `order_id`= '".$_GET['order_id']."'";
      $result = $mysqli->query($query);
      /*udpate quantity*/
      $query = "SELECT * FROM `orders` WHERE `order_id` = '".$_GET['order_id']."'";
      $result = $mysqli->query($query);
      $result = $result->fetch_assoc();
      $quantity_difference=$result['quantity'];
      $product_id = $result['product_id'];
      $query="SELECT * FROM `products` WHERE `product_id` = '".$product_id."'";
      $result = $mysqli->query($query);
      $result = $result->fetch_assoc();
      $new_quantity=$result['quantity_real']-$quantity_difference;
      $query = "UPDATE `products` SET `quantity_real`='".$new_quantity."' WHERE `product_id`= '".$product_id."'";
      $result = $mysqli->query($query);
      $return.="</div></div>";
      echo $return;
      echo "
          <script type=\"text/javascript\">
            alert('Успішно додано');
            window.location='orders.php?action=view_orders';
          </script>
        ";
    }
    elseif($_GET['action']=="search_by_id"){
      if ($_SESSION['role']<2) {
        echo "
          <script type=\"text/javascript\">
            alert('Тільки для адміністрації');
            window.location='index.php?action=news';
          </script>
        "; 
      }
      $return="<div class=\"content\">
          <div class=\"container\">";
      /*search form by id*/
      $return.="
          <div class=\"order-search\">
            <form action=\"orders.php?action=search_by_id\" method=\"post\">
              <input type=\"number\" name=\"order_id\" value=\"".$_POST['order_id']."\">
              <input type=\"submit\" value=\"Search\" class=\"button1\">
            </form>
          </div>
      ";
      $return.="
          <div class=\"orders-list\">
      ";

      $mysqli = new mysqli($host, $login, $password, $database);
      // check connection
      if ($mysqli->connect_errno) {
        die("Connect failed: ".$mysqli->connect_error);
      }
      $query = "SELECT * FROM `orders` WHERE `order_id`= '".$_POST['order_id']."'";
      $result = $mysqli->query($query);

      foreach ($result as $key => $result) {
        if ($result['status']=="1") {
          $status="Отримано";
        }
        elseif ($result['status']=="2") {
          $status="Відправлено";
        }
        elseif ($result['status']=="3") {
          $status="Доставлено";
        }
        elseif ($result['status']=="101") {
          $status="Відмінено";
        }
        else{
          $status="Помилка";
        }
        if (isset($result['tracking_number']) && !empty($result['tracking_number'])) {
          $tracking_number = $result['tracking_number'];
        }
        else{
          $tracking_number = "Поки що недоступно";
        }
        $products_query ="SELECT `name` FROM `products` WHERE `product_id` ='".$result['product_id']."'";
        $products_result = $mysqli->query($products_query);
        $products_result = $products_result->fetch_assoc();
        $return.= "
          <div class=\"order\">
            <table>
              <tr>
                <td>id: замовлення</td>
                <td>".$result['order_id']."</td>
              </tr>
              <tr>
                <td>id користувача:</td>
                <td>".$result['user_id']."</td>
              </tr>
              <tr>
                <td>id продукту:</td>
                <td>".$result['product_id']."</td>
              </tr>
              <tr>
                <td>Імя продукту:</div>
                <td>".$products_result['name']."</td>
              </tr>
              <tr>
                <td>Кількість:</div>
                <td>".$result['quantity']."</td>
              </tr>
              <tr>
                <td>Час замовлення:</td>
                <td>".gmdate("Y-m-d\t H:i:s", $result['order_time'])."</td>
              </tr>
              <tr>
                <td>Статус</td>
                <td>".$status."</td>
              </tr>
              <tr>
                <td>Адреса:</td>
                <td>".$result['adress']."</td>
              </tr>
              <tr>
                <td>Tracking number:</td>
                <td>".$tracking_number."</td>
              </tr>
            </table>";
          $return.="
            <div class=\"order_buttons\">
              <a href=\"orders.php?action=cancel&order_id=".$result['order_id']."\"><input type=\"button\" class=\"button1\" value=\"Cancel order\"></a>
          ";
          if ($result['status']==1) {
            $return.="
              <form action=\"orders.php?action=add_tracking_number&order_id=".$result['order_id']."\" method=\"post\">
                <input type=\"text\" name=\"tracking_number\">
                <input type=\"submit\" value=\"Add tracking number\" class=\"button1\">
              </form>
            ";
          }
          $return.="</div>";
         
        $return.="</div>";
      }
      $return.="</div></div></div>";
      echo $return;
    }
    else{
      echo "
        <script type=\"text/javascript\">
          alert('Тільки для адміністації');
          window.location='index.php?action=news';
        </script>
      "; 
    }
  }






  do_html_footer();
?>
<script src="../js/vendor/modernizr-3.11.2.min.js"></script>
  <script src="../js/plugins.js"></script>
  <script src="../js/main.js"></script>

  <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
  <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('set', 'anonymizeIp', true); ga('set', 'transport', 'beacon'); ga('send', 'pageview')
  </script>
  <script src="https://www.google-analytics.com/analytics.js" async></script>
</body>

</html>
