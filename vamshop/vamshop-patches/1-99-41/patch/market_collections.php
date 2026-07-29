<?php
/**
 * yml.php
 *
 * @package yml feed
 * @copyright Copyright 2005-2008 Andrew Berezin eCommerce-Service.com
 * @copyright Portions Copyright 2003-2006 Zen Cart Development Team
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: yml.php,v 3.12 27.07.2008 17:52 Andrew Berezin $
 */
// http://partner.market.yandex.ru/legal/tt/
/*
1. Язык, в котором отдаётся yml, определяется по умолчанию или задаётся в адресной строке.
2. Валюта, в которой отдаются цены, определяется по умолчанию или задаётся в адресной строке.
   Т.е. можно определять для сторонних сайтов ссылки вида:
   http://<domain>/yml.php?language=ru&currency=RUR
3. Изготовителя может быть задан, а может и не быть задан;
4. Короткие описания могут быть установлены, а могут и не быть установлены;
5. Поле yml-флага в таблице товаров может существовать, а может и не существовать.
6. Все валюты и их курсы формируются автоматически;
7. Все ссылки на товар и картинки преобразуются в соответсвии с правилами (urlencode()),
   что решает проблему с использованием нестандарных символов в ссылках.
8. Поддержка yml-флага для категорий, содержащих только товары;
9. Поддержка нескольких категорий для товара;
10. Поддержка доступа по паролю (логин/пароль можно задать в админе или определить здесь,
    в константах). Константы YML_AUTH_USER, YML_AUTH_PW;
11. Доставка включена или нет определяется константой доступа по паролю (логин/пароль можно
    задать в админе или определить здесь, в константах). Константа YML_DELIVERYINCLUDED;
12. Поддерживает типы продуктов (страницы отображения информации о товаре для разных типов
    товара);
13. Поддержка <offer available; Константа YML_AVAILABLE может принимать одно из трёх значений:
    "true", "false" и "stock". В последнем случае доступность товара определяется по наличию его на складе
    (поле products_quantity);
14. Добавлены константы YML_NAME & YML_COMPANY;
15. Добавлены константы YML_REF_ID и YML_REF_IP (для тех, кто не умеет отслеживать заходы иначе);
16. Добавлена опция "убирания" тегов (константа YML_STRIP_TAGS);
17. Добавлена опция перекодирования в utf-8 (константа YML_UTF8);
18. Поддержка специальных цен;
19. Кеширование производителей;
20. Добавлена опция генерации тега <vendor> (константа YML_VENDOR);
21. Добавлен тег <vendorCode>;
22. Добавлена замена кода валюты RUB на RUR для совместимости с Яндекс.Маркет;
23. Добавлена возможность генерации статического файла. Для этого надо задать имя файла в параметре $_GET['file']. В этом случае надо помнить о YML_REF_IP - при запуске по cron использование этого параметра теряет смысл;
24. Добавлена возможность задания параметра $_GET['ref']. Это удобно при генерации разных статических файлов для разных торговых площадок. Не забывайте об YML_REF_ID - в данном случае его использование не должно быть противоречивым и избыточным;
25. Добавлена опция генерации тега <vendorCode> (константа YML_VENDORCODE);
26. Добавлена опция использования тега CDATA (константа YML_USE_CDATA);
27. Убрал YML_REF_IP;
28. Использование yml_bid, yml_cbid, yml_bid, yml_cbid;
29. Добавлен параметр cats=all/master. По умолчанию - cats=master;
30. Параметр YML_UTF8 заменён на YML_CHARSET. Определяет выходную кодировку по умолчанию.
31. Добавлен параметр charset=. Задаёт выходную кодировку;

TODO:
1.


-- Константы в админе:
INSERT INTO configuration_group (configuration_group_id, configuration_group_title, configuration_group_description, sort_order, visible) VALUES (NULL, 'Яндекс-Маркет', 'Конфигурирование Яндекс-Маркет', '1', '1');
SET @configuration_group_id = last_insert_id();
UPDATE configuration_group SET sort_order = @configuration_group_id WHERE configuration_group_id = @configuration_group_id;

INSERT INTO configuration (configuration_id, configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added, use_function, set_function) VALUES (NULL, 'Название магазина', 'YML_NAME', '', 'Название магазина для Яндекс-Маркет. Если поле пустое, то используется STORE_NAME.', @configuration_group_id, 1, NOW(), NULL,  NULL),
(NULL, 'Название компании', 'YML_COMPANY', '', 'Название компании для Яндекс-Маркет. Если поле пустое, то используется STORE_OWNER.', @configuration_group_id, 2, NOW(), NULL,  NULL),
(NULL, 'Доставка включена', 'YML_DELIVERYINCLUDED', 'true', 'Доставка включена в стоимость товара?', @configuration_group_id, 3, NOW(), NULL, 'vam_cfg_select_option(array(\'true\', '\false\'),'),
(NULL, 'Товар в наличии', 'YML_AVAILABLE', 'stock', 'Товар в наличии или под заказ?', @configuration_group_id, 4, NOW(), NULL, 'vam_cfg_select_option(array(\'true\', \'false\', \'stock\'),'),
(NULL, 'Теги', 'YML_STRIP_TAGS', 'true', 'Убирать html-теги в строках?', @configuration_group_id, 6, NOW(), NULL, 'vam_cfg_select_option(array(\'false\', \'true\'),'),
(NULL, 'Тег CDATA', 'YML_USE_CDATA', 'true', 'Использовать тег CDATA для наименований и описаний товарови категорий', @configuration_group_id, 6, NOW(), NULL, 'vam_cfg_select_option(array(\'false\', \'true\'),'),
(NULL, 'Ссылка', 'YML_REF_ID', 'ref=yml', 'Добавить в адрес товара параметр', @configuration_group_id, 9, NOW(), NULL, NULL),
(NULL, 'Генерация <vendor>', 'YML_VENDOR', 'false', 'Генерировать тег <vendor>?', @configuration_group_id, 8, NOW(), NULL, 'vam_cfg_select_option(array(\'false\', \'true\'),'),
(NULL, 'Генерация <vendorCode>', 'YML_VENDORCODE', 'true', 'Генерировать тег <vendorCode>?', @configuration_group_id, 8, NOW(), NULL, 'vam_cfg_select_option(array(\'false\', \'true\'),'),
(NULL, 'Использовать cPath', YML_USE_CPATH', 'true', 'Использовать cPath в адресе товара?', @configuration_group_id, 8, NOW(), NULL, 'vam_cfg_select_option(array(\'false\', \'true\'),'),
(NULL, 'Сжатие', 'YML_GZIP', 'false', 'Использование сжатие GZIP', @configuration_group_id, 10, NOW(), NULL, 'vam_cfg_select_option(array(\'false\', \'true\'),'),
(NULL, 'Логин', 'YML_AUTH_USER', '', 'Логин для доступа к YML', @configuration_group_id, 11, NOW(), NULL, NULL),
(NULL, 'Пароль', 'YML_AUTH_PW', '', 'Пароль для доступа к YML', @configuration_group_id, 12, NOW(), NULL, NULL);

ALTER TABLE categories ADD yml_enable TINYINT(1) DEFAULT '1' NOT NULL;
ALTER TABLE products ADD yml_enable TINYINT(1) DEFAULT '1' NOT NULL;

ALTER TABLE categories ADD yml_bid INT(4) DEFAULT '0' NOT NULL;
ALTER TABLE categories ADD yml_cbid INT(4) DEFAULT '0' NOT NULL;

ALTER TABLE products ADD yml_bid INT(4) DEFAULT '0' NOT NULL;
ALTER TABLE products ADD yml_cbid INT(4) DEFAULT '0' NOT NULL;
*/
@define('GZIP_LEVEL','0');
require('includes/application_top.php');
require_once (DIR_FS_INC.'vam_get_products_mo_images.inc.php');

@define('YML_NAME', '');
@define('YML_COMPANY', '');
@define('YML_AVAILABLE', 'stock');
@define('YML_DELIVERYINCLUDED', 'false');
@define('YML_AUTH_USER', '');
@define('YML_AUTH_PW', '');
@define('YML_REF_ID', '');
@define('YML_STRIP_TAGS', 'true');
@define('YML_USE_CDATA', 'true');
@define('YML_UTF8', '');
@define('YML_VENDOR', 'false');
@define('YML_VENDORCODE', 'true');
@define('YML_USE_CPATH', 'false');
@define('YML_OUTPUT_BUFFER_MAXSIZE', 1024);
@define('YML_OUTPUT_DIRECTORY', 'temp/');
@define('YML_GZIP', 'false');

// BOF attributes_variants
define('TABLE_PRODUCTS_ATTRIBUTES_YML', 'products_attributes_yml');
define('TABLE_PRODUCTS_OPTIONS_YML', 'products_options_yml');
define('TABLE_SPECIFICATION_YML', 'specification_yml');
// EOF attributes_variants

// BOF specification
@define('YML_USE_SPECIFICATION', 'true'); // true false
// EOF specification

if (!get_cfg_var('safe_mode') && function_exists('set_time_limit')) {
  set_time_limit(0);
}

if (YML_AUTH_USER != "" && YML_AUTH_PW != "") {
  if (!isset($_SERVER["PHP_AUTH_USER"]) || $_SERVER["PHP_AUTH_USER"] != YML_AUTH_USER || $_SERVER["PHP_AUTH_PW"] != YML_AUTH_PW) {
    header('WWW-Authenticate: Basic realm="Realm-Name"');
    header("HTTP/1.0 401 Unauthorized");
    die;
  }
}

$charset = (YML_UTF8 == 'true') ? 'windows-1251' : $_SESSION['language_charset'];

$yml_referer = YML_REF_ID;
$referrer = (YML_REF_ID != '' ? '&' . YML_REF_ID : '');
$referrer .= (!empty($_GET['ref']) ? '&ref=' . $_GET['ref'] : '');

if($_SESSION["language_code"] != DEFAULT_LANGUAGE) $language_get = '&language=' . $_SESSION["language_code"];

$display_all_categories = (isset($_GET['cats']) && $_GET['cats'] == 'all');

if(!vam_yml_out()) {
  echo 'Ошибка при создании yml-файла'; // Убрать в константы
  die;
}

vam_yml_out('<?xml version="1.0" encoding="' . $charset .'"?' . '><!DOCTYPE yml_catalog SYSTEM "shops.dtd">');
vam_yml_out('<yml_catalog date="' . date('Y-m-d H:i') . '">');
vam_yml_out('<shop>');
vam_yml_out('<name>' . vam_yml_clear_string((YML_NAME == '' ? STORE_NAME : YML_NAME)) .'</name>');
vam_yml_out('<company>' . vam_yml_clear_string((YML_COMPANY == '' ? STORE_OWNER : YML_COMPANY)) . '</company>');
vam_yml_out('<url>' . HTTP_SERVER . DIR_WS_CATALOG . '</url>');

$current_currency = $_SESSION['currency'];
if($_SESSION['currency'] == 'RUB') $current_currency = 'RUR';
vam_yml_out('  <currencies>');
//foreach($vamPrice->currencies as $code => $v){
//  if($code == 'RUB') $code = 'RUR';
//  vam_yml_out('    <currency id="' . $code . '" rate="' . number_format(1/$v['value'],4) . '"/>');
//}
if ($_GET['currency'] == "") {
    foreach($vamPrice->currencies as $code => $v){
vam_yml_out('    <currency id="' . $code . '" rate="' . number_format(1/$v["value"],4) . '"/>');
    }
}  else {
    $varcurrency = $vamPrice->currencies[$_GET['currency']];
        foreach($vamPrice->currencies as $code => $v){
vam_yml_out('    <currency id="' . $code . '" rate="' . number_format($varcurrency['value']/$v['value'],4) . '"/>');
    }
    }
vam_yml_out('  </currencies>');

vam_yml_out('  <categories>');
if($yml_select === false) {
  $yml_select = vam_db_query('describe ' . TABLE_CATEGORIES . ' yml_enable');
  $yml_select = ($yml_select > 0) ? ", c.yml_enable, c.yml_bid, c.yml_cbid " : "";
}
$categories_bid = $categories_disable = array();
$categories_query = vam_db_query("SELECT c.categories_id, c.parent_id, cd.categories_name" . $yml_select . "
                            FROM " . TABLE_CATEGORIES . " c
                              LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON (c.categories_id = cd.categories_id)
                            WHERE cd.language_id='" . (int)$_SESSION['languages_id'] . "'
                              AND c.categories_status= '1' AND c.yml_enable = '1' 
                            ORDER BY c.categories_id");
while ($categories = vam_db_fetch_array($categories_query)) {
  if(vam_not_null($categories['categories_name'])) {
    if (!isset($categories["yml_enable"]) || $categories["yml_enable"] == 1) {
      $categories_bid[$categories['categories_id']] = (!isset($categories["yml_bid"])) ? 0 : $categories["yml_bid"];
      $categories_cbid[$categories['categories_id']] = (!isset($categories["yml_cbid"])) ? 0 : $categories["yml_cbid"];
      vam_yml_out('    <category id="' . $categories['categories_id'] . '"' . (($categories['parent_id'] == "0") ? '>' : ' parentId="' . $categories['parent_id'] . '">') . vam_yml_clear_string($categories['categories_name']) . '</category>');
    } else {
      $categories_disable[] = $categories_id;
    }
  }
}
vam_yml_out('  </categories>');

vam_yml_out('  <offers>');
$products_short_description = vam_db_query('describe ' . TABLE_PRODUCTS_DESCRIPTION . ' products_description');
$yml_select = vam_db_query('describe ' . TABLE_PRODUCTS . ' products_to_xml');
$products_sql = "SELECT distinct c.categories_id, cd.categories_name, c.categories_url, p.products_id, p2c.categories_id, p.products_model, p.products_quantity, p.products_image, p.products_ean, p.products_price, s.status, s.specials_new_products_price as price, p.products_tax_class_id, p.manufacturers_id, p.products_sort, GREATEST(p.products_date_added, IFNULL(p.products_last_modified, 0), IFNULL(p.products_date_available, 0)) AS base_date, pd.products_name, m.manufacturers_name, pd.products_description" .
                (($products_short_description > 0) ? ", pd.products_description " : " ") . "as proddesc " .
                (($yml_select > 0) ? ", p.yml_bid, p.yml_cbid " : "") .
                "FROM " . TABLE_PRODUCTS . " p
                    LEFT JOIN " . TABLE_PRODUCTS_DESCRIPTION . " pd ON (p.products_id = pd.products_id)
                    LEFT JOIN " . TABLE_MANUFACTURERS . " m ON (p.manufacturers_id = m.manufacturers_id)
                    LEFT JOIN " . TABLE_PRODUCTS_TO_CATEGORIES . " p2c ON (p.products_id = p2c.products_id)
                    LEFT JOIN " . TABLE_CATEGORIES . " c ON (c.categories_id = p2c.categories_id)
                    LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON (cd.categories_id = p2c.categories_id)
                    LEFT JOIN " . TABLE_SPECIALS . " s ON (p.products_id = s.products_id)
                 WHERE p.products_status = 1" .
                   (($yml_select > 0) ? " and p.products_to_xml = 1" : "") .
                 " AND pd.language_id = " . (int)$_SESSION['languages_id'] . "
                 AND cd.language_id = " . (int)$_SESSION['languages_id'] . "
                 group by p.products_id 
                 ORDER BY p.products_id ASC";
$products_query = vam_db_query($products_sql);
while ($products = vam_db_fetch_array($products_query)) {

  $available = "false";
  switch(YML_AVAILABLE) {
    case "stock":
      if($products['products_quantity'] > 0)
        $available = "true";
      else
        $available = "false";
      break;
    case "false":
    case "true":
      $available = YML_AVAILABLE;
      break;
  }
  $cbid = $bid = '';
  $products["yml_bid"] = max((!isset($products["yml_bid"]) ? 0 : $products["yml_bid"]), $categories_bid[$products["categories_id"]]);
  if($products["yml_bid"] > 0) $bid = ' bid="' . $products["yml_bid"] . '"';
  $products["yml_cbid"] = max((!isset($products["yml_cbid"]) ? 0 : $products["yml_cbid"]), $categories_cbid[$products["categories_id"]]);
  if($products["yml_cbid"] > 0) $cbid = ' cbid="' . $products["yml_cbid"] . '"';
  $price = $products['products_price'];
  $price = $vamPrice->GetPrice($products['products_id'], $format = false, 1, $products['products_tax_class_id'], $price);

  $old_price = $products['price'];
  $old_price = $vamPrice->GetPrice($products['products_id'], $format = false, 1, $products['products_tax_class_id'], $old_price);

  $url = vam_href_link(FILENAME_PRODUCT_INFO, vam_product_link($products['products_id'], $products['products_name']) . (isset($_GET['ref']) ? '&amp;ref=' . $_GET['ref'] : null) . $yml_referer, 'NONSSL', false);
  
// BOF extra_fields
  $sql = "SELECT pef.products_extra_fields_status as status, pef.products_extra_fields_name as name, ptf.products_extra_fields_value as value
          FROM " . TABLE_PRODUCTS_EXTRA_FIELDS . " pef
            LEFT JOIN  ". TABLE_PRODUCTS_TO_PRODUCTS_EXTRA_FIELDS ." ptf ON ptf.products_extra_fields_id=pef.products_extra_fields_id
          WHERE ptf.products_id=" . (int)$products['products_id'] . "
            AND ptf.products_extra_fields_value<>''
            AND (pef.languages_id='0' OR pef.languages_id='" . (int)$_SESSION['languages_id'] . "')
          ORDER BY products_extra_fields_order";
  $extra_fields_query = vamDBquery($sql);
  while ($extra_fields = vam_db_fetch_array($extra_fields_query)) {
//    var_export($extra_fields);echo "\n";
    if (!$extra_fields['status'])
      continue;
  }
// EOF extra_fields

// BOF specification
  $specification_values_array = array();
  if (YML_USE_SPECIFICATION == 'true') {
    $sql = "SELECT sg.specification_group_id
            FROM " . TABLE_SPECIFICATION_GROUPS . " sg,
                 " . TABLE_SPECIFICATIONS_TO_CATEGORIES . " sg2c
            WHERE sg.show_products = 'True'
              AND sg.specification_group_id = sg2c.specification_group_id
              AND sg2c.categories_id = '" . (int)$products['categories_id'] . "'
            ORDER BY sg.specification_group_name";
	$specification_group_query = vamDBquery($sql);
    $check_query = vam_db_query("SHOW TABLES LIKE '" . TABLE_SPECIFICATION_YML . "'");
    $select = '';
    $from = '';
    $where = '';
    if (vam_db_num_rows($check_query) > 0) {
      $select = ", syml.yml_active";
      $from = " LEFT JOIN " . TABLE_SPECIFICATION_YML . " syml ON (syml.specifications_id = s.specifications_id)";
      $where = "";
    }
    while ($specification_group = vam_db_fetch_array($specification_group_query)) {
      $sql = "SELECT ps.specification, sd.specification_name, sd.specification_prefix, sd.specification_suffix" . $select . "
              FROM " . TABLE_PRODUCTS_SPECIFICATIONS . " ps,
                   " . TABLE_SPECIFICATION . " s" . $from . ",
                   " . TABLE_SPECIFICATION_DESCRIPTION . " sd,
                   " . TABLE_SPECIFICATION_GROUPS . " sg,
                   " . TABLE_SPECIFICATIONS_TO_CATEGORIES . " sg2c
              WHERE sg.show_products = 'True'
                AND s.show_products = 'True'
                AND s.specification_group_id = sg.specification_group_id
                AND sg.specification_group_id = sg2c.specification_group_id
                AND sg.specification_group_id = '" . (int)$specification_group['specification_group_id'] . "'
                AND sd.specifications_id = s.specifications_id
                AND ps.specifications_id = sd.specifications_id
                AND sg2c.categories_id = '" . (int)$products['categories_id'] . "'
                AND ps.products_id = '" . (int)$products['products_id'] . "'
                AND sd.language_id = '" . (int)$_SESSION['languages_id'] . "'
                AND ps.language_id = sd.language_id
              ORDER BY s.specification_sort_order, sd.specification_name";
      $specifications_query = vamDBquery($sql);
      while ($specifications = vam_db_fetch_array ($specifications_query, true) ) {
//        var_export($specifications);echo "\n";
        if ($specifications['yml_active'] == '0') continue;
        if ($specifications['specification'] != '') {
          $specification_value = (!empty($specifications['specification_prefix']) ? $specifications['specification_prefix'] . ' ' : '') . $specifications['specification'] . (!empty($specifications['specification_suffix']) ? ' ' . $specifications['specification_suffix'] : '');
          $specification_values_array[trim($specifications['specification_name'])] = trim($specification_value);
        }
      }
    }
  }
// EOF specification

// BOF attributes_variants
  $options_values_array = array();
  $check_query = vam_db_query("SHOW TABLES LIKE '" . TABLE_PRODUCTS_OPTIONS_YML . "'");
  $select = '';
  $from = '';
  $where = '';
/*  if (vam_db_num_rows($check_query) > 0) {
    $select = ", poyml.yml_parm, poyml.yml_unit, poyml.yml_active";
    $from = " LEFT JOIN " . TABLE_PRODUCTS_OPTIONS_YML . " poyml ON (poyml.options_id = po.products_options_id AND poyml.language_id = po.language_id)";
    $where = "";
  }*/
  $productsid = $products['products_id'];
  //echo $productsid;
  $sql = "SELECT DISTINCT po.*, pa.*" . $select . "
          FROM " . TABLE_PRODUCTS_OPTIONS . " po" . $from . ",
               " . TABLE_PRODUCTS_ATTRIBUTES . " pa
          WHERE pa.products_id = " . (int)$productsid . "
            AND pa.options_id = po.products_options_id
            AND po.language_id = " . (int)$_SESSION['languages_id'] . "
          ORDER BY po.products_options_name";
  $options_query = vamDBquery($sql);
  $check_query = vam_db_query("SHOW TABLES LIKE '" . TABLE_PRODUCTS_ATTRIBUTES_YML . "'");
  $select = '';
  $from = '';
  $where = '';
  if (vam_db_num_rows($check_query) > 0) {
    $select = ", payml.yml_parm_value";
    $from = " LEFT JOIN " . TABLE_PRODUCTS_ATTRIBUTES_YML . " payml ON (payml.products_attributes_id = pa.products_attributes_id)";
    $where = "";
  }
  while ($options = vam_db_fetch_array($options_query)) {
  	
    //if ($options['products_options_name'] != 'Размер') $options = null;
    //if ($options['products_options_name'] != 'Цвет') $options = null;
      	
//    var_export($options);echo "\n";
//echo 'tet2';
//$attributes_stock = "and pa.attributes_stock>0";
//if (isset($_GET['ozon'])){
	$attributes_stock = " ";
//}
//echo $products['products_id'];
    //if ($options['yml_active'] == '0') continue;
    $sql = "SELECT pov.*, pa.*" . $select . "
            FROM " . TABLE_PRODUCTS_ATTRIBUTES . " pa" . $from . ",
                 " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov
            WHERE pa.products_id = " . (int)$productsid . "
              AND pa.options_id = " . (int)$options['products_options_id'] . "
              AND pa.options_values_id = pov.products_options_values_id
              AND pov.language_id = " . (int)$_SESSION['languages_id'] . "
			  ".$attributes_stock."
            ORDER BY pa.sortorder";
					  					   
    $options_values_query = vamDBquery($sql);

//echo 'tet'.var_dump($options);   

$yml_parm = null;
$yml_value = null; 
    
    while ($options_values = vam_db_fetch_array($options_values_query)) {
    //  var_export($options_values);echo "\n";
    //echo 'test'.$options['products_options_name'];
      $yml_parm = (!empty($options['yml_parm']) ? $options['yml_parm'] : $options['products_options_name']);
      $yml_parm = (!empty($options_values['yml_parm']) ? $options_values['yml_parm'] : $yml_parm);
      $yml_unit = (!empty($options['yml_unit']) ? $options['yml_unit'] : '');
      $yml_unit = (!empty($options_values['yml_unit']) ? $options_values['yml_unit'] : $yml_unit);
      $yml_value = (!empty($options_values['yml_parm_value']) ? $options_values['yml_parm_value'] : $options_values['products_options_values_name']);
      //echo 'tet'.var_dump($yml_parm).var_dump($yml_value);
         //echo 'test3'.$yml_parm;
      //echo var_dump($options);
      $options_values_array[$options_values['products_options_values_id']]['available'] = $available;
//      error_log('prod=' . var_export($products['products_quantity'], true) . " " . var_export($available, true) . "\n", 3, __FILE__.'.log');
      if (YML_AVAILABLE == 'stock' && $options_values['attributes_stock'] <= 0) {
        $options_values_array[$options_values['products_options_values_id']]['available'] = 'false';
      }
//      error_log('attr=' . var_export($options_values['attributes_stock'], true) . " " . var_export($options_values_array[$options_values['products_options_values_id']]['available'], true) . "\n", 3, __FILE__.'.log');
      $options_values_array[$options_values['products_options_values_id']]['options_id'] = $options_values['products_attributes_id'];
      $options_values_array[$options_values['products_options_values_id']]['id'] = $products['products_id'] . 'v' . $options_values['products_attributes_id'];
      $options_values_array[$options_values['products_options_values_id']]['url'] = vam_yml_add2url($url, 'variant_id=' . $options_values['products_attributes_id']);
//      $options_values_array[$options_values['products_options_values_id']]['id'] = $products['products_id'] . '-' . $options_values['products_options_values_id'];
//      $options_values_array[$options_values['products_options_values_id']]['url'] = vam_yml_add2url($url, 'variant_id=' . $options_values['products_options_values_id']);
      $options_values_array[$options_values['products_options_values_id']]['price'] = $price;
      $options_values_array[$options_values['products_options_values_id']]['oldprice'] = $products['products_price'];
      if ($options_values['options_values_price'] != 0) {
        if ($options_values['price_prefix'] == '+') {
          $options_values_array[$options_values['products_options_values_id']]['price'] += $options_values['options_values_price'];
          $options_values_array[$options_values['products_options_values_id']]['oldprice'] += $options_values['options_values_price'];
        } else {
          $options_values_array[$options_values['products_options_values_id']]['price'] -= $options_values['options_values_price'];
          $options_values_array[$options_values['products_options_values_id']]['oldprice'] -= $options_values['options_values_price'];
        }
      }
      if ($price == $products['products_price']) {
        unset($options_values_array[$options_values['products_options_values_id']]['oldprice']);
      }
      $options_values_array[$options_values['products_options_values_id']]['name'] = $products['products_name'] . ' (' . $options_values['products_options_values_name'] . ')';
      $options_values_array[$options_values['products_options_values_id']]['products_options_name'] = $options['products_options_name'];
      $options_values_array[$options_values['products_options_values_id']]['products_options_values_name'] = $options_values['products_options_values_name'];
      $options_values_array[$options_values['products_options_values_id']]['yml_parm'] = $yml_parm;
      $options_values_array[$options_values['products_options_values_id']]['yml_unit'] = $yml_unit;
      $options_values_array[$options_values['products_options_values_id']]['yml_value'] = $yml_value;
	  $options_values_array[$options_values['products_options_values_id']]['attributes_model'] = $options_values['attributes_model'];
	  
	  $options_values_array[$options_values['products_options_values_id']]['attributes_stock'] = $options_values['attributes_stock'];
	 // 	print_r( $options_values_array[$options_values['products_options_values_id']]);//	print_r( $yml_unit);print_r( $yml_value);
    }
  }//	print_r( $options_values_array);
// EOF attributes_variants  
  
// BOF attributes_variants
// https://yandex.ru/support/partnermarket/guides/clothes.html#group-id
  if (count($options_values_array) == 0) {
//    error_log('$products=' . var_export($products, 3) . "\n", 3, __FILE__.'.log');
    $options = array();
    $options['id'] = $products['products_id'];
    $options['url'] = $url;
    $options['price'] = $price;
    $options['oldprice'] = $products['products_price']; 
    if ($price == $products['products_price']) {
      unset($options['oldprice']);
    }
    $options['available'] = $available;
    $options['name'] = $products['products_name'];
    $options_values_array = array($options);
  }

  $available = ' available="' . $available . '"';

  foreach ($options_values_array as $options_values_values_id => $options) {
//      var_export($options);echo "\n";

    //if ($options['products_options_name'] != 'Размер') $options = null;
    //if ($options['products_options_name'] != 'Цвет') $options = null;

  //vam_yml_out('<offer id="' . $products['products_id'].$options['options_id'] . '"' . $available . $bid . $cbid . ' group_id="' . $products['products_id'].  '">');
  vam_yml_out('<offer id="' . $products['products_id'].$options['options_id'] . '"' . $available . $bid . $cbid . '>');
  //vam_yml_out('<offer id="' . $products['products_id'].$options['options_id'] . '"' . $available . $bid . $cbid . '>');
  vam_yml_out('  <url>' . $url . '</url>');
  vam_yml_out('  <collectionId>' . (vam_not_null($products['categories_name']) ? str_replace('.html','',$products['categories_url']) :  $products['categories_id']) . '</collectionId>');
  vam_yml_out('  <delivery>true</delivery>');
  vam_yml_out('  <price>' . ($old_price > 0 && $products['status'] == 1  ? $old_price : $price) . '</price>');
  if ($products['price'] > 0 && $products['status'] == 1) {
  	vam_yml_out('  <oldprice>' . $vamPrice->Format($products['products_price'], false) . '</oldprice>');
  	//vam_yml_out('  <enable_auto_discounts>yes</enable_auto_discounts>');
  }
  //vam_yml_out('  <group_id>' . $products['products_id'] . '</group_id>');
  vam_yml_out('  <currencyId>' . $current_currency . '</currencyId>');

  vam_yml_out('  <categoryId>' . $products['categories_id'] . '</categoryId>');
  if($display_all_categories) {
    $p2c_query = vam_db_query("SELECT categories_id
                         FROM " . TABLE_PRODUCTS_TO_CATEGORIES . "
                         WHERE products_id=" . (int)$products['products_id'] . "
                           AND categories_id<>" . (int)$products['categories_id'] . "");
    while($p2c = vam_db_fetch_array($p2c_query)) {
      vam_yml_out('  <categoryId>' . $p2c['categories_id'] . '</categoryId>');
    }
  }

  if(vam_not_null($products['products_image'])) vam_yml_out('  <picture>' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_THUMBNAIL_IMAGES . urldecode($products['products_image']) . '</picture>');

		$mo_images = vam_get_products_mo_images($products['products_id']);
        if ($mo_images != false) {
            foreach ($mo_images as $img) {
                vam_yml_out('  <picture>' . HTTP_SERVER . DIR_WS_CATALOG . DIR_WS_POPUP_IMAGES . urldecode($img['image_name']) . '</picture>');
            }
        }

  if(YML_DELIVERYINCLUDED == "true") vam_yml_out('  <deliveryIncluded/>');
  vam_yml_out('  <name>' . vam_yml_clear_string($products['products_name']) . '</name>');

  if(YML_VENDOR == "true" && $products['manufacturers_id'] != 0) {
    vam_yml_out('  <vendor>' . vam_yml_clear_string($products['manufacturers_name']) . '</vendor>');
  }

  if(YML_VENDORCODE == "true" && vam_not_null($products['products_model'])) {
    vam_yml_out('  <vendorCode>' . $products['products_model'] . '</vendorCode>');
  }

    vam_yml_out('  <model>' . vam_yml_clear_string($products['products_name']) . '</model>');

$cat_query = vamDBquery("SELECT
                                 categories_name
                                 FROM ".TABLE_CATEGORIES_DESCRIPTION." 
                                 WHERE categories_id='".$products['categories_id']."'
                                 and language_id = '".(int) $_SESSION['languages_id']."'"
                                 );
$cat_data = vam_db_fetch_array($cat_query, true);
		

  //vam_yml_out('  <typePrefix>' . vam_yml_clear_string($cat_data['categories_name']) . '</typePrefix>');

  vam_yml_out('  <description>' . vam_yml_clear_string($products['proddesc']) . '</description>');
  if(YML_SALES_NOTES != "") {
    vam_yml_out('  <sales_notes>' . vam_yml_clear_string(YML_SALES_NOTES) . '</sales_notes>');
  }


		$also_purchased = array();
		if ($_SESSION['customers_status']['customers_fsk18_display'] == '0') {
			$fsk_lock = ' and p.products_fsk18!=1';
		}
		$group_check = "";
		if (GROUP_CHECK == 'true') {
			$group_check = " and p.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
		}

		$orders_query = "SELECT
                    px.products_id,
                    px.xsell_id,
					p.products_model
                FROM products_xsell px, 
					products p
                WHERE
                px.xsell_id = p.products_id and 
                px.products_id = ".$products['products_id']."";
																								                                            
		$orders_query = vamDBquery($orders_query);
		while ($orders = vam_db_fetch_array($orders_query, true)) {

			$also_purchased[] = $orders['xsell_id'];

		}

  if ($products['products_ean'] != '') vam_yml_out('<barcode>' . $products['products_ean'] . '</barcode>');
  //vam_yml_out('<rec>'.implode(",",array_unique($also_purchased)).'</rec>' . "\n");
    if (!empty($options['yml_parm'])) {
      vam_yml_out('  <param name="' . trim($options['yml_parm']) . '"' . (!empty($options['yml_unit']) ? ' unit="' . $options['yml_unit'] . '"' : '' ) . '>' . vam_yml_clear_parm($options['yml_value']) . '</param>');
    }
// BOF specification
    foreach ($specification_values_array as $specification_name => $specification_value) {
      vam_yml_out('  <param name="'. $specification_name . '">' . vam_yml_clear_parm($specification_value) . '</param>');
    }
// EOF specification  
    
    
//echo var_dump($options_values_array);    
    
  vam_yml_out('</offer>' . "\n");

}
}
vam_yml_out('</offers>');


vam_yml_out('  <collections>');
if($yml_select1 === false) {
  $yml_select1 = vam_db_query('describe ' . TABLE_CATEGORIES . ' yml_enable');
  $yml_select1 = ($yml_select1 > 0) ? ", c.yml_enable, c.yml_bid, c.yml_cbid " : "";
}
$categories_bid1 = $categories_disable1 = array();
$categories_query1 = vam_db_query("SELECT c.categories_id, c.categories_image, c.parent_id, c.categories_url, cd.*" . $yml_select1 . "
                            FROM " . TABLE_CATEGORIES . " c
                              LEFT JOIN " . TABLE_CATEGORIES_DESCRIPTION . " cd ON (c.categories_id = cd.categories_id)
                            WHERE cd.language_id='" . (int)$_SESSION['languages_id'] . "'
                              AND c.categories_status= '1' AND c.yml_enable = '1' 
                            ORDER BY c.categories_id");
while ($categories1 = vam_db_fetch_array($categories_query1)) {
  if(vam_not_null($categories1['categories_name'])) {
    if (!isset($categories1["yml_enable"]) || $categories1["yml_enable"] == 1) {
      $categories_bid[$categories['categories_id']] = (!isset($categories1["yml_bid"])) ? 0 : $categories1["yml_bid"];
      $categories_cbid[$categories['categories_id']] = (!isset($categories1["yml_cbid"])) ? 0 : $categories1["yml_cbid"];
      vam_yml_out('<collection id="' . (vam_not_null($categories1['categories_name']) ? str_replace('.html','',$categories1['categories_url']) :  $categories1['categories_id']) . '">'); 
      vam_yml_out('<url>' . HTTPS_SERVER . '/' . vam_yml_clear_string($categories1['categories_url']) . '</url>'); 
      if(vam_not_null($categories1['categories_image'])) vam_yml_out('<picture>' . HTTPS_SERVER . '/images/categories/' . vam_yml_clear_string($categories1['categories_image']) . '</picture>'); 
      vam_yml_out('<name>' . vam_yml_clear_string($categories1['categories_name']) . '</name>'); 
      if(vam_not_null($categories1['categories_description'])) vam_yml_out('<description>' . vam_yml_clear_string($categories1['categories_description']) . '</description>');  
      vam_yml_out('</collection>');
    } else {
      $categories_disable1[] = $categories_id1;
    }
  }
}
vam_yml_out('  </collections>');

vam_yml_out('</shop>');
vam_yml_out('</yml_catalog>');

vam_yml_out();

  function vam_yml_out($output='') {
    static $fp = false;
    static $output_buffer = "";
    $retval = true;
    if($output == '') {
      if(!$fp) {
        if(isset($_GET['file'])) {
          if(YML_GZIP == 'true') {
            $retval = $fp = gzopen(DIR_FS_CATALOG . YML_OUTPUT_DIRECTORY . $_GET['file'] . '.gz', "wb");
          } else {
            $retval = $fp = fopen(DIR_FS_CATALOG . YML_OUTPUT_DIRECTORY . $_GET['file'], "wb");
          }
        } else {
          if(YML_GZIP == 'true') {
            if (($ini_zlib_output_compression = (int)ini_get('zlib.output_compression')) < 1) {
              ob_start('ob_gzhandler');
            } else {
              @ini_set('zlib.output_compression_level', GZIP_LEVEL);
            }
          }
          header('Content-Type: text/xml');
          $fp = true;
        }
      } else {
        if(strlen($output_buffer) > 0) {
          if(isset($_GET['file'])) {
            if(YML_GZIP == 'true') {
              $retval = gzwrite($fp, $output_buffer, strlen($output_buffer));
            } else {
              $retval = fwrite($fp, $output_buffer, strlen($output_buffer));
            }
          } else {
            echo $output_buffer;
          }
          $output_buffer = "";
        }
        if(isset($_GET['file'])) {
          fclose($fp);
          $fp = false;
        }
      }
    } else {
      if(strlen($output_buffer) > YML_OUTPUT_BUFFER_MAXSIZE) {
        if(isset($_GET['file'])) {
          if(YML_GZIP == 'true') {
            $retval = gzwrite($fp, $output_buffer, strlen($output_buffer));
          } else {
            $retval = fwrite($fp, $output_buffer, strlen($output_buffer));
          }
        } else {
          echo $output_buffer;
        }
        $output_buffer = "";
      }
      $output_buffer .= $output . "\n";
    }
    return $retval;
  }
// BOF YML_TURBO_MAX_OFFERS
function vam_yml_ob_start() {
  global $output_buffer_flag, $output_buffer;
  $output_buffer = '';
  $output_buffer_flag = true;
}

function vam_yml_ob_get() {
  global $output_buffer_flag, $output_buffer;
  $output_buffer_flag = false;
  return $output_buffer;
}
// EOF YML_TURBO_MAX_OFFERS

function vam_yml_clear_parm($str) {
  global $charset;
  $str = htmlspecialchars($str, ENT_QUOTES, $charset);
  $str = trim($str);
  return $str;
}

function vam_yml_clear_string($str) {
  global $charset;
//    $str = htmlspecialchars_decode($str, ENT_QUOTES);
  if (YML_STRIP_TAGS == 'true') {
    $str = strip_tags($str);
  }
  if (function_exists('iconv')) {
  if ($charset != $_SESSION['language_charset']) {
    $str = iconv($_SESSION['language_charset'], $charset, $str);
  }
  }
  if (YML_USE_CDATA == 'true') {
    $str = '<![CDATA[' . $str . ']]>';
  } else {
    $str = htmlspecialchars($str, ENT_QUOTES);
  }

  return $str;
}

function vam_yml_add2url($url, $parm) {
  if (empty($parm)) return $url;
  if (strpos($url, '?') === false) {
    $url .= '?' . $parm;
  } else {
    $url .= '&' . $parm;
  }
  return $url;
}

function vam_yml_clear_url($url) {
  $url = str_replace('&amp;', '&', $url);
  $url = str_replace('&&', '&', $url);
  $url = str_replace('&', '&amp;', $url);
  return $url;
}

function vam_yml_clear_html($str) {
  $strip_tags = array(
    // Remove invisible content
    '@<head[^>]*?>.*?</head>@siU',
    '@<style[^>]*?>.*?</style>@siU',
    '@<script[^>]*?.*?</script>@siU',
    '@<object[^>]*?.*?</object>@siU',
    '@<embed[^>]*?.*?</embed>@siU',
    '@<applet[^>]*?.*?</applet>@siU',
    '@<noframes[^>]*?.*?</noframes>@siU',
    '@<noscript[^>]*?.*?</noscript>@siU',
    '@<noembed[^>]*?.*?</noembed>@siU',
    '@<iframe[^>]*?.*?</iframe>@siU',

    '/<([\?\%]) .*? \\1>/sx',     #встроенный PHP, Perl, ASP код
    '/<\!\[CDATA\[ .*? \]\]>/sx', #блоки CDATA
    '/<\!--.*?-->/s', #комментарии
    #MS Word таги типа "<![if! vml]>...<![endif]>",
    #условное выполнение кода для IE типа "<!--[if expression]> HTML <![endif]-->"
    #условное выполнение кода для IE типа "<![if expression]> HTML <![endif]>"
    #см. http://www.tigir.com/comments.htm
    '/<\! (?:--)?
          \[
          (?> [^\]"\']  | "[^"]*" | \'[^\']*\' )*
          \]
          (?:--)?
     >/sx',

    );
  $str = preg_replace($strip_tags, '', $str);
  $str = str_replace('&nbsp;', ' ', $str);
  $str = htmlspecialchars_decode($str, ENT_QUOTES);
  if (YML_STRIP_TAGS == 'true') {
    $str = strip_tags($str);
//    $str = zen_clean_html($str);
    $str = preg_replace('@\s\s+@', ' ', $str);
    $str = html_entity_decode($str, ENT_QUOTES, CHARSET);
  }

  if (YML_USE_CDATA == 'true') {
    if (preg_match('@(&|<|>)@', $str)) {
      $str = '<![CDATA[' . $str . ']]>';
    }
  } else {
    $str = htmlspecialchars($str, ENT_QUOTES);
  }
  return $str;
}