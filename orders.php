<?php
session_start();
require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>

<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">
  <link rel="shortcut icon" href="img/top-logo.png" type="image/x-icon"/>
  <title>KaminskyVV DR</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <meta property="og:title" content="">
  <meta property="og:type" content="">
  <meta property="og:url" content="">
  <meta property="og:image" content="">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="css/normalize.css">
  <link rel="stylesheet" href="css/main.css">

  <meta name="theme-color" content="#fafafa">

  <?php
  if (isset($_SESSION['theme'])&&!empty($_SESSION['theme'])) {
    if ($_SESSION['theme']=="white") {
      echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style_white.css\">";
    }
    elseif ($_SESSION['theme']=="dark") {
      echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
    }
    else{
      echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
    }
  }
  else{
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
  }
  ?>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

</head>

<body>
<?php
  include 'bd_connect.php';
  $db = new PDO('mysql:host='.$host.';dbname='.$database, $login, $password);
  include 'blocks/header.php';

  if(isset($_SESSION['role']) && !empty($_SESSION['role']) && isset($_GET['action']) && !empty($_GET['action'])){
    if ($_GET['action']=="confirm") {
      $query = "UPDATE `orders` SET `status`='3' WHERE `order_id`= :order_id";
      $result = $db->prepare($query);
      $result->bindParam(':order_id',$_GET['order_id']);
      $result->execute();
      echo "
          <script type=\"text/javascript\">
            alert('Confirmed sucessfully');
            window.location='index.php?action=orders_list';
          </script>
      ";
    }
    elseif ($_GET['action']=="cancel"){
      $query = "UPDATE `orders` SET `status`='101' WHERE `order_id`= :order_id";
      $result = $db->prepare($query);
      $result->bindParam(':order_id',$_GET['order_id']);
      $result->execute();

      $query = "SELECT * FROM `orders` WHERE `order_id` = :order_id";
      $result = $db->prepare($query);
      $result->bindParam(':order_id',$_GET['order_id']);
      $result->execute();
      $result = $result->fetch();
      
      $quantity_recovered = $result['quantity'];
      $product_id = $result['product_id'];

      $query = "SELECT `quantity_available` FROM `products` WHERE `product_id` = '".$product_id."'";
      $result = $db->query($query);
      $result = $result->fetch();

      $query = "UPDATE `products` SET `quantity_available`='".$result['quantity_available']+$quantity_recovered."' WHERE `product_id` = '".$product_id."'";
      $result = $db->query($query);
      echo "
          <script type=\"text/javascript\">
            alert('Canceled sucessfully');
            window.location='index.php?action=orders_list';
          </script>
      ";
    }
    elseif($_GET['action']=="view_orders"){
      /*displaying orders*/
      if ($_SESSION['role']<2) {
        echo "
          <script type=\"text/javascript\">
            alert('Only for stuff');
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

      $query = "SELECT * FROM `orders` WHERE `status`= '1'";
      $result = $db->query($query);

      foreach ($result as $key => $result) {
        if ($result['status']=="1") {
          $status="Recived";
        }
        elseif ($result['status']=="2") {
          $status="Sent";
        }
        elseif ($result['status']=="3") {
          $status="Delivered";
        }
        elseif ($result['status']=="101") {
          $status="Canceled";
        }
        else{
          $status="Error";
        }
        if (isset($result['tracking_number']) && !empty($result['tracking_number'])) {
          $tracking_number = $result['tracking_number'];
        }
        else{
          $tracking_number = "Not available yet";
        }
        $products_query ="SELECT `name` FROM `products` WHERE `product_id` ='".$result['product_id']."'";
        $products_result = $db->query($products_query);
        $products_result = $products_result->fetch();
        $return.= "
          <div class=\"order\">
            <table>
              <tr>
                <td class=\"order-quality-name\">Order id:</td>
                <td>".$result['order_id']."</td>
              </tr>
              <tr>
                <td>User id:</td>
                <td>".$result['user_id']."</td>
              </tr>
              <tr>
                <td>Product id:</td>
                <td>".$result['product_id']."</td>
              </tr>
              <tr>
                <td>Product name:</div>
                <td>".$products_result['name']."</td>
              </tr>
              <tr>
                <td>Quantity:</div>
                <td>".$result['quantity']."</td>
              </tr>
              <tr>
                <td>Order time:</td>
                <td>".gmdate("Y-m-d\t H:i:s", $result['order_time'])."</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>".$status."</td>
              </tr>
              <tr>
                <td>Adress:</td>
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
      
      $query = "UPDATE `orders` SET `status`='2',`sent_time` =".time().", `tracking_number`= :tracking_number WHERE `order_id`= :order_id";
      $result = $db->prepare($query);
      $result->bindParam(':tracking_number',$_POST['tracking_number']);
      $result->bindParam(':order_id',$_GET['order_id']);
      $result->execute();
      
      
      
      /*udpate quantity*/
      $query = "SELECT * FROM `orders` WHERE `order_id` = :order_id";
      $result = $db->prepare($query);
      $result->bindParam(':order_id',$_GET['order_id']);
      $result->execute();
      $result = $result->fetch();
      $quantity_difference=$result['quantity'];
      $product_id = $result['product_id'];
      $query="SELECT * FROM `products` WHERE `product_id` = '".$product_id."'";
      $result = $db->prepare($query);
      $result = $result->fetch();
      $new_quantity=$result['quantity_real']-$quantity_difference;
      $query = "UPDATE `products` SET `quantity_real`='".$new_quantity."' WHERE `product_id`= '".$product_id."'";
      $result = $db->query($query);
      $return.="</div></div>";
      echo $return;
      echo "
          <script type=\"text/javascript\">
            alert('Added succesfully');
            window.location='orders.php?action=view_orders';
          </script>
        ";
    }
    elseif($_GET['action']=="search_by_id"){
      if ($_SESSION['role']<2) {
        echo "
          <script type=\"text/javascript\">
            alert('Only for stuff');
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

      $query = "SELECT * FROM `orders` WHERE `order_id`= :order_id";
      $result = $db->prepare($query);
      $result->bindParam(':order_id',$_POST['order_id']);
      $result->execute();

      foreach ($result as $key => $result) {
        if ($result['status']=="1") {
          $status="Recived";
        }
        elseif ($result['status']=="2") {
          $status="Sent";
        }
        elseif ($result['status']=="3") {
          $status="Delivered";
        }
        elseif ($result['status']=="101") {
          $status="Canceled";
        }
        else{
          $status="Error";
        }
        if (isset($result['tracking_number']) && !empty($result['tracking_number'])) {
          $tracking_number = $result['tracking_number'];
        }
        else{
          $tracking_number = "Not available yet";
        }
        $products_query ="SELECT `name` FROM `products` WHERE `product_id` ='".$result['product_id']."'";
        $products_result = $db->query($products_query);
        $products_result = $products_result->fetch();
        $return.= "
          <div class=\"order\">
            <table>
              <tr>
                <td>Order id:</td>
                <td>".$result['order_id']."</td>
              </tr>
              <tr>
                <td>User id:</td>
                <td>".$result['user_id']."</td>
              </tr>
              <tr>
                <td>Product id:</td>
                <td>".$result['product_id']."</td>
              </tr>
              <tr>
                <td>Product name:</div>
                <td>".$products_result['name']."</td>
              </tr>
              <tr>
                <td>Quantity:</div>
                <td>".$result['quantity']."</td>
              </tr>
              <tr>
                <td>Order time:</td>
                <td>".gmdate("Y-m-d\t H:i:s", $result['order_time'])."</td>
              </tr>
              <tr>
                <td>Status</td>
                <td>".$status."</td>
              </tr>
              <tr>
                <td>Adress:</td>
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
          alert('Only for stuff');
          window.location='index.php?action=news';
        </script>
      "; 
    }
  }






  include 'blocks/footer.php';
?>
<script src="js/vendor/modernizr-3.11.2.min.js"></script>
  <script src="js/plugins.js"></script>
  <script src="js/main.js"></script>

  <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
  <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('set', 'anonymizeIp', true); ga('set', 'transport', 'beacon'); ga('send', 'pageview')
  </script>
  <script src="https://www.google-analytics.com/analytics.js" async></script>
</body>

</html>
