<?php
$return="
		<div class=\"content\">
			<div class=\"container\">
				<div class=\"products-list\">";
	
	$query ="SELECT * 
	FROM `products` INNER JOIN `products_group` USING (group_id) 
	WHERE grand_product_group_id = :grand_product_group ORDER BY last_delivery DESC";
	$result = $db->prepare($query);
	$result->bindParam(':grand_product_group',$_GET['grand_group_id']);
	$result->execute();

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
	$return.="</div></div></div>";
	echo $return;
?>