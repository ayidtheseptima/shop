<?php
	$return="<div class=\"content\">
					<div class=\"container\">
						<div class=\"search-header\">
							Searching: ".$_POST['search-request']."
						</div>
						<div class=\"products-list\">";
	
	$search_request = "%".$_POST['search-request']."%";
	$query ="SELECT * FROM `products` WHERE `name` LIKE :search_request";
	$result = $db->prepare($query);
	$result->bindParam(':search_request', $search_request);
	$result->execute();
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
	}
	$return.="</div></div></div>";
	echo $return;
?>