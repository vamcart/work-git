<?php
/* -----------------------------------------------------------------------------------------
   $Id: ajaxQuickFind.php 1243 2009-02-06 20:41:56 VaM $   

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2006	 Andrew Berezin (ajaxQuickFind.php,v 1.9 2003/08/17); zen-cart.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

	define("AJAX_QUICKSEARCH_RESULT", 'text'); // dropdown or text
	define("AJAX_QUICKSEARCH_DROPDOWN_SIZE", 5);
	define("AJAX_QUICKSEARCH_LIMIT", 10);

	$q = addslashes(preg_replace("%[^0-9a-zA-Zа-яА-Я\s]%iu", "", $_REQUEST['keywords']) );

	$out = "";
	if(isset($q) && vam_not_null($q)) {

		$searchwords = explode(" ",$q);
		$nosearchwords = sizeof($searchwords);
		foreach($searchwords as $key => $value) {
			if ($value == '')
				unset($searchwords[$key]);
		}
		$searchwords = array_values($searchwords);
		$nosearchwords = sizeof($searchwords);
		foreach($searchwords as $key => $value) {
			//$booltje = '+' . $searchwords[$key] . '*';
			$booltje = $searchwords[$key];
			$searchwords[$key] = $booltje;
		}
		$q = implode(" ",$searchwords);
		
		//echo $q;

//fsk18 lock
$fsk_lock = '';
if ($_SESSION['customers_status']['customers_fsk18_display'] == '0') {
	$fsk_lock = ' and p.products_fsk18!=1';
}
if (GROUP_CHECK == 'true') {
	$group_check = " and p.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
}

    // sorting query
    $sorting_query = vamDBquery("SELECT products_sorting,
                                                products_sorting2 FROM ".TABLE_CATEGORIES."
                                                where categories_id='".(int) $_GET['filter_id']."'");
    $sorting_data = vam_db_fetch_array($sorting_query,true);
    my_sorting_products($sorting_data);
    if (!$sorting_data['products_sorting'])
    $sorting_data['products_sorting'] = 'pd.products_name';
    $sorting = ' ORDER BY '.$sorting_data['products_sorting'].' '.$sorting_data['products_sorting2'].' ';

	//build query
	$select_str = "SELECT distinct
	                  p.*, pd.* ";

	$from_str  = "FROM ".TABLE_PRODUCTS."  AS p LEFT JOIN ".TABLE_PRODUCTS_DESCRIPTION." AS pd ON (p.products_id = pd.products_id and products_status = 1) LEFT JOIN ".TABLE_MANUFACTURERS." AS m ON (p.manufacturers_id = m.manufacturers_id) LEFT JOIN products_to_categories as p2c2 ON (p2c2.products_id=p.products_id) LEFT JOIN categories as c ON (c.categories_id=p2c2.categories_id)";
	//$from_str .= $subcat_join;
	//if (SEARCH_IN_ATTR == 'true') { $from_str .= " LEFT OUTER JOIN ".TABLE_PRODUCTS_ATTRIBUTES." AS pa ON (p.products_id = pa.products_id) LEFT OUTER JOIN ".TABLE_PRODUCTS_OPTIONS_VALUES." AS pov ON (pa.options_values_id = pov.products_options_values_id) "; }
	$from_str .= "LEFT OUTER join " . TABLE_PRODUCTS_SPECIFICATIONS . " psv ON (p.products_id = psv.products_id) left join ".TABLE_SPECIALS." AS s ON (p.products_id = s.products_id) AND s.status = '1'";
   $from_str .= " LEFT OUTER JOIN ".TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS." AS pe ON (p.products_id = pe.products_id)";

	//if ((DISPLAY_PRICE_WITH_TAX == 'true') && ((isset ($_GET['pfrom']) && vam_not_null($_GET['pfrom'])) || (isset ($_GET['pto']) && vam_not_null($_GET['pto'])))) {
		//if (!isset ($_SESSION['customer_country_id'])) {
			//$_SESSION['customer_country_id'] = STORE_COUNTRY;
			//$_SESSION['customer_zone_id'] = STORE_ZONE;
		//}
		//$from_str .= " LEFT OUTER JOIN ".TABLE_TAX_RATES." tr ON (p.products_tax_class_id = tr.tax_class_id) LEFT OUTER JOIN ".TABLE_ZONES_TO_GEO_ZONES." gz ON (tr.tax_zone_id = gz.geo_zone_id) ";
		//$tax_where = " AND (gz.zone_country_id IS NULL OR gz.zone_country_id = '0' OR gz.zone_country_id = '".(int) $_SESSION['customer_country_id']."') AND (gz.zone_id is null OR gz.zone_id = '0' OR gz.zone_id = '".(int) $_SESSION['customer_zone_id']."')";
	//} else {
		//$tax_where='';
	//}

	$stemmer = new Lingua_Stem_Ru();
	
	//where-string
	$where_str = " WHERE pd.products_keywords LIKE ('%".addslashes($stemmer->stem_word($q))."%') OR pd.products_name LIKE ('%".addslashes($stemmer->stem_word($q))."%') OR p.products_model LIKE ('%".addslashes($stemmer->stem_word($q))."%') and p.products_status = '1' "." AND c.categories_status=1 AND pd.language_id = '".(int) $_SESSION['languages_id']."'".$subcat_where.$fsk_lock.$manu_check.$group_check.$tax_where.$pfrom_check.$pto_check . " limit " . AJAX_QUICKSEARCH_LIMIT;

  // optional Product List Filter

    $filterlist_sql = $select_str.$from_str.$where_str;
    
    $products_query = vam_db_query($filterlist_sql);



		if(vam_db_num_rows($products_query)) {
			$dropdown = array();
			$out .= '
<div id="searchPreview">
<table class="table table-sm table-striped table-hover">
  <thead>
	<tr>
		<th colspan="3">'.sprintf(TEXT_AJAX_QUICKSEARCH_TOP, AJAX_QUICKSEARCH_LIMIT).'</th>
	</tr>
	</thead>
  <tbody>			
			
			';
			while($products = vam_db_fetch_array($products_query)) {

		$quick_find_products_price = $vamPrice->GetPrice($products['products_id'], $format = true, 1, $products['product_tax_class_id'], $products['products_price'], 1);
		$quick_find_price = $quick_find_products_price['formated'];

		if ($products['products_image'] != '')
			$image = DIR_WS_INFO_IMAGES.$products['products_image'];
	   
	   if (!file_exists($image)) $image = DIR_WS_IMAGES.'product_images/noimage.png';

				$out .= '
				
	<tr>
		<td class="text-center"><img class="media-object" src="'.$image.'" alt="'.$products['products_name'].'" width="40" height="40" /></td>
		<td><a href="' . vam_href_link(FILENAME_PRODUCT_INFO, vam_product_link($products['products_id'], $products['products_name']), 'NONSSL', false) . '">' . $products['products_name'] . '</a></td>
		<td>'.$quick_find_price.'</td>
	</tr>
				
				
				' . "\n";
				$dropdown[] = array('id' => $products['products_id'],
														'text' => $products['products_name']);
			}
			$out .= '
			
	<tr>
      <td colspan="3" class="text-center"><a href="'.DIR_WS_CATALOG.FILENAME_ADVANCED_SEARCH_RESULT.'?keywords='.htmlspecialchars(vam_db_input($_REQUEST['keywords'])).'">'.TEXT_SHOW_ALL.'</a></td>
	</tr>
  </tbody>
</table>			
</div>			
			' . "\n";
			$out .= '
					   <script>
					   $(document).ready(function() {
							$("#ajaxQuickFind").show();
						$(document).click(function (){
							$("#ajaxQuickFind").hide();
						});		
						});		
						</script>	
			';
			if(AJAX_QUICKSEARCH_RESULT == 'dropdown') {
				$out .= vam_draw_pull_down_menu('AJAX_QUICKSEARCH_pid', $dropdown, '', 'onChange="this.form.submit();" size="' . AJAX_QUICKSEARCH_DROPDOWN_SIZE . '" class="ajaxQuickFind"') . vam_hide_session_id();
			}
		}
	}
	echo $out;
?>