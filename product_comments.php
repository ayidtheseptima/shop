<?php
session_start();
require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>KaminskyVv DR</title>
  <link rel="shortcut icon" href="img/top-logo.png" type="image/x-icon"/>
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

  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
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
  <script type="js/jquery-3.6.0.js"></script>
</head>

<body>


<?php
include 'bd_connect.php';
$db = new PDO('mysql:host='.$host.';dbname='.$database, $login, $password);
if(isset($_SESSION['role']) && !empty($_SESSION['role']) && isset($_GET['action']) && !empty($_GET['action'])){
	if ($_GET['action']=="add_comment") {
		$query="INSERT INTO `products_comments` (`product_id`, `user_id`, `comment_time`, `comment_text`) VALUES (:return_id, '".$_SESSION['user_id']."', '".time()."', :comment_editor);";
		$result = $db->prepare($query);
    $result->bindParam(':return_id',$_GET['return_id']);
    $result->bindParam(':comment_editor',$_POST['comment-editor']);
    $result->execute();
		$location="index.php?action=view_product&product_id=".$_GET['return_id'];
    echo "
      <script type=\"text/javascript\">
        alert('Confirmed sucessfully');
        window.location='".$location."';
      </script>
    ";
	}
  elseif ($_GET['action']=="delete_comment") {
    $mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
        die("Connect failed: ".$mysqli->connect_error);
    }
    $query="SELECT * FROM `products_comments` WHERE `comment_time` = :comment_time AND `user_id` = :user_id";
    $result = $db->prepare($query);
    $result->bindParam(':comment_time',$_GET['comment_time']);
    $result->bindParam(':user_id',$_GET['user_id']);
    $result->execute();
    $result = $result->fetch();
    $user_query = "SELECT * FROM `users` WHERE `user_id` = '".$result['user_id']."'";
    $user_result = $db->query($user_query);
    $user_result = $user_result->fetch();
    if ($_SESSION['user_id']==$user_result['user_id'] OR $user_result['role']<$_SESSION['role']) {
      $query = "DELETE FROM `products_comments` WHERE `comment_time` = '".$result['comment_time']."' AND `user_id` = '".$result['user_id']."'";
      $result2 = $db->query($query);
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
