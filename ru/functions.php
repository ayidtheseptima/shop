<?php


header('Content-Type: text/html; charset=utf-8');
function do_html_header_unlogged(){
	echo "<div class=\"wrapper\">
	<header><ul class=\"header\">
	    <li class=\"header__part header_logo\"><a href=\"index.php?action=news&page=1\">KaminskyVVDR</a></li>
	    <li class=\"header__part header__navigation\">
	    	<form method=\"post\" action=\"index.php?action=search\">
	    		<input type=\"text\" name=\"search-request\" autocomplete=\"off\" class=\"search-form_request-field\" placeholder=\"Поиск\">
	    	</form>
	    </li>
	    <li class=\"header__part header__navigation\"><a href=\"auth.php\">Ввойти</a></li>
	    		 
	  </ul>
	  <nav class=\"menu\">
	  	<ul class=\"menu__list\">
	  		<li>
	  			<a href=\"index.php?action=news\" class=\"menu__link\">Новинки</a>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=products_by_grand_group&grand_group_id=1\" class=\"menu__link\">Перефирия</a>
	  			<span class=\"menu__arrow arrow\"></span>
	  			<ul class=\"sub-menu__list\">
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=1\" class=\"sub-menu__link\">Клавиатуры</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=2\" class=\"sub-menu__link\">Мыши</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=3\" class=\"sub-menu__link\">Коврики для мыши</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=4\" class=\"sub-menu__link\">Устройства хранения данных</a>
	  				</li>
	  			</ul>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=products_by_grand_group&grand_group_id=2\" class=\"menu__link\">Консоли</a>
	  			<span class=\"menu__arrow arrow\"></span>
	  			<ul class=\"sub-menu__list\">
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=5\" class=\"sub-menu__link\">Playstation</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=6\" class=\"sub-menu__link\">XBOX</a>
	  				</li>
	  			</ul>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=products_by_grand_group&grand_group_id=3\" class=\"menu__link\">Мобильные аксессуары</a>
	  			<span class=\"menu__arrow arrow\"></span>
	  			<ul class=\"sub-menu__list\">
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=7\" class=\"sub-menu__link\">Колонки для мобильных</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=8\" class=\"sub-menu__link\">Внешний аккумулятор</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=9\" class=\"sub-menu__link\">Разное</a>
	  				</li>
	  			</ul>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=brand_list\" class=\"menu__link\">Список брендов</a>
	  		</li>
	  	</ul>
	  </nav>
	  </header>
	  ";
}
function do_html_header_logged($user_id,$user_role){
	include '../bd_connect.php';
	$link = mysqli_connect($host, $login, $password, $database) 
	     or die("Error " . mysqli_error($link));
	$query ="SELECT * FROM users WHERE user_id = '".$_SESSION['user_id']."'";
	$result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
	$result = mysqli_fetch_array($result);
	echo "<div class=\"wrapper\">
	<header>
		<ul class=\"header\">
	   	<li class=\"header__part header_logo\"><a href=\"index.php?action=news&page=1\">KaminskyVVDR</a></li>
	   	<li class=\"header__part header__navigation\">
	    	<form method=\"post\" action=\"index.php?action=search\">
	    		<input type=\"text\" name=\"search-request\" autocomplete=\"off\" class=\"search-form_request-field\" placeholder=\"Поиск\">
	    	</form>
	   	</li>
	   	<li class=\"header__part droplist\">
	      	<img class=\"header__user-image\" alt=\"".$result['nickname']."\" src=\"../img/user/".$result['user_image']."\"> ".$result['nickname']."
	      	<ul class=\"droplist__list\">
	      		<li><a href=\"user_settings.php?action=update_info\">Настройки</a></li>
	      		<li><a href=\"index.php?action=orders_list\">Мои заказы</a></li>";
	   if ($user_role>1) {
	   	echo"
	   			<li><a href=\"orders.php?action=view_orders\">Заказы</a></li>
	   	";
	   }
	   echo"
	      		<li><a href=\"index.php?action=log_out\">Выйти</a></li>
	      	</ul>
	    	</li>
	    		 
	  </ul>
	  <nav class=\"menu\">
	  	<ul class=\"menu__list\">
	  		<li>
	  			<a href=\"index.php?action=news\" class=\"menu__link\">Новинки</a>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=products_by_grand_group&grand_group_id=1\" class=\"menu__link\">Перефирия</a>
	  			<span class=\"menu__arrow arrow\"></span>
	  			<ul class=\"sub-menu__list\">
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=1\" class=\"sub-menu__link\">Клавиатуры</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=2\" class=\"sub-menu__link\">Мыши</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=3\" class=\"sub-menu__link\">Коврики для мыши</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=4\" class=\"sub-menu__link\">Устройства для хранения данных</a>
	  				</li>
	  			</ul>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=products_by_grand_group&grand_group_id=2\" class=\"menu__link\">Консоли</a>
	  			<span class=\"menu__arrow arrow\"></span>
	  			<ul class=\"sub-menu__list\">
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=5\" class=\"sub-menu__link\">Playstation</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=6\" class=\"sub-menu__link\">XBOX</a>
	  				</li>
	  			</ul>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=products_by_grand_group&grand_group_id=3\" class=\"menu__link\">Мобильные аксессуары</a>
	  			<span class=\"menu__arrow arrow\"></span>
	  			<ul class=\"sub-menu__list\">
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=7\" class=\"sub-menu__link\">Колонки для мобильных</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=8\" class=\"sub-menu__link\">Внешний аккумулятор</a>
	  				</li>
	  				<li>
	  					<a href=\"index.php?action=products_by_group&group_id=9\" class=\"sub-menu__link\">Разное</a>
	  				</li>
	  			</ul>
	  		</li>
	  		<li>
	  			<a href=\"index.php?action=brand_list\" class=\"menu__link\">Список брендов</a>
	  		</li>
	  	</ul>
	  </nav>
	</header>
	";
}
function do_html_body(){
	echo"<div class=\"content\">
		<div class=\"container\">content ll be here
		</div>
	</div>";
}
function do_html_body_news($user_role){
	$return="<div class=\"content\">
					<div class=\"container\">	";
	if ($user_role>1) {
		$return .= "
			<div class=\"admin-options\">
				<a href=\"products.php?action=add_new_product\"><input type=\"button\" class=\"nav-button\" value=\"Добавить новый продукт\"></a>
			</div>
		";
	}
	$return.="	<div class=\"products-list\">";
	include '../bd_connect.php';
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * FROM `products` ORDER BY last_delivery DESC";
	$result = $mysqli->query($query);

	$display_number=0;
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=view_product&product_id=".$result['product_id']."\">
				<div class=\"product\" title=\"".$result['name']."\">
					<div class=\"product__image\">
						<img src=\"../img/product/".$result['image']."\" class=\"product__image-image\" loading=\"lazy\">
					</div>
		        <div class=\"product__name\">".$result['name']."</div>
		        <div class=\"product__price\">".$result['price_usd']."$</div>
		    	</div>
		   </a>
		";
		$display_number++;
		if ($display_number>49) {
			break;
		}
	}

	    

	$return.="</div></div></div>";
	echo $return;
}
function do_html_body_product_by_group($group_id,$role){
	$return="
		<div class=\"content\">
			<div class=\"container\">
				<div class=\"products-list\">";
	include '../bd_connect.php';	
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * FROM `products` WHERE group_id = '".$group_id."' ORDER BY last_delivery DESC";
	$result = $mysqli->query($query);
	
	$display_number=0;
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=view_product&product_id=".$result['product_id']."\">
				<div class=\"product\" title=\"".$result['name']."\">
					<div class=\"product__image\">
						<img src=\"../img/product/".$result['image']."\" class=\"product__image-image\" loading=\"lazy\">
					</div>
		        <div class=\"product__name\">".$result['name']."</div>
		        <div class=\"product__price\">".$result['price_usd']."$</div>
		    	</div>
		   </a>
		";
		$display_number++;
		if ($display_number>49) {
			break;
		}
	}
	$return.="</div></div></div>";
	echo $return;
}
function do_html_body_product_by_grand_group($grand_product_group_id,$role){
	$return="
		<div class=\"content\">
			<div class=\"container\">
				<div class=\"products-list\">";
	include '../bd_connect.php';	
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * 
	FROM `products` INNER JOIN `products_group` USING (group_id) 
	WHERE grand_product_group_id = '".$grand_product_group_id."' ORDER BY last_delivery DESC";
	$result = $mysqli->query($query);
	
	$display_number=0;
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=view_product&product_id=".$result['product_id']."\">
				<div class=\"product\" title=\"".$result['name']."\">
					<div class=\"product__image\">
						<img src=\"../img/product/".$result['image']."\" class=\"product__image-image\" loading=\"lazy\">
					</div>
		        <div class=\"product__name\">".$result['name']."</div>
		        <div class=\"product__price\">".$result['price_usd']."$</div>
		    	</div>
		   </a>
		";
		$display_number++;
		if ($display_number>49) {
			break;
		}
	}
	$return.="</div></div></div>";
	echo $return;
}
function do_html_body_ventor_list($role){
	$return="
		<div class=\"content\">
			<div class=\"container\">
	";
	if ($_SESSION['role']>1) {
		$return.="
			<div class=\"admin-options\">
				<a href=\"brands.php?action=add_brand\"><input type=\"button\" class=\"button1\" value=\"Добавить новый бренд\"></a>
			</div>
		";
	}
	$return.="
				<div class=\"ventors-list\">
	";
	include '../bd_connect.php';	
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * FROM `ventors` ORDER BY ventor_id";
	$result = $mysqli->query($query);
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=products_by_ventor&ventor_id=".$result['ventor_id']."\">
				<div class=\"ventor\">
					<div class=\"ventor__image\">
						<img src=\"../img/ventor/".$result['ventor_logo']."\" class=\"ventor__image-image\" loading=\"lazy\">
					</div>
		        <div class=\"ventor__name\">".$result['ventor_name']."</div>
		    	</div>
		   </a>
		";
	}
	$return.="</div></div></div>";
	echo $return;
}
function do_html_body_product_by_ventor($ventor_id,$role){
	$return="
		<div class=\"content\">
			<div class=\"container\">
				<div class=\"products-list\">";
	include '../bd_connect.php';	
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * FROM `products` WHERE ventor_id = '".$ventor_id."' ORDER BY last_delivery DESC";
	$result = $mysqli->query($query);
	
	$display_number=0;
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=view_product&product_id=".$result['product_id']."\">
				<div class=\"product\" title=\"".$result['name']."\">
					<div class=\"product__image\">
						<img src=\"../img/product/".$result['image']."\" class=\"product__image-image\" loading=\"lazy\">
					</div>
		        <div class=\"product__name\">".$result['name']."</div>
		        <div class=\"product__price\">".$result['price_usd']."$</div>
		    	</div>
		   </a>
		";
		$display_number++;
		if ($display_number>49) {
			break;
		}
	}
	$return.="</div></div></div>";
	echo $return;
}
function do_html_body_product_info($product_id,$role){
	$return="<div class=\"content\">
					<div class=\"container\">";

	include '../bd_connect.php';
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * FROM `products` WHERE product_id = '".$product_id."'";
	$result = $mysqli->query($query);
	$result = $result->fetch_assoc();
	
	if ($role>1) {
		$return .= "
			<div class=\"admin-options\">
				<a href=\"products.php?action=add_quantity&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"Добавить количество\"></a>
				<a href=\"products.php?action=edit_product&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"Изменить продукт\"></a>
				<a href=\"products.php?action=delete_product&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"Удалить продукт\"></a>
			</div>
		";
	}
	$return .="
	<div class=\"product-body\">
		<div class=\"product-info\">
			<div class=\"product-body_image\">
				<img src=\"../img/product/".$result['image']."\" alt=\"".$result['name']."\">
			</div>
			<div class=\"product-body__main-info\">
				<div class=\"product-body_name\">".$result['name']."</div>
				<div class=\"product-body_price\">".$result['price_usd']."$</div>
				<form action=\"buy.php?action=adress_info&product_id=".$result['product_id']."\" method=\"post\">
					<input type=\"number\" name=\"quantity\" value=\"1\" class=\"product-body_quantity\" min=\"1\" max=\"".$result['quantity_available']."\">
					<input type=\"submit\" value=\"Купить\" class=\"button1\">
				</form>
			</div>
		</div>
		<div class=\"product-body_description\">".$result['ru_description']."</div>	
	</div>";
	
	$query ="SELECT * FROM `products_comments` WHERE product_id = '".$product_id."' ORDER BY comment_time";
	$result = $mysqli->query($query);
	
	$return .="
		<div class=\"comments\">
			<div class=\"comments__header\">Коментрии:</div>";
			
	foreach ($result as $key => $result) {
		$user_query="SELECT * FROM `users` WHERE user_id = '".$result['user_id']."'";
		$user_result = $mysqli->query($user_query);
		$user_result = $user_result->fetch_assoc();
		$return .="
			<div class=\"comments__body\">
				<div class=\"comments__comment\">
					<div class=\"comments_all-info\">
						<div class=\"comments__user-info\">
							<div class=\"comments_user-image\" style=\" background-image: url(../img/user/".$user_result['user_image'].");\">
							</div>
							<div class=\"comments_info\">
								<div class=\"comments__nickname\">".$user_result['nickname']."</div>
								<div class=\"comments__time\">".gmdate("Y-m-d\t H:i:s", $result['comment_time'])."</div>
							</div>
						</div>
						<div class=\"comments__administrate\">";
		if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
			if ($_SESSION['user_id']==$result['user_id']) {
				$return.="<a href=\"product_comments.php?action=delete_comment&comment_time=".$result['comment_time']."&user_id=".$result['user_id']."\"><img class=\"comments__delete-image\" src=\"../img/iconfinder_trash-bin-garbage-delete-rubbish-waste_3643729.png\"></a>";
			}
			elseif ($_SESSION['role']>$user_result['role']) {
				$return.="<a href=\"product_comments.php?action=delete_comment&comment_time=".$result['comment_time']."&user_id=".$result['user_id']."\"><img class=\"comments__delete-image\" src=\"../img/iconfinder_trash-bin-garbage-delete-rubbish-waste_3643729.png\"></a>";
			}
		}
		$return.="					
						</div>
					</div>
					<div class=\"comments__text\">".$result['comment_text']."</div>
				</div>
			</div>";
	}
	if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {		
		$return .="
			</div>
			<div class=\"comments-form\">
				<form method=\"post\" action=\"product_comments.php?action=add_comment&return_id=".$product_id."\" name=\"send_news_comment_form\" id=\"send_news_comment_form\">
					<textarea required=\"\" form=\"send_news_comment_form\"class=\"comments-form__editor\" id=\"comment-editor\" name=\"comment-editor\"></textarea><br>
					<input type=\"submit\" value=\"Send comment\">
				</form>
			</div>
		</div>";
	}
	else{
		$return .= "<div class=\"not-auth-comment\">Ввойдите или зарегистрируйтесь для того что бы оставлять коментарии.</div>";
	}
	$return.="</div>";
	echo $return;
}
function do_html_body_search($role){
	$return="<div class=\"content\">
					<div class=\"container\">
						<div class=\"search-header\">
							Поиск: ".$_POST['search-request']."
						</div>
						<div class=\"products-list\">";
	include '../bd_connect.php';	
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * FROM `products` WHERE `name` LIKE '%".$_POST['search-request']."%'";
	$result = $mysqli->query($query);
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=view_product&product_id=".$result['product_id']."\">
				<div class=\"product\" title=\"".$result['name']."\">
					<div class=\"product__image\">
						<img src=\"../img/product/".$result['image']."\" class=\"product__image-image\" loading=\"lazy\">
					</div>
		        <div class=\"product__name\">".$result['name']."</div>
		        <div class=\"product__price\">".$result['price_usd']."$</div>
		    	</div>
		   </a>
		";
	}
	$return.="</div></div></div>";
	echo $return;
}
function do_html_body_orders_list($role,$user_id){
	$return="<div class=\"content\">
					<div class=\"container\">
						<div class=\"orders-list\">";
	include '../bd_connect.php';
	$mysqli = new mysqli($host, $login, $password, $database);
	// check connection
	if ($mysqli->connect_errno) {
	    die("Connect failed: ".$mysqli->connect_error);
	}
	$query ="SELECT * FROM `orders` WHERE `user_id`='".$user_id."' ORDER BY `order_time` DESC";
	$result = $mysqli->query($query);
	foreach ($result as $key => $result) {
		if ($result['status']=="1") {
			$status="Recived";
		}
		elseif ($result['status']=="2") {
			$status="Sent";
		}
		elseif ($result['status']=="3") {
			$status="Delivered";
		}
		elseif ($result['status']=="101") {
			$status="Canceled";
		}
		else{
			$status="Error";
		}
		if (isset($result['tracking_number']) && !empty($result['tracking_number'])) {
			$tracking_number = $result['tracking_number'];
		}
		else{
			$tracking_number = "Not available yet";
		}
		$products_query ="SELECT `name` FROM `products` WHERE `product_id` ='".$result['product_id']."'";
		$products_result = $mysqli->query($products_query);
		$products_result = $products_result->fetch_assoc();
		$return.= "
			<div class=\"order\">
				<table>
					<tr>
						<td>id заказа:</td>
						<td>".$result['order_id']."</td>
					</tr>
					<tr>
						<td>id продукта:</td>
						<td>".$result['product_id']."</td>
					</tr>
					<tr>
						<td>Название:</div>
						<td>".$products_result['name']."</td>
					</tr>
					<tr>
						<td>Количество:</div>
						<td>".$result['quantity']."</td>
					</tr>
					<tr>
						<td>Время заказа:</td>
						<td>".gmdate("Y-m-d\t H:i:s", $result['order_time'])."</td>
					</tr>
					<tr>
						<td>Статус:</td>
						<td>".$status."</td>
					</tr>
					<tr>
						<td>Адрес доставки:</td>
						<td>".$result['adress']."</td>
					</tr>
					<tr>
						<td>Tracking number:</td>
						<td>".$tracking_number."</td>
					</tr>
				</table>";
		if ($_SESSION['user_id']==$result['user_id'] && $result['status']<3) {
			$return.="
			<div class=\"order_buttons\">
				<a href=\"orders.php?action=confirm&order_id=".$result['order_id']."\"><input type=\"button\" class=\"button1\" value=\"Confirm fullfilment\"><a>";
			if ($result['status']==1) {
				$return.="<a href=\"orders.php?action=cancel&order_id=".$result['order_id']."\"><input type=\"button\" class=\"button1\" value=\"Cancel order\"></a>";
			}
			$return.="</div>";
		}		
		$return.="</div>";
	}
	$return.="</div></div></div>";
	echo $return;
}
function do_html_footer(){
	echo"<footer class=\"footer\">
		<div class=\"container\">
			<div class=\"higher-footer\">
				<div class=\"higher-footer__container\">
					<h3>Customer Service</h3>
					<ul>
						<li><a href=\"#\">Сервис</a></li>
						<li><a href=\"#\">FAQ</a></li>
					</ul>
				</div>
				<div class=\"higher-footer__container\">
					<h3>KaminskyVVDR</h3>
					<ul>
						<li><a href=\"#\">Cookie Policy</a></li>
						<li><a href=\"#\">Про нас</a></li>
						<li><a href=\"#\">Способы оплаты</a></li>
					</ul>
				</div>
				<div class=\"higher-footer__container\">
					<h3>Follow Us</h3>
					<ul>
						<li><a href=\"#\"><img src=\"../img/facebook.svg\">Facebook</a></li>
						<li><a href=\"#\"><img src=\"../img/twitter.svg\">Twitter</a></li>
					</ul>
				</div>
			</div>
			<div class=\"lower-footer\">
				© 2022-2022 KaminskyVVDR. Все права защищены.
			</div>
	</footer></div>";
}
?>