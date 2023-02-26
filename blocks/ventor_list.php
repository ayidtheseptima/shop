<?php
$return="
		<div class=\"content\">
			<div class=\"container\">
	";
	if ($_SESSION['role']>1) {
		$return.="
			<div class=\"admin-options\">
				<a href=\"brands.php?action=add_brand\"><input type=\"button\" class=\"button1\" value=\"Add new brand\"></a>
			</div>
		";
	}
	$return.="
				<div class=\"ventors-list\">
	";
	
	$query ="SELECT * FROM `ventors` ORDER BY ventor_id";
	$result = $db->query($query);
	foreach ($result as $key => $result) {
		$return.="
			<a href=\"index.php?action=products_by_ventor&ventor_id=".$result['ventor_id']."\">
				<div class=\"ventor\">
					<div class=\"ventor__image\">
						<img src=\"img/ventor/".$result['ventor_logo']."\" class=\"ventor__image-image\" loading=\"lazy\">
					</div>
		        <div class=\"ventor__name\">".$result['ventor_name']."</div>
		    	</div>
		   </a>
		";
	}
	$return.="</div></div></div>";
	echo $return;
?>