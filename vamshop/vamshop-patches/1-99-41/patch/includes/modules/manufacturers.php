<?php
/* -----------------------------------------------------------------------------------------
   $Id: sitemap.php 782 2007-02-13 10:23:57 VaM $

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce; www.oscommerce.com
   (c) 2003	 nextcommerce; www.nextcommerce.org
   (c) 2004 xt:Commerce (sitemap.php,v 1.19 2004/08/25); xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

$module = new vamTemplate;
$module->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');

 $manufacturers_query = "SELECT m.*, mi.* FROM ".TABLE_MANUFACTURERS." as m 
                         left join ".TABLE_MANUFACTURERS_INFO." as mi 
                         on mi.manufacturers_id = m.manufacturers_id 
                         where mi.languages_id = '".$_SESSION['languages_id']."' 
                         and m.manufacturers_status = 1 order by m.sort_order, m.manufacturers_name asc limit ".MAX_DISPLAY_SEARCH_RESULTS."
                         ";

 // db Cache
 $manufacturers_query = vamDBquery($manufacturers_query);
 $module_content = array();
 $manufacturers_image = '';
 
 
 //$categories_man_query = "select distinct c.categories_id as id, cd.categories_name as name from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_TO_CATEGORIES." p2c, ".TABLE_CATEGORIES." c, ".TABLE_CATEGORIES_DESCRIPTION." cd where p.products_status = '1' and c.categories_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '".(int) $_SESSION['languages_id']."' and p.manufacturers_id = '2' order by c.sort_order, cd.categories_name asc";
 //$categories_man_query = vam_db_query($categories_man_query);
 //$categories_man = vam_db_fetch_array($categories_man_query);

 //while ($categories_man = vam_db_fetch_array($categories_man_query)) {
 	
 //echo $categories_man['categories_id'] . ' ff';
 
 //} 
 
$categories_man_query = '';

 while ($manufacturers = vam_db_fetch_array($manufacturers_query,true)) {

   $manufacturers_image = DIR_FS_CATALOG . DIR_WS_IMAGES . $manufacturers['manufacturers_image'];
 
	if(file_exists($manufacturers_image) && is_file($manufacturers_image)) {
		list($width, $height, $type, $attr) = getimagesize($manufacturers_image);
	}
	
		$star_rating = '';
		for($i=0;$i<number_format(vam_get_manufacturer_rating($manufacturers['manufacturers_id']));$i++)	{
		$star_rating .= '<span class="star-rating rating"><i class="star-rating-icon fas fa-star active"></i></span> ';
		}
		//$star_rating = '<span class="rating"><i class="far fa-star"></i></span> ';

$cats = array();
$categories_man_query = vam_db_query("select distinct c.categories_id, c.categories_image, cd.categories_name from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_TO_CATEGORIES." p2c, ".TABLE_CATEGORIES." c, ".TABLE_CATEGORIES_DESCRIPTION." cd where p.products_status = '1' and c.categories_status = '1' and p.products_id = p2c.products_id and p2c.categories_id = c.categories_id and p2c.categories_id = cd.categories_id and cd.language_id = '".(int) $_SESSION['languages_id']."' and p.manufacturers_id = '".$manufacturers['manufacturers_id']."' order by c.sort_order, cd.categories_name asc");

 while ($categories_man = vam_db_fetch_array($categories_man_query)) {
 	
 $cats[] = array(
 
 'CATEGORIES_ID' => $categories_man['categories_id'],
 'CATEGORIES_URL' => vam_href_link(FILENAME_DEFAULT, 'cat='.$categories_man['categories_id'].'&filter_id='.$manufacturers['manufacturers_id']),
 'CATEGORIES_NAME' => $categories_man['categories_name'],
 'CATEGORIES_IMAGE' => $categories_man['categories_image']
 
 );
 
 } 
 		
   $module_content[]=array('PRODUCTS_ID'  => $manufacturers['manufacturers_id'],
                           'PRODUCTS_NAME'  => $manufacturers['manufacturers_name'],
                           'PRODUCTS_SHORT_DESCRIPTION'  => $manufacturers['manufacturers_description'],
                           'PRODUCTS_IMAGE' => DIR_WS_IMAGES . $manufacturers['manufacturers_image'],
                           'PRODUCTS_IMAGE_WIDTH' => $width,
                           'PRODUCTS_IMAGE_HEIGHT' => $height,
                           'PRODUCTS_LINK'  => vam_href_link(FILENAME_DEFAULT, 'manufacturers_id='.$manufacturers['manufacturers_id']),
                           'CATS'  => $cats,
                           'REVIEWS_TOTAL'=> vam_get_manufacturer_rating_count($manufacturers['manufacturers_id']), 
                           'REVIEWS_TOTAL_RATING'=> vam_get_manufacturer_rating($manufacturers['manufacturers_id']), 
                           'REVIEWS_STAR_RATING'=> $star_rating
                           
   );
 }

//echo var_dump($module_content);

 // if there's sth -> assign it
 if (sizeof($module_content)>=1)
 {
 $module->assign('MANUFACTURERS_LINK', vam_href_link(FILENAME_MANUFACTURERS));
 $module->assign('language', $_SESSION['language']);
 $module->assign('module_content',$module_content);
 // set cache ID
 if (!CacheCheck()) {
 $module->caching = 0;
 $module = $module->fetch(CURRENT_TEMPLATE.'/module/manufacturers_default.html');
 } else {
 $module->caching = 1;
 $module->cache_lifetime=CACHE_LIFETIME;
 $module->cache_modified_check=CACHE_CHECK;
 $cache_id = $current_category_id.$_SESSION['language'].$_SESSION['customers_status']['customers_status_name'].$_SESSION['currency'];
 $module = $module->fetch(CURRENT_TEMPLATE.'/module/manufacturers_default.html',$cache_id);
 }
 	$default->assign('MODULE_manufacturers_default', $module);
 }
 
?>