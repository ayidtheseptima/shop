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
	if ($_GET['action']=="adress_info") {
		echo"
			<div class=\"content\">
				<div class=\"container\">
					<div class=\"address-info-form\">	
						<form action=\"buy.php?action=buy&product_id=".$_GET['product_id']."&quantity=".$_POST['quantity']."\" method=\"post\">
							<input type=\"text\" name=\"adress\" class=\"input1\" placeholder=\"Delivery adress\">
							<input type=\"submit\" value=\"Buy\" class=\"button1\">
						</form>
					</div>
				</div>
			</div>
		";
	}
	elseif($_GET['action']=="buy"){
		
		
		$query="INSERT INTO `orders` (`order_id`, `product_id`, `quantity`, `order_time`, `sent_time`, `adress`, `status`, `user_id`, `tracking_number` ) VALUES ('', :product_id, :quantity, '".time()."','',:adress,'1','".$_SESSION['user_id']."','');";
		$result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->bindParam(':quantity',$_GET['quantity']);
    $result->bindParam(':adress',$_POST['adress']);
    $result->execute();
		/*udpate quantity*/
		$query="SELECT * FROM `products` WHERE `product_id` = :product_id";
		$result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    $result = $result->fetch();

    $new_quantity=$result['quantity_available']-$_GET['quantity'];
    $query = "UPDATE `products` SET `quantity_available`='".$new_quantity."' WHERE `product_id`= :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
		$location="index.php?action=view_product&product_id=".$_GET['product_id'];
		echo "
      <script type=\"text/javascript\">
        window.location='".$location."';
      </script>
    ";
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
  echo "
      <script type=\"text/javascript\">
        window.location='index.php?action=news';
      </script>
    ";
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
