<?php

function vam_get_products_balance($id) {

	$bonus_query = vamDBquery("select p.customers_balance_added_products, p.manufacturers_id from " . TABLE_PRODUCTS . " p where p.products_id = '".(int)$id."'");
	$bonus = vam_db_fetch_array($bonus_query,true);
	$pb = $bonus['customers_balance_added_products'];

	if($bonus['manufacturers_id'] !=''){
		$manufacturer_query = vamDBquery("select customers_balance_added_manufacturers from " . TABLE_MANUFACTURERS . " where manufacturers_id = '" . (int)$bonus['manufacturers_id'] . "'");
		$manufacturer_balance = vam_db_fetch_array($manufacturer_query,true);
		$mb = $manufacturer_balance['customers_balance_added_manufacturers'];
	}

	$res_bonus = KOVALSKY_BONUS_PERSENT;
	if($mb > 0.001){
		$res_bonus = $mb;
	}
	if($pb > 0.001){
		$res_bonus = $pb;
	}
	//$real_bonus = $products_price['plain']/100*$res_bonus;

	return $res_bonus;
}

	
?>    