<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <title>KaminskyVV DR</title>
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
		session_start();
		require(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php');
		$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
		$dotenv->load();
		include 'bd_connect.php';
		$db = new PDO('mysql:host='.$host.';dbname='.$database, $login, $password);
		include 'blocks/header.php';

		if (isset($_POST['nickname']) && !empty($_POST['nickname'])
			&& isset($_POST['email']) && !empty($_POST['email'])
			&& isset($_POST['password2']) && !empty($_POST['password2'])
			&& isset($_POST['password']) && !empty($_POST['password'])) {
			
			if ($_POST['password']!==$_POST['password2']) {
				echo "
			        <script type=\"text/javascript\">
			          alert('Passwords don\'t match!');
			          window.location='sign_up.php';
			        </script>
			      ";
			      die();
			}
			
		    $query="SELECT * FROM `users` WHERE `nickname` = :nickname";
			$nickname_result = $db->prepare($query);
			$nickname_result->bindParam(':nickname',$_POST['nickname']);
			$nickname_result->execute();
		    $nickname_result = $nickname_result->fetch();
		    if (!empty($nickname_result['user_id'])) {
		    	echo "
			        <script type=\"text/javascript\">
			          alert('This nickname is already in use!');
			          window.location='sign_up.php';
			        </script>
			      ";
			      die();
		    }
		    
		    $query="SELECT * FROM `users` WHERE `email` = :email";
		    $email_result = $db->prepare($query);
			$email_result->bindParam(':email',$_POST['email']);
			$email_result->execute();
		    $email_result = $email_result->fetch();
		    if (!empty($email_result['user_id'])) {
		    	echo "
			        <script type=\"text/javascript\">
			          alert('This email is already in use!');
			          window.location='sign_up.php';
			        </script>
			      ";
			      die();
		    }
		    
			$password = md5($_POST['password']);
		    $query = "INSERT INTO `users` (`user_id`, `email`, `password`, `nickname`, `role`, `phone`,  `user_image`) 
		    VALUES (NULL, :email, :password, :nickname, '1', '', '21252.png');";
		    $result = $db->prepare($query);
			$result->bindParam(':email',$_POST['email']);
			$result->bindParam(':password', $password);
			$result->bindParam(':nickname',$_POST['nickname']);
			$result->execute();
		    echo "
			    <script type=\"text/javascript\">
			        alert('Registration complete!');
			        window.location='auth.php';
			    </script>
			 ";


			
		}
		else{
			echo "
			<div class=\"main-content\">
				<div class=\"container\">
					<h1 class=\"reg-form-head\">Sign up</h1>
					<div class=\"wrap\">
						<form action=\"sign_up.php\" method=\"post\">
							<div class=\"reg-form-item\">
								<div class=\"reg-form-item_lab\">Email</div>
								<div class=\"reg-form-item_inp\"><input type=\"email\" name=\"email\" required=\"\" class=\"reg-form-item_input\"></div>
							</div>
							<div class=\"reg-form-item\">
								<div class=\"reg-form-item_lab\">Nickname</div>
								<div class=\"reg-form-item_inp\"><input type=\"text\" name=\"nickname\" required=\"\" class=\"reg-form-item_input\"></div>
							</div>
							<div class=\"reg-form-item\">
								<div class=\"reg-form-item_lab\">Password</div>
								<div class=\"reg-form-item_inp\"><input type=\"password\" name=\"password\" required=\"\" class=\"reg-form-item_input\"></div>
							</div>
							<div class=\"reg-form-item\">
								<div class=\"reg-form-item_lab\">Comfirm password</div>
								<div class=\"reg-form-item_inp\"><input type=\"password\" name=\"password2\" required=\"\" class=\"reg-form-item_input\"></div>
							</div>
							<input type=\"submit\" class=\"reg-form-submit-button\" value=\"Sign up\">
						</form>
					</div>
				</div>
			</div>";
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
