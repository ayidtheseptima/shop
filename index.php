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
    #error_reporting(0);
    include'bd_connect.php';
    $db = new PDO('mysql:host='.$host.';dbname='.$database, $login, $password);
    /*header*/
    include 'blocks/header.php';

    if(isset($_GET['action']) && !empty($_GET['action'])) {
      //actions
      if ($_GET['action']=="log_out") {
        unset($_SESSION["user_id"]);
        unset($_SESSION["role"]);
        unset($_SESSION["theme"]);
        echo "
          <script type=\"text/javascript\">
            window.location='auth.php';
          </script>
        ";
      }
      elseif ($_GET['action']=="news") {
        include 'blocks/news.php';
      }
      elseif ($_GET['action']=="products_by_group") {
        include 'blocks/product_by_group.php';
      }
      elseif ($_GET['action']=="products_by_grand_group"){
        include 'blocks/product_by_grand_group.php';
      }
      elseif ($_GET['action']=="view_product") {
        include 'blocks/product_info.php';
      }
      elseif ($_GET['action']=="search") {
        include 'blocks/search.php';
      }
      elseif ($_GET['action']=="brand_list"){
        include 'blocks/ventor_list.php';
      }
      elseif ($_GET['action']=="products_by_ventor") {
        include 'blocks/product_by_ventor.php';
      }
      elseif ($_GET['action']=="orders_list") {
        include 'blocks/orders_list.php';
      }
      else{
        echo "
          <script type=\"text/javascript\">
            window.location='index.php?action=news';
          </script>
        ";
      } 
    }
    else{
      if (!isset($_GET['action'])) {
        echo "
          <script type=\"text/javascript\">
            window.location='index.php?action=news';
          </script>
        ";
      }
      
    }


    include 'blocks/footer.php';
    $result = null;
    $db = null;

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
