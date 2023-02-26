<?php
	$return="<div class=\"content\">
					<div class=\"container\">
						<div class=\"orders-list\">";
	
	$query ="SELECT * FROM `orders` WHERE `user_id`='".$_SESSION['user_id']."' ORDER BY `order_time` DESC";
	$result = $db->query($query);
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
		$products_result = $db->query($products_query);
		$products_result = $products_result->fetch();
		$return.= "
			<div class=\"order\">
				<table>
					<tr>
						<td class=\"order-quality-name\">Order id:</td>
						<td>".$result['order_id']."</td>
					</tr>
					<tr>
						<td>Product id:</td>
						<td>".$result['product_id']."</td>
					</tr>
					<tr>
						<td>Product name:</div>
						<td>".$products_result['name']."</td>
					</tr>
					<tr>
						<td>Quantity:</div>
						<td>".$result['quantity']."</td>
					</tr>
					<tr>
						<td>Order time:</td>
						<td>".gmdate("Y-m-d\t H:i:s", $result['order_time'])."</td>
					</tr>
					<tr>
						<td>Status</td>
						<td>".$status."</td>
					</tr>
					<tr>
						<td>Adress:</td>
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
?>