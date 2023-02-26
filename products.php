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
		$query="SELECT * FROM `products` WHERE `product_id` = :product_id";
		$result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    $result = $result->fetch();
    $new_quantity_available=$result['quantity_available']+intval($_POST['quantity']);
    $new_quantity_real=$result['quantity_real']+intval($_POST['quantity']);
    $query = "UPDATE `products` SET `quantity_available`='".$new_quantity_available."', `quantity_real`='".$new_quantity_real."', `last_delivery`='".time()."' WHERE `product_id`= :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
		$location="index.php?action=view_product&product_id=".$_GET['product_id'];
		echo "
      <script type=\"text/javascript\">
        alert('Confirmed sucessfully');
        window.location='".$location."';
      </script>
    ";
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
		$query="SELECT * FROM `products_group`";
		$result = $db->query($query);
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
		$result = $db->query($query);
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
		
    $adding_time=time();
    $query = "INSERT INTO `products`( `product_id`, `group_id`, `name`, `price_usd`, `image`, `description`, `quantity_real`, `quantity_available`, `ventor_id`, `ru_description`, `ua_description`, `last_delivery`) VALUES ('',:product_group,:name,:price_usd,'21252.png',:product_description_text_en, :quantity, :quantity, :ventor, :product_description_text_ru, :product_description_text_ua, '".$adding_time."')";
    $result = $db->prepare($query);
    $result->bindParam(':product_group',$_POST['product_group']);
    $result->bindParam(':name',$_POST['name']);
    $result->bindParam(':price_usd',$_POST['price_usd']);
    $result->bindParam(':product_description_text_en',$_POST['product_description_text_en']);
    $result->bindParam(':product_description_text_ru',$_POST['product_description_text_ru']);
    $result->bindParam(':product_description_text_ua',$_POST['product_description_text_ua']);
    $result->bindParam(':quantity',$_POST['quantity']);
    $result->bindParam(':ventor',$_POST['ventor']);
    $result->execute();
  
    $query ="SELECT `product_id` FROM `products` WHERE `name` = :name AND `last_delivery` = '".$adding_time."'";
    $result = $db->prepare($query);
    $result->bindParam(':name',$_POST['name']);
    $result->execute();
    $result = $result->fetch();
   
    //image upload
    $imgFile = $_FILES['product_image']['name'];
    $tmp_dir = $_FILES['product_image']['tmp_name'];
    $imgSize = $_FILES['product_image']['size'];
    $product_id = $result['product_id'];

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
    $query = "UPDATE `products` SET `image`= '".$coverpic."' WHERE product_id = ".$product_id;
    $result = $db->query($query);
    echo"</div>";
    echo "
      <script type=\"text/javascript\">
        alert('Added successfully');
        window.location='products.php?action=add_new_product';
      </script>
    ";
	}
	elseif ($_GET['action']=="delete_product"){
		
    $query ="SELECT * FROM `products` WHERE `product_id` = :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    $result = $result->fetch();
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
    $query ="SELECT * FROM `products` WHERE `product_id` = :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    $result = $result->fetch();
    //deleting old image
    $old_image_path="img/product/".$result['image'];
    unlink($old_image_path);
    //deleting related comments
    $query="DELETE FROM `products_comments` WHERE `product_id` = :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    //deleting product itself
    $query="DELETE FROM `products` WHERE `product_id` = :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    //alert+redirect
    
    echo "
      <script type=\"text/javascript\">
        alert('Deleted successfully');
        window.location='index.php?action=news';
      </script>
    ";
	}
	elseif ($_GET['action']=="edit_product"){
    $query ="SELECT * FROM `products` WHERE `product_id` = :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    $result = $result->fetch();
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
		$group_result = $db->query($group_query);
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
		$ventor_result = $db->query($ventor_query);
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
	
    $query ="SELECT * FROM `products` WHERE `product_id` = :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
    $result = $result->fetch();
    //changing image
    //deleting old image
    $old_image_path="img/product/".$result['image'];
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

    $query = "UPDATE `products` SET `group_id`=:product_group,`name`=:name,`price_usd`=:price_usd, `image`= '".$coverpic."',`description`= :product_description_text_en,`ventor_id` = :ventor,`ru_description` = :product_description_text_ru,`ua_description` = :product_description_text_ua WHERE `product_id` = :product_id";
    $result = $db->prepare($query);
    $result->bindParam(':product_group',$_POST['product_group']);
    $result->bindParam(':name',$_POST['name']);
    $result->bindParam(':price_usd',$_POST['price_usd']);
    $result->bindParam(':product_description_text_en',$_POST['product_description_text_en']);
    $result->bindParam(':product_description_text_ru',$_POST['product_description_text_ru']);
    $result->bindParam(':product_description_text_ua',$_POST['product_description_text_ua']);
    $result->bindParam(':ventor',$_POST['ventor']);
    $result->bindParam(':product_id',$_GET['product_id']);
    $result->execute();
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
