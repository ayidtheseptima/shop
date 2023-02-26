<?php
	$return="<div class=\"content\">
					<div class=\"container\">";

	$query ="SELECT * FROM `products` WHERE product_id = :product_id";
	$result = $db->prepare($query);
	$result->bindParam(':product_id',$_GET['product_id']);
	$result->execute();
	$result = $result -> fetch();
	
	if ($_SESSION['role']>1) {
		$return .= "
			<div class=\"admin-options\">
				<a href=\"products.php?action=add_quantity&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"Add quantity\"></a>
				<a href=\"products.php?action=edit_product&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"Edit product\"></a>
				<a href=\"products.php?action=delete_product&product_id=".$_GET['product_id']."\"><input type=\"button\" class=\"nav-button\" value=\"Delete product\"></a>
			</div>
		";
	}
	$return .="
	<div class=\"product-body\">
		<div class=\"product-info\">
			<div class=\"product-body_image\">
				<img src=\"img/product/".$result['image']."\" alt=\"".$result['name']."\">
			</div>
			<div class=\"product-body__main-info\">
				<div class=\"product-body_name\">".$result['name']."</div>
				<div class=\"product-body_price\">".$result['price_usd']."$</div>
				<form action=\"buy.php?action=adress_info&product_id=".$result['product_id']."\" method=\"post\">
					<input type=\"number\" name=\"quantity\" value=\"1\" class=\"product-body_quantity\" min=\"1\" max=\"".$result['quantity_available']."\">
					<input type=\"submit\" value=\"Buy\" class=\"button1\">
				</form>
			</div>
		</div>
		<div class=\"product-body_description\">".$result['description']."</div>	
	</div>";
	
	$query ="SELECT * FROM `products_comments` WHERE product_id = :product_id ORDER BY comment_time";
	$result = $db->prepare($query);
	$result->bindParam(':product_id',$_GET['product_id']);
	$result->execute();
	
	$return .="
		<div class=\"comments\">
			<div class=\"comments__header\">Comments:</div>";
			
	foreach ($result as $key => $result) {
		$user_query="SELECT * FROM `users` WHERE user_id = :user_id";
		$user_result = $db->prepare($user_query);
		$user_result->bindParam(':user_id',$result['user_id']);
		$user_result->execute();
		$user_result = $user_result ->fetch();
		$return .="
			<div class=\"comments__body\">
				<div class=\"comments__comment\">
					<div class=\"comments_all-info\">
						<div class=\"comments__user-info\">
							<div class=\"comments_user-image\" style=\" background-image: url(img/user/".$user_result['user_image'].");\">
							</div>
							<div class=\"comments_info\">
								<div class=\"comments__nickname\">".$user_result['nickname']."</div>
								<div class=\"comments__time\">".gmdate("Y-m-d\t H:i:s", $result['comment_time'])."</div>
							</div>
						</div>
						<div class=\"comments__administrate\">";
		if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
			if ($_SESSION['user_id']==$result['user_id']) {
				$return.="<a href=\"product_comments.php?action=delete_comment&comment_time=".$result['comment_time']."&user_id=".$result['user_id']."\"><img class=\"comments__delete-image\" src=\"img/iconfinder_trash-bin-garbage-delete-rubbish-waste_3643729.png\"></a>";
			}
			elseif ($_SESSION['role']>$user_result['role']) {
				$return.="<a href=\"product_comments.php?action=delete_comment&comment_time=".$result['comment_time']."&user_id=".$result['user_id']."\"><img class=\"comments__delete-image\" src=\"img/iconfinder_trash-bin-garbage-delete-rubbish-waste_3643729.png\"></a>";
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
				<form method=\"post\" action=\"product_comments.php?action=add_comment&return_id=".$_GET['product_id']."\" name=\"send_news_comment_form\" id=\"send_news_comment_form\">
					<textarea required=\"\" form=\"send_news_comment_form\"class=\"comments-form__editor\" id=\"comment-editor\" name=\"comment-editor\"></textarea><br>
					<input type=\"submit\" value=\"Send comment\">
				</form>
			</div>
		</div>";
	}
	else{
		$return .= "<div class=\"not-auth-comment\">Log in or Sign up to leave comments.</div>";
	}
	$return.="</div>";
	echo $return;
?>