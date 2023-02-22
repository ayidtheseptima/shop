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
    #error_reporting(0);
    include'functions.php';
    include'../bd_connect.php';


    if(isset($_SESSION['role']) && !empty($_SESSION['role'])){
      do_html_header_logged($_SESSION['user_id'],$_SESSION['role']);
    }
    else{
      do_html_header_unlogged();
    }

    if(isset($_GET['action']) && !empty($_GET['action'])) {
      //setting $role for further functions
      if(isset($_SESSION['role']) && !empty($_SESSION['role'])){
        $role=$_SESSION['role'];
      }
      else{
        $role=0;
      }
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
        do_html_body_news($role);
      }
      elseif ($_GET['action']=="products_by_group") {
        do_html_body_product_by_group($_GET['group_id'],$role);
      }
      elseif ($_GET['action']=="products_by_grand_group"){
        do_html_body_product_by_grand_group($_GET['grand_group_id'],$role);
      }
      elseif ($_GET['action']=="search"){
        do_html_body_search($role);
      }
      elseif ($_GET['action']=="view_product") {
        do_html_body_product_info($_GET['product_id'],$role);
      }
      elseif ($_GET['action']=="search") {
        do_html_body_search($role);
      }
      elseif ($_GET['action']=="brand_list"){
        do_html_body_ventor_list($role);
      }
      elseif ($_GET['action']=="products_by_ventor") {
        do_html_body_product_by_ventor($_GET['ventor_id'],$role);
      }
      elseif ($_GET['action']=="orders_list") {
        do_html_body_orders_list($role,$_SESSION['user_id']);
      }
      else{
        header("Location:index.php?action=news&page=1");
      } 
    }
    else{
      if (!isset($_GET['action'])) {
        header("Location:index.php?action=news&page=1");
      }
      
    }


    do_html_footer();


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
