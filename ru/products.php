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

if(isset($_SESSION['role']) && !empty($_SESSION['role']) && isset($_GET['action']) && !empty($_GET['action']) && $_SESSION['role']>1){
	if ($_GET['action']=="add_quantity") {
		echo"
			<div class=\"content\">
				<div class=\"container\">
					<div class=\"add-quantity-form\">
						<form action=\"products.php?action=adding_quantity&product_id=".$_GET['product_id']."\" method=\"post\">
							<input type=\"number\" name=\"quantity\" class=\"input1\" value=\"1\">
							<input type=\"submit\" value=\"Add\" class=\"button1\">
						</form>
					</div>
				</div>
			</div>
		";
	}
	elseif ($_GET['action']=="adding_quantity") {
		$mysqli = new mysqli($host, $login, $password, $database);
		// check connection
		if ($mysqli->connect_errno) {
			die("Connect failed: ".$mysqli->connect_error);
		}
		$query="SELECT * FROM `products` WHERE `product_id` = '".$_GET['product_id']."'";
		$result = $mysqli->query($query);
    $result = $result->fetch_assoc();
    $new_quantity_available=$result['quantity_available']+$_POST['quantity'];
    $new_quantity_real=$result['quantity_real']+$_POST['quantity'];
    $query = "UPDATE `products` SET `quantity_available`='".$new_quantity_available."', `quantity_real`='".$new_quantity_real."', `last_delivery`='".time()."' WHERE `product_id`= '".$_GET['product_id']."'";
    $result = $mysqli->query($query);
		$location="index.php?action=view_product&product_id=".$_GET['product_id'];
		header('Location: '.$location);
	}
	elseif ($_GET['action']=="add_new_product"){
		$return="
			<div class=\"content\">
				<div class=\"container\">
					<div class=\"add-product\">
						<form action=\"products.php?action=adding_new_product\" method=\"post\" name=\"add_product\" id=\"add_product\" enctype=\"multipart/form-data\">
							<div class=\"add-product__title\">
								Name:<input type=\"text\" name=\"name\" class=\"input1\" required=\"\">
							</div>
							<div class=\"add-product__title\">
								Product group:<select name=\"product_group\" id=\"product_group\">";
		$mysqli = new mysqli($host, $login, $password, $database);
		// check connection
		if ($mysqli->connect_errno) {
			die("Connect failed: ".$mysqli->connect_error);
		}
		$query="SELECT * FROM `products_group`";
		$result = $mysqli->query($query);
		foreach ($result as $key => $result) {
			$return.="
				<option value=\"".$result['group_id']."\">".$result['product_group_name']."</option>
			";
		}
		$return.="
								</select>
							</div>
							<div class=\"add-product__title\">
								Price usd:<input type=\"number\" name=\"price_usd\" class=\"input1\" value=\"9.99\" step=\"0.01\" required=\"\">
							</div>		
							<div class=\"add-product__title\">
								Quantity:<input type=\"number\" name=\"quantity\" class=\"input1\" value=\"1\" required=\"\">
							</div>
							<div class=\"add-product__title\">
								Ventor:<select name=\"ventor\" id=\"ventor\">";
		$query="SELECT * FROM `ventors`";
		$result = $mysqli->query($query);
		foreach ($result as $key => $result) {
			$return.="
				<option value=\"".$result['ventor_id']."\">".$result['ventor_name']."</option>
			";
		}
							
		$return.="
								</select>
							</div>
							<div class=\"add-product__description-text\">
                Description en:<br>
                <textarea  form=\"add_product\"  id=\"product_description\" class=\"add-product-description-editor\" name=\"product_description_text_en\" rows=\"12\" cols=\"55\" required=\"\"></textarea>
              </div>
              <div class=\"add-product__description-text\">
                Description ru:<br>
                <textarea  form=\"add_product\"  id=\"product_description_ru\" class=\"add-product-description-editor\" name=\"product_description_text_ru\" rows=\"12\" cols=\"55\" required=\"\"></textarea>
              </div>  
              <div class=\"add-product__description-text\">
                Description ua:<br>
                <textarea  form=\"add_product\"  id=\"product_description_ua\" class=\"add-product-description-editor\" name=\"product_description_text_ua\" rows=\"12\" cols=\"55\" required=\"\"></textarea>
              </div>
              <div class=\"add-product__title\">
								<input type=\"file\" name=\"product_image\" id=\"product_image\" class=\"button1\" required=\"\">
							</div>
							<div class=\"add-product__title add-product__submit-button\">
								<input type=\"submit\" value=\"Add\" class=\"button1\">
							</div>
						</form>
					</div>
				</div>
			</div>
		";
		echo $return;
	}
	elseif ($_GET['action']=="adding_new_product"){
		echo"<div class=content>";
		$mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
      die("Connect failed: ".$mysqli->connect_error);
    }
    $name = addslashes($_POST['name']);
    $description_en = addslashes($_POST['product_description_text_en']);
    $description_ru = addslashes($_POST['product_description_text_ru']);
    $description_ua = addslashes($_POST['product_description_text_ua']);
    $adding_time=time();

    $query = "INSERT INTO `products`( `product_id`, `group_id`, `name`, `price_usd`, `image`, `description`, `quantity_real`, `quantity_available`, `ventor_id`, `ru_description`, `ua_description`, `last_delivery`) VALUES ('','".$_POST['product_group']."','".$name."','".$_POST['price_usd']."','21252.png','".$description_en."','".$_POST['quantity']."','".$_POST['quantity']."','".$_POST['ventor']."','".$description_ru."','".$description_ua."','".$adding_time."')";
    $result = $mysqli->query($query);
  
    $query ="SELECT `product_id` FROM `products` WHERE `name` = '".$name."' AND `last_delivery` = '".$adding_time."'";
    $result = $mysqli->query($query);
    $result = $result->fetch_assoc();
   
    //image upload
    $imgFile = $_FILES['product_image']['name'];
    $tmp_dir = $_FILES['product_image']['tmp_name'];
    $imgSize = $_FILES['product_image']['size'];
    $product_id = $result['product_id'];

    if(!empty($imgFile)){
      $upload_dir = '../img/product/'; // upload directory
      $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
      // valid image extensions
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp'); // valid extensions
      // rename uploading image
      $coverpic = $result['product_id'].".".$imgExt;
      // allow valid image file formats
      if(in_array($imgExt, $valid_extensions)){
        // Check file size '6MB'
        if($imgSize < 6000000){
          move_uploaded_file($tmp_dir,$upload_dir.$coverpic);
        }
      }
    }
    $query = "UPDATE `products` SET `image`= '".$coverpic."' WHERE product_id = ".$product_id;
    $result = $mysqli->query($query);
    echo"</div>";
    echo "
      <script type=\"text/javascript\">
        alert('Added successfully');
        window.location='products.php?action=add_new_product';
      </script>
    ";
	}
	elseif ($_GET['action']=="delete_product"){
		$mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
      die("Connect failed: ".$mysqli->connect_error);
    }
    $query ="SELECT * FROM `products` WHERE `product_id` = '".$_GET['product_id']."'";
    $result = $mysqli->query($query);
    $result = $result->fetch_assoc();
    echo "
      <div class=\"content\">
      	<div class=\"container\">
      		<div class=\"center\">
      			Are you sure You want to delete:\"".$result['name']."\"?
			      <a href=\"products.php?action=deleting_product&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"Yes!\"></a>
			      <a href=\"index.php?action=view_product&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"No!\"></a>
      		</div>
      	</div>
      </div>
    ";
	}
	elseif ($_GET['action']=="deleting_product"){
		$mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
      die("Connect failed: ".$mysqli->connect_error);
    }
    $query ="SELECT * FROM `products` WHERE `product_id` = '".$_GET['product_id']."'";
    $result = $mysqli->query($query);
    $result = $result->fetch_assoc();
    //deleting old image
    $old_image_path="img/product/".$result['image'];
    unlink($old_image_path);
    //deleting related comments
    $query="DELETE FROM `products_comments` WHERE `product_id` = ".$_GET['product_id'];
    $result = $mysqli->query($query);
    //deleting product itself
    $query="DELETE FROM `products` WHERE `product_id` = ".$_GET['product_id'];
    $result = $mysqli->query($query);
    //alert+redirect
    
    echo "
      <script type=\"text/javascript\">
        alert('Deleted successfully');
        window.location='index.php?action=news';
      </script>
    ";
	}
	elseif ($_GET['action']=="edit_product"){
		$mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
      die("Connect failed: ".$mysqli->connect_error);
    }
    $query ="SELECT * FROM `products` WHERE `product_id` = '".$_GET['product_id']."'";
    $result = $mysqli->query($query);
    $result = $result->fetch_assoc();
    $return="
			<div class=\"content\">
				<div class=\"container\">
					<div class=\"add-product\">
						<form action=\"products.php?action=editing_product&product_id=".$result['product_id']."\" method=\"post\" name=\"add_product\" id=\"add_product\" enctype=\"multipart/form-data\">
							<div class=\"add-product__title\">
								Name:<input type=\"text\" name=\"name\" class=\"input1\" required=\"\" value=\"".$result['name']."\">
							</div>
							<div class=\"add-product__title\">
								Product group:<select name=\"product_group\" id=\"product_group\">";
		$group_query = "SELECT * FROM `products_group`";
		$group_result = $mysqli->query($group_query);
		foreach ($group_result as $key => $group_result) {
			$return.="
				<option value=\"".$group_result['group_id']."\"";
			if ($group_result['group_id']==$result['group_id']) {
				$return.=" selected";
			}
			$return.=">".$group_result['product_group_name']."</option>
			";

		}
		$return.="
								</select>
							</div>
							<div class=\"add-product__title\">
								Price usd:<input type=\"number\" name=\"price_usd\" class=\"input1\" value=\"9.99\" step=\"0.01\" required=\"\">
							</div>		
							<div class=\"add-product__title\">
								Ventor:<select name=\"ventor\" id=\"ventor\">";
		$ventor_query="SELECT * FROM `ventors`";
		$ventor_result = $mysqli->query($ventor_query);
		foreach ($ventor_result as $key => $ventor_result) {
			$return.="
				<option value=\"".$ventor_result['ventor_id']."\"";
			if ($ventor_result['ventor_id']==$result['ventor_id']) {
				$return.="selected";
			}
			$return .= ">".$ventor_result['ventor_name']."</option>
			";
		}
		$return.="
								</select>
							</div>
							<div class=\"add-product__description-text\">
                Description en:<br>
                <textarea  form=\"add_product\"  id=\"product_description\" class=\"add-product-description-editor\" name=\"product_description_text_en\" rows=\"12\" cols=\"55\" required=\"\">".$result['description']."</textarea>
              </div>
              <div class=\"add-product__description-text\">
                Description ru:<br>
                <textarea  form=\"add_product\"  id=\"product_description_ru\" class=\"add-product-description-editor\" name=\"product_description_text_ru\" rows=\"12\" cols=\"55\" required=\"\">".$result['ru_description']."</textarea>
              </div>  
              <div class=\"add-product__description-text\">
                Description ua:<br>
                <textarea  form=\"add_product\"  id=\"product_description_ua\" class=\"add-product-description-editor\" name=\"product_description_text_ua\" rows=\"12\" cols=\"55\" required=\"\">".$result['ua_description']."</textarea>
              </div>
              <div class=\"add-product__title\">
								<input type=\"file\" name=\"product_image\" id=\"product_image\" class=\"button1\" required=\"\">
							</div>
							<div class=\"add-product__title add-product__submit-button\">
								<input type=\"submit\" value=\"Save\" class=\"button1\">
							</div>
						</form>
					</div>
				</div>
			</div>
		";
		echo $return;
	}
	elseif ($_GET['action']=="editing_product"){
		echo "<div class=\"content\">
						<div class=\"container\">";
		$mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
       die("Connect failed: ".$mysqli->connect_error);
    }
    $query ="SELECT * FROM `products` WHERE `product_id` = '".$_GET['product_id']."'";
    $result = $mysqli->query($query);
    $result = $result->fetch_assoc();
    //changing image
    //deleting old image
    $old_image_path="../img/product/".$result['image'];
    unlink($old_image_path);
    //image upload
    $imgFile = $_FILES['product_image']['name'];
    $tmp_dir = $_FILES['product_image']['tmp_name'];
    $imgSize = $_FILES['product_image']['size'];

    if(!empty($imgFile)){
      $upload_dir = 'img/product/'; // upload directory
      $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
      // valid image extensions
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp'); // valid extensions
      // rename uploading image
      $coverpic = $result['product_id'].".".$imgExt;
      // allow valid image file formats
      if(in_array($imgExt, $valid_extensions)){
        // Check file size '6MB'
        if($imgSize < 6000000){
          move_uploaded_file($tmp_dir,$upload_dir.$coverpic);
        }
      }
    }
    $name = addslashes($_POST['name']);
    $description_en = addslashes($_POST['product_description_text_en']);
    $description_ru = addslashes($_POST['product_description_text_ru']);
    $description_ua = addslashes($_POST['product_description_text_ua']);

    $query = "UPDATE `products` SET `group_id`='".$_POST['product_group']."',`name`='".$name."',`price_usd`='".$_POST['price_usd']."', `image`= '".$coverpic."',`description`='".$description_en."',`ventor_id`='".$_POST['ventor']."',`ru_description`='".$description_ru."',`ua_description`='".$description_ua."' WHERE product_id = ".$_GET['product_id'];
    $result = $mysqli->query($query);
    echo"</div></div>";
    //alert+redirect
    echo "
      <script type=\"text/javascript\">
        alert('Updated successfully');
        window.location='index.php?action=view_product&product_id=".$_GET['product_id']."';
      </script>
    ";
	}
	else{
		header('Location: '.'index.php?action=news');
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
