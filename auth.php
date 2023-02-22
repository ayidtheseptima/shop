<?php
session_start();
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <link rel="shortcut icon" href="img/top-logo.png" type="image/x-icon"/>
  <meta charset="utf-8">
  <title>KaminskyVVDR</title>
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
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/style.css\">";
  ?>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  
</head>

<body>
<?php

include'functions.php';
do_html_header_unlogged();
if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])){
/*connecting to bd*/
	include 'bd_connect.php';
	$link = mysqli_connect($host, $login, $password, $database) 
	     or die("Error " . mysqli_error($link));
	$query ="SELECT * FROM users WHERE email = '".$_POST['email']."'";
	$result = mysqli_query($link, $query) or die("Error " . mysqli_error($link));
	$result = mysqli_fetch_array($result);
  if (!empty($result['password'])) {
    if(md5($_POST['password'])==$result['password']){
      $_SESSION['user_id']=$result['user_id'];
      $_SESSION['role']=$result['role'];
      header('Location: index.php?action=news');
    }
    else{
      echo "
        <script>
          alert('Wrong password');
           window.location='auth.php';
        </script>";
    }
  }
  else{
    echo "
      <script>
        alert('Wrong username');
        window.location='auth.php';
      </script>


    ";
  }
	
}
else{
	echo "
	<div class=\"main_content\">
		<div class=\"authorization\">
			<div class=\"authorization__header\">
				Log in
			</div>
			<form method=\"post\" action=\"auth.php\">
				<input class=\"authorization-input\" type=\"email\" name=\"email\" placeholder=\"email\"><br>
				<input class=\"authorization-input\" type=\"password\" name=\"password\" placeholder=\"password\"><br>
				<input class =\"auauthorization-button\" type=\"submit\" value=\"login\"><br>
				or<br>
				<a href=\"sign_up.php\"><input type=\"button\" class =\"auauthorization-button\" value=\"Sign up\"></a>
			</form>
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
