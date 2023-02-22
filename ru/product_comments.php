<?php
session_start();
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>KaminskyVv DR</title>
  <link rel="shortcut icon" href="../img/top-logo.png" type="image/x-icon"/>
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

  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
  <script type="js/jquery-3.6.0.js"></script>
</head>

<body>


<?php
include '../bd_connect.php';
if(isset($_SESSION['role']) && !empty($_SESSION['role']) && isset($_GET['action']) && !empty($_GET['action'])){
	if ($_GET['action']=="add_comment") {
		$mysqli = new mysqli($host, $login, $password, $database);
		// check connection
		if ($mysqli->connect_errno) {
		    die("Connect failed: ".$mysqli->connect_error);
		}
    


		$query="INSERT INTO `products_comments` (`product_id`, `user_id`, `comment_time`, `comment_text`) VALUES ('".$_GET['return_id']."', '".$_SESSION['user_id']."', '".time()."', '".$_POST['comment-editor']."');";
		$result = $mysqli->query($query);
		$location="index.php?action=view_product&product_id=".$_GET['return_id'];
		header('Location: '.$location);
	}
  elseif ($_GET['action']=="delete_comment") {
    $mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
        die("Connect failed: ".$mysqli->connect_error);
    }
    $query="SELECT * FROM `products_comments` WHERE `comment_time` = '".$_GET['comment_time']."' AND `user_id` = '".$_GET['user_id']."'";
    $result = $mysqli->query($query);
    $result = $result->fetch_assoc();
    $user_query = "SELECT * FROM `users` WHERE `user_id` = '".$result['user_id']."'";
    $user_result = $mysqli->query($user_query);
    $user_result = $user_result->fetch_assoc();
    if ($_SESSION['user_id']==$user_result['user_id'] OR $user_result['role']<$_SESSION['role']) {
      $query = "DELETE FROM `products_comments` WHERE `comment_time` = '".$result['comment_time']."' AND `user_id` = '".$result['user_id']."'";
      $result2 = $mysqli->query($query);
      //alert+redirect
      echo "
        <script type=\"text/javascript\">
          alert('Deleted successfully');
          window.location='index.php?action=view_product&product_id=".$result['product_id']."';
        </script>
      ";
    }
  }
	else{
		alert("Some error!");
		header('Location: index.php?action=news');
	}
}










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
