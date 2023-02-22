<?php
session_start();
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
include'functions.php';
include 'bd_connect.php';
if(isset($_SESSION['role']) && !empty($_SESSION['role'])){
   do_html_header_logged($_SESSION['user_id'],$_SESSION['role']);
}
else{
   do_html_header_unlogged();
}
if(isset($_SESSION['role']) && !empty($_SESSION['role']) && isset($_GET['action']) && !empty($_GET['action'])){
  echo"
    <div class=\"content\">
      <div class=\"container\">
  ";
  if ($_GET['action']=="add_brand") {
    echo"
    <div class=\"add-brand\">
      <form action=\"brands.php?action=adding_brand\" method=\"post\" name=\"add_brand\" id=\"add_brand\" enctype=\"multipart/form-data\">
        <div class=\"add-brand__title\">
          Name:<input type=\"text\" name=\"name\" class=\"input1\" required=\"\">
        </div>
        <div class=\"add-brand__title\">
          <input type=\"file\" name=\"brand_image\" id=\"brand_image\" class=\"button1\" required=\"\">
        </div>
        <div class=\"add-brand__description-text\">
          Description:<br>
          <textarea  form=\"add_brand\"  id=\"brand_description\" class=\"add-brand-description-editor\" name=\"brand_description_text\" rows=\"12\" cols=\"55\" required=\"\"></textarea>
        </div>
        <div class=\"add-brand__submit-button\">
          <input type=\"submit\" class=\"button1\" value=\"Add new brand\">
        </div>
      </form>
    </div>
    ";
  }
  elseif($_GET['action']=="adding_brand"){
    $mysqli = new mysqli($host, $login, $password, $database);
    // check connection
    if ($mysqli->connect_errno) {
      die("Connect failed: ".$mysqli->connect_error);
    }
    $name = addslashes($_POST['name']);
    $query = "INSERT INTO `ventors`( `ventor_id`, `ventor_name`, `ventor_logo`, `ventor_description`) VALUES ('','".$name."','21252.png','".addslashes($_POST['brand_description_text'])."')";
    $result = $mysqli->query($query);
    
    $query ="SELECT * FROM `ventors` WHERE `ventor_name` = '".$name."'";
    $result = $mysqli->query($query);
    $result = $result->fetch_assoc();

    //image upload
    $imgFile = $_FILES['brand_image']['name'];
    $tmp_dir = $_FILES['brand_image']['tmp_name'];
    $imgSize = $_FILES['brand_image']['size'];
    $ventor_id = $result['ventor_id'];

    if(!empty($imgFile)){
      $upload_dir = 'img/ventor/'; // upload directory
      $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
      // valid image extensions
      $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp'); // valid extensions
      // rename uploading image
      $coverpic = $result['ventor_id'].".".$imgExt;
      // allow valid image file formats
      if(in_array($imgExt, $valid_extensions)){
        // Check file size '6MB'
        if($imgSize < 6000000){
          move_uploaded_file($tmp_dir,$upload_dir.$coverpic);
        }
      }
    }
    $query = "UPDATE `ventors` SET `ventor_logo`= '".$coverpic."' WHERE `ventor_id` = ".$ventor_id;
    $result = $mysqli->query($query);
    echo"</div>";
    echo "
      <script type=\"text/javascript\">
        alert('Added successfully');
        window.location='index.php?action=brand_list';
      </script>
    ";
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



echo"</div></div>";
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
