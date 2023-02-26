<?php
	$return="<div class=\"content\">
					<div class=\"container\">	";
	if ($_SESSION['role'] ?? '0'>1) {
		$return .= "
			<div class=\"admin-options\">
				<a href=\"products.php?action=add_new_product\"><input type=\"button\" class=\"nav-button\" value=\"Add new product\"></a>
			</div>
		";
	}
	$return.="	<div class=\"products-list\">";
	include 'bd_connect.php';
	
	$result = $db->query("SELECT * FROM `products` ORDER BY last_delivery DESC");
	$display_number=0;
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=view_product&product_id=".$result['product_id']."\">
				<div class=\"product\" title=\"".$result['name']."\">
					<div class=\"product__image\">
						<img src=\"img/product/".$result['image']."\" class=\"product__image-image\" loading=\"lazy\">
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
	$result = null;
	    

	$return.="</div></div></div>";
	echo $return;
?>