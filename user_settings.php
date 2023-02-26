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
  <title>KaminskyVV BR</title>
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
    include'bd_connect.php';
    $db = new PDO('mysql:host='.$host.';dbname='.$database, $login, $password);
    include 'blocks/header.php';


    if(isset($_GET['action']) && !empty($_GET['action'])){
      if($_GET['action']=="update_info"){
        $query ="SELECT * FROM `users` WHERE `user_id` = '".$_SESSION['user_id']."'";
        $result = $db->query($query);
        $result = $result->fetch();

        $return="
      <div class=\"content\">
        <div class=\"container\">
          <div class=\"settings-header\"><h1>Update account info:</h1></div>
            <div class=\"settings-list\">
              <form action=\"user_settings.php?action=updating_info\" method=\"post\" enctype=\"multipart/form-data\">
                <div class=\"settings-list_item\">
                  <div class=\"settings-list_item-lable\">
                    Nickname
                  </div>
                  <div class=\"settings-list_item-val\">
                    <input type=\"text\" name=\"nickname\" class=\"settings-list_item-value\" required=\"\" value=\"".$result['nickname']."\">
                  </div>
                </div>
                <div class=\"settings-list_item\">
                  <div class=\"settings-list_item-lable\">
                    Email
                  </div>
                  <div class=\"settings-list_item-val\">
                    <input type=\"email\" name=\"email\" class=\"settings-list_item-value\" required=\"\" value=\"".$result['email']."\">
                  </div>
                </div>
                <div class=\"settings-list_item\">
                  <div class=\"settings-list_item-lable\">
                    Phone
                  </div>
                  <div class=\"settings-list_item-val\">
                    <input type=\"tel\" name=\"phone\" class=\"settings-list_item-value\" required=\"\" value=\"".$result['phone']."\">
                  </div>
                </div>
                <div class=\"settings-list_item\">
                  <div class=\"settings-list_item-lable\">
                    Password
                  </div>
                  <div class=\"settings-list_item-val\">
                    <input type=\"password\" name=\"password\" class=\"settings-list_item-value\" required=\"\">
                  </div>
                </div>
                <div class=\"settings-list_item\">
                  <div class=\"settings-list_item-lable\">
                    Avatar image
                  </div>
                  <div class=\"settings-list_item-val\">
                    <input type=\"file\"  name=\"article_image\" id=\"avatar_image\">
                  </div>
                </div>
                <input type=\"submit\" value=\"update\" class=\"settings-list_submit-button\">
              </form>
            </div>
            <div class=\"settings-header\">Choose theme</div>
            <div class=\"settings-list\">
              <div class=\"theme-option\">
                <a href=\"user_settings.php?action=set_theme&theme=dark\">-Dark</a>
              </div>
              <div class=\"theme-option\">
                <a href=\"user_settings.php?action=set_theme&theme=white\">-Light</a>
              </div>
            </div>
          </div>
        ";

        $return.="</div>";
        echo $return;
      }
      elseif ($_GET['action']=="updating_info") {
        $query="SELECT * FROM `users` WHERE `nickname` = :nickname";
        $nickname_result = $db->prepare($query);
        $nickname_result->bindParam(':nickname',$_POST['nickname']);
        $nickname_result->execute();
        $nickname_result = $nickname_result->fetch();
        if (!empty($nickname_result['user_id'])) {
          if ($nickname_result['user_id']!=$_SESSION['user_id']) {
            echo "
              <script type=\"text/javascript\">
                alert('This nickname is already in use!');
                window.location='user_settings.php?action=udpdate_info';
              </script>
            ";
            die();
          }
        }

        $query="SELECT * FROM `users` WHERE `email` = :email";
        $email_result = $db->prepare($query);
        $email_result->bindParam(':email',$_POST['email']);
        $email_result->execute();
        $email_result = $email_result->fetch();
        
        if (!empty($email_result['user_id'])) {
          if ($email_result['user_id']!=$_SESSION['user_id']) {
            echo "
              <script type=\"text/javascript\">
                alert('This email is already in use!');
                window.location='user_settings.php?action=udpdate_info';
              </script>
            ";
            die();
          }
        }

        $query="SELECT * FROM `users` WHERE `user_id` = '".$_SESSION['user_id']."'";
        $result = $db->query($query);
        $result = $result->fetch();
        //image
        //deleting old image
        if (!($result['user_image']=="21252.png")) {
          $old_image_path="img/user/".$result['user_image'];
          unlink($old_image_path);
        }
        //image upload
        $imgFile = $_FILES['article_image']['name'];
        $tmp_dir = $_FILES['article_image']['tmp_name'];
        $imgSize = $_FILES['article_image']['size'];

        if(!empty($imgFile)){
          $upload_dir = 'img/user/'; // upload directory
          $imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
          // valid image extensions
          $valid_extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp'); // valid extensions
          // rename uploading image
          $coverpic = $result['user_id'].".".$imgExt;
          // allow valid image file formats
          if(in_array($imgExt, $valid_extensions)){
            // Check file size '6MB'
            if($imgSize < 6000000){
              move_uploaded_file($tmp_dir,$upload_dir.$coverpic);
            }
          }
        }
        //updating info
        $password = md5($_POST['password']);
        $query = "UPDATE `users` SET `nickname`= :nickname, `phone`= :phone, `password`= :password,`email`= :email,`user_image`= :user_image WHERE `user_id`= '".$_SESSION['user_id']."'";
        $result = $db->prepare($query);
        $result->bindParam(':nickname',$_POST['nickname']);
        $result->bindParam(':phone',$_POST['phone']);
        $result->bindParam(':email',$_POST['email']);
        $result->bindParam(':password',$password);
        $result->bindParam(':user_image',$coverpic);

        $result->execute();

        //alert+redirect
        echo "
          <script type=\"text/javascript\">
            alert('Updated successfully');
            window.location='user_settings.php?action=update_info';
          </script>
          ";
        
      }
      elseif ($_GET['action']=="set_theme") {
        $_SESSION['theme']=$_GET['theme'];
        echo "
          <script type=\"text/javascript\">
            alert('Updated successfully');
            window.location='user_settings.php?action=update_info';
          </script>
          ";
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
