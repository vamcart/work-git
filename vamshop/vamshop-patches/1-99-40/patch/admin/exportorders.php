<?php
/* --------------------------------------------------------------
   $Id: exportorders.php 899 2011-02-07 17:36:57 VaM $

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2011 VaM Shop
   --------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(define_language.php,v 1.6 2002/01/17); www.oscommerce.com 
   (c) 2003	 nextcommerce (define_language.php,v 1.4 2003/08/14); www.nextcommerce.org
   (c) 2004	 xt:Commerce (define_language.php,v 1.4 2003/08/14); xt-commerce.com

   Released under the GNU General Public License 
   --------------------------------------------------------------*/

require('includes/application_top.php'); 

  // check start and end Date
  $startDate = "";
  $startDateG = 0;
  if ( ($_GET['startD']) && (vam_not_null($_GET['startD'])) )
{    $sDay = $_GET['startD'];
    $startDateG = 1;
  } else {
    $sDay = 1;
  }
  if ( ($_GET['startM']) && (vam_not_null($_GET['startM'])) )
{    $sMon = $_GET['startM'];
    $startDateG = 1;
  } else {
    $sMon = 1;
  }
  if ( ($_GET['startY']) && (vam_not_null($_GET['startY'])) )
{    $sYear = $_GET['startY'];
    $startDateG = 1;
  } else {
    $sYear = date("Y");
  }
  if ($startDateG) {
    $startDate = mktime(0, 0, 0, $sMon, $sDay, $sYear);
  } else {
    $startDate = mktime(0, 0, 0, date("m"), 1, date("Y"));
  }
    
  $endDate = "";
  $endDateG = 0;
  if ( ($_GET['endD']) && (vam_not_null($_GET['endD'])) ) {
    $eDay = $_GET['endD'];
    $endDateG = 1;
  } else {
    $eDay = 1;
  }
  if ( ($_GET['endM']) && (vam_not_null($_GET['endM'])) ) {
    $eMon = $_GET['endM'];
    $endDateG = 1;
  } else {
    $eMon = 1;
  }
  if ( ($_GET['endY']) && (vam_not_null($_GET['endY'])) ) {
    $eYear = $_GET['endY'];
    $endDateG = 1;
  } else {
    $eYear = date("Y");
  }
  if ($endDateG) {
    $endDate = mktime(0, 0, 0, $eMon, $eDay + 1, $eYear);
  } else {
    $endDate = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
  }   

// Check if the form is submitted
if (!$_GET['submitted'])
{
?>
<!-- header_eof //-->
<!DOCTYPE html>
<html <?php echo HTML_PARAMS; ?>>
<head>
<!--<meta name="viewport" content="initial-scale=1.0, width=device-width" />-->
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>"> 
<title><?php echo TITLE; ?></title>
<!-- Header JS, CSS -->
<?php require(DIR_FS_ADMIN.DIR_WS_INCLUDES . 'header_include.php'); ?>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
<?php if (ADMIN_DROP_DOWN_NAVIGATION == 'false') { ?>
    <td width="<?php echo BOX_WIDTH; ?>" align="left" valign="top">
<!-- left_navigation //-->
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
<!-- left_navigation_eof //-->
    </td>
<?php } ?>
<!-- body_text //-->
    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr>
          <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"></td>
              </tr>
            </table></td>
        </tr>
        <!-- first ends // -->
        <tr>
          <td><table border="0" style="" width="100%" cellspacing="2" cellpadding="2">
              <tr>
                <td><form method="GET" action="<?php echo $PHP_SELF; ?>">
                    <table border="0" style="" cellpadding="3">

                      <tr>
                        <td>Дата от</td>
                  <td>
                    <select name="startD" size="1">
<?php
      if ($startDate) {
        $j = date("j", $startDate);
      } else {
        $j = 1;
      }
      for ($i = 1; $i < 32; $i++) {
?>
                        <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
      }
?>
                    </select>
                    <select name="startM" size="1">
<?php
      if ($startDate) {
        $m = date("n", $startDate);
      } else {
        $m = 1;
      }
      for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
      }
?>
                    </select>
                    <select name="startY" size="1">
<?php
      if ($startDate) {
        $y = date("Y") - date("Y", $startDate);
      } else {
        $y = 0;
      }
      for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo date("Y") - $i; ?></option>
<?php
    }
?>
                    </select>
                  </td>
                      </tr>
                     
                      <tr>
                        <td>Дата до</td>
                  <td>
                    <select name="endD" size="1">
<?php
    if ($endDate) {
      $j = date("j", $endDate - 60* 60 * 24);
    } else {
      $j = date("j");
    }
    for ($i = 1; $i < 32; $i++) {
?>
                      <option<?php if ($j == $i) echo " selected"; ?>><?php echo $i; ?></option>
<?php
    }
?>
                    </select>
                    <select name="endM" size="1">
<?php
    if ($endDate) {
      $m = date("n", $endDate - 60* 60 * 24);
    } else {
      $m = date("n");
    }
    for ($i = 1; $i < 13; $i++) {
?>
                      <option<?php if ($m == $i) echo " selected"; ?> value="<?php echo $i; ?>"><?php echo strftime("%B", mktime(0, 0, 0, $i, 1)); ?></option>
<?php
    }
?>
                    </select>
                    <select name="endY" size="1">
<?php
    if ($endDate) {
      $y = date("Y") - date("Y", $endDate - 60* 60 * 24);
    } else {
      $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
?>
                      <option<?php if ($y == $i) echo " selected"; ?>><?php echo
date("Y") - $i; ?></option><?php
    }
?>
                    </select>
                  </td>
                      </tr>

<?php

$customers_array = array (array ('id' => '0', 'text' => 'Все покупатели'));
$customers_query = vam_db_query("select * from ".TABLE_CUSTOMERS." order by customers_firstname ASC");
while ($customers = vam_db_fetch_array($customers_query)) {
        $customers_array[] = array ('id' => $customers['customers_id'], 'text' => $customers['customers_firstname'] . ' ' . $customers['customers_lastname'] . ' (' . $customers['customers_email_address'] . ')');
}

?>

                      <tr>
                        <td>Клиент:</td>
                        <td><!-- <input name="end" size="5" value="<?php echo $end; ?>"> -->
                          <?php 
//						echo '&nbsp;&nbsp;' . vam_draw_pull_down_menu('customer', $customers_array, (isset($_GET['customer']) ? $_GET['customer'] : '')) . '&nbsp;&nbsp;&nbsp;';
						echo '&nbsp;&nbsp;' . vam_draw_pull_down_menu('customer', $customers_array) . '&nbsp;&nbsp;&nbsp;';
						?></td>
                      </tr>

<?php

$tax_class_array = array (array ('id' => '0', 'text' => 'Все поставщики'));
$tax_class_query = vam_db_query("select tax_class_id, tax_class_title from ".TABLE_TAX_CLASS." order by tax_class_title");
while ($tax_class = vam_db_fetch_array($tax_class_query)) {
        $tax_class_array[] = array ('id' => $tax_class['tax_class_id'], 'text' => $tax_class['tax_class_title']);
}

?>
                      
                      <tr>
                        <td>Поставщик</td>
                        <td><!-- <input name="end" size="5" value="<?php echo $end; ?>"> -->
                          <?php 
//						echo '&nbsp;&nbsp;' . vam_draw_pull_down_menu('products_tax_class_id', $tax_class_array, (isset($_GET['products_tax_class_id']) ? $_GET['products_tax_class_id'] : '')) . '&nbsp;&nbsp;&nbsp;';
						echo '&nbsp;&nbsp;' . vam_draw_pull_down_menu('products_tax_class_id', $tax_class_array) . '&nbsp;&nbsp;&nbsp;';
						?></td>
                      </tr>                      

                      <tr>
                        <td><?php echo 'Статус заказа:'; ?></td>
                        <td>
                        
                        
                <?php 
                
                
$orders_statuses = array ();
$orders_status_array = array ();
$orders_status_query = vam_db_query("select orders_status_id, orders_status_name from ".TABLE_ORDERS_STATUS." where language_id = '".(int)$_SESSION['languages_id']."'");
while ($orders_status = vam_db_fetch_array($orders_status_query)) {
	$orders_statuses[] = array ('id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']);
	$orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
}                
                
                define('TEXT_ALL_ORDERS', 'Все заказы');
//                echo vam_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses), $_GET['status']).vam_draw_hidden_field(vam_session_name(), vam_session_id());                         
                echo vam_draw_pull_down_menu('status', array_merge(array(array('id' => '', 'text' => TEXT_ALL_ORDERS)), $orders_statuses)).vam_draw_hidden_field(vam_session_name(), vam_session_id()); ?>                        
                        
                        
                        </td></td>
                      </tr>



                      <tr>
                        <td>&nbsp;</td>
                        <td><span class="button"><button type="submit" value="<?php echo INPUT_VALID; ?>"><?php echo vam_image(DIR_WS_IMAGES . 'icons/buttons/submit.png', '', '12', '12'); ?>&nbsp;<?php echo INPUT_VALID; ?></button></span></td>
                      </tr>
                    </table>
                    <input type="hidden" name="submitted" value="1">
                  </form></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
<?php
}
// submitted so generate csv if the form is submitted
else
{
generatecsv($startDate, $endDate, $_GET['customer'], $_GET['products_tax_class_id']);
}

// generates csv file from $start order to $end order, inclusive
function generatecsv($start, $end)
{
    
    //echo $start;
    //echo $end;
    //exit;  

   $tax_class_id = '';
   $customer = ''; 
    
//Placing columns names in first row
$delim =  ';' ;
$csv_output .= "Время_заказа".$delim;
$csv_output .= "№_заказа".$delim;
$csv_output .= "ID клиента".$delim;
$csv_output .= "Имя_клиента".$delim;
//$csv_output .= "Поставщик".$delim;
// $csv_output .= "Фамилия".$delim;
// $csv_output .= "Name_On_Card".$delim;
// $csv_output .= "Компания".$delim;
$csv_output .= "Email".$delim;
//$csv_output .= "Адрес_клиента".$delim;
// $csv_output .= "Billing_Address_2".$delim;
//$csv_output .= "Город_клиента".$delim;
//$csv_output .= "Регион_клиента".$delim;
//$csv_output .= "Индекс_клиента".$delim;
// $csv_output .= "Страна_клиента".$delim;
//$csv_output .= "Телефон_клиента".$delim;
// $csv_output .= "ShipTo_First_Name".$delim;
// $csv_output .= "ShipTo_Last_Name".$delim;
//$csv_output .= "Имя_получателя".$delim;
// $csv_output .= "Компания_получателя".$delim;
//$csv_output .= "Адрес_получателя".$delim;
//$csv_output .= "Город_получателя".$delim;
// $csv_output .= "ShipTo_Address_2".$delim;
//$csv_output .= "Регион_получателя".$delim;
//$csv_output .= "Индекс_получателя".$delim;
//$csv_output .= "Страна_получателя".$delim;
//$csv_output .= "Тел._получателя".$delim;
//$csv_output .= "Card_Type".$delim;
//$csv_output .= "Card_Number".$delim;
//$csv_output .= "Exp_Date".$delim;
//$csv_output .= "Bank_Name".$delim;
//$csv_output .= "Gateway".$delim;
//$csv_output .= "AVS_Code".$delim;
// $csv_output .= "Transaction_ID".$delim;
// $csv_output .= "Order_Special_Notes".$delim;
//$csv_output .= "Комм._к_заказу".$delim;
$csv_output .= "Стоимость_товара".$delim;
// $csv_output .= "Order_Tax".$delim;
// $csv_output .= "Order_Insurance".$delim;
// $csv_output .= "Tax_Exempt_Message".$delim;
$csv_output .= "Стоимость_доставки".$delim;
//$csv_output .= "Small_Order_Fee".$delim;
//$csv_output .= "Discount_Rate".$delim;
//$csv_output .= "Discount_Message".$delim;
//$csv_output .= "CODAmount".$delim;
$csv_output .= "Статус заказа".$delim;
$csv_output .= "Заказ_итого".$delim;
$csv_output .= "Товаров_в_заказе".$delim;
//$csv_output .= "Способ_доставки".$delim;
// $csv_output .= "Shipping_Weight".$delim;
//$csv_output .= "Coupon_Code".$delim;
//$csv_output .= "Order_security_msg.".$delim;
//$csv_output .= "Order_Surcharge_Amount".$delim;
//$csv_output .= "Order_Surcharge_Something".$delim;
//$csv_output .= "Affiliate_code".$delim;
//$csv_output .= "Sentiment_message".$delim;
//$csv_output .= "Checkout_form_type".$delim;
//$csv_output .= "Card_CVV_value".$delim;
//$csv_output .= "future1".$delim;
//$csv_output .= "future2".$delim;
//$csv_output .= "future3".$delim;
//$csv_output .= "future4".$delim;
//$csv_output .= "future5".$delim;
//$csv_output .= "future6".$delim;
//$csv_output .= "future7".$delim;
//$csv_output .= "future8".$delim;
//$csv_output .= "future9".$delim;
//$csv_output .= "Remarks".$delim;
$csv_output .= "Товары заказа".$delim;
$csv_output .= "Артикулы".$delim;
$csv_output .= "Цена_розница".$delim;
//$csv_output .= "Цена_закупка".$delim;
$csv_output .= "Сумма розница".$delim;
//$csv_output .= "Сумма закупка".$delim;
$csv_output .= "Количество".$delim;
$csv_output .= "Наименование_товара".$delim;
$csv_output .= "Aтрибуты".$delim;
$csv_output .= "Значения_атрибутов".$delim;
$csv_output .= "\n";


if ($_GET['status'] > 0) {
$orders_status = "o.orders_status = '".vam_db_input($_GET['status'])."' ";
} else {
$orders_status = "o.orders_status > 0 ";
}

if ($start != '' && $end != '') {
$date_sort = "and o.date_purchased >= '" . vam_db_input(date("Y-m-d\TH:i:s", $start)) . "' AND o.date_purchased < '" . vam_db_input(date("Y-m-d\TH:i:s", $end)) . "' ";
} else {
$date_sort = '';
}

//echo $date_sort;
//exit;

if ($_GET['customer'] > 0) {
$customer = "and o.customers_id = '".vam_db_input($_GET['customer'])."' ";
} else {
$customer = '';
}

if ($_GET['products_tax_class_id'] > 0) {
$tax_class_id = "and op.tax_class_id = '".vam_db_input($_GET['products_tax_class_id'])."' ";
} else {
$tax_class_id = "";
}

//End Placing columns in first row
// Patch dlan
// if both fields are empty we select all orders
//if ($start!="" && $end!="") {
    
//echo "SELECT distinct *  
//FROM orders as o left join orders_products as op on op.orders_id = o.orders_id where ".(($orders_status != "") ? " " . $orders_status : '').(($customer != "") ? " " . $customer : '').(($tax_class_id != "") ? " " . $tax_class_id : '')." GROUP BY o.orders_id ORDER BY o.orders_id";
//exit;    
    
 $orders = vam_db_query("SELECT distinct o.*, op.*   
FROM orders as o left join orders_products as op on op.orders_id = o.orders_id where ".(($orders_status != "") ? " " . $orders_status : '').(($customer != "") ? " " . $customer : '').(($date_sort != "") ? " " . $date_sort : '').(($tax_class_id != "") ? " " . $tax_class_id : '')." GROUP BY o.orders_id ORDER BY o.orders_id"); 
// if $start is empty we select all orders up to $end

//echo "SELECT distinct o.*, op.*   
//FROM orders as o left join orders_products as op on op.orders_id = o.orders_id where ".(($orders_status != "") ? " " . $orders_status : '').(($customer != "") ? " " . $customer : '').(($date_sort != "") ? " " . $date_sort : '').(($tax_class_id != "") ? " " . $tax_class_id : '')." GROUP BY o.orders_id ORDER BY o.orders_id";
//exit;

//}

//$csv_output ="\n";
while ($row_orders = vam_db_fetch_array($orders)) { //start one loop
 
//echo var_dump($row_orders);
//exit; 
//echo $row_orders["orders_id"];
//exit;
 
$csv_output_ordersbefore = $csv_output;

$Orders_id = $row_orders["orders_id"];
$Date1 = $row_orders["date_purchased"];
//list($Date, $Time) = explode (' ',$Date1);
$Date = date('m/d/Y', strtotime($Date1));
$Time= date('H:i:s', strtotime($Date1));
$Name_On_Card1 = $row_orders["customers_name"]; 
$Name_On_Card = filter_text($Name_On_Card1);// order changed
list($First_Name,$Last_Name) = explode(', ',$Name_On_Card1); // order changed
$IDCustomer = filter_text($row_orders["customers_id"]);
$Supplier = filter_text($row_orders["tax_class_id"]);
$Company = filter_text($row_orders["customers_company"]);
$email = filter_text($row_orders["customers_email_address"]);
$Billing_Address_1 = filter_text($row_orders["billing_street_address"]);
$Billing_Address_2 = "";
$Billing_City = filter_text($row_orders["billing_city"]);
$Billing_State = filter_text($row_orders["billing_state"]);
$Billing_Zip = filter_text($row_orders["billing_postcode"]);
$Billing_Country = str_replace("(48 Contiguous Sta", "", $row_orders["billing_country"]);
$Billing_Phone = filter_text($row_orders["customers_telephone"]);
$ShipTo_Name1 = $row_orders["delivery_name"];
$ShipTo_Name = filter_text($ShipTo_Name1); // order changed
list($ShipTo_First_Name,$ShipTo_Last_Name) = explode(', ',$ShipTo_Name1); // order changed
$ShipTo_Company = filter_text($row_orders["delivery_company"]);
$ShipTo_Address_1 = filter_text($row_orders["delivery_street_address"]);
$ShipTo_Address_2 = "";
$ShipTo_City = filter_text($row_orders["delivery_city"]);
$ShipTo_State = filter_text($row_orders["delivery_state"]);
$ShipTo_Zip = filter_text($row_orders["delivery_postcode"]);
$ShipTo_Country = str_replace("(48 Contiguous Sta", "", $row_orders["delivery_country"]);
$ShipTo_Phone = "";
$Card_Type = $row_orders["cc_type"];
$Card_Number = $row_orders["cc_number"];
$Exp_Date = $row_orders["cc_expires"];
$Bank_Name = "";
$Gateway  = "";
$AVS_Code = "";
$Transaction_ID = "";
$Order_Special_Notes = "";
// --------------------    QUERIES 1  ------------------------------------//
//Orders_status_history for comments
 $orders_status_history = vam_db_query("select comments from orders_status_history
 where orders_id = '" . $Orders_id . "'");
 //$row_orders_status_history = vam_db_fetch_array($comments);
 while($row_orders_status_history = mysqli_fetch_array($orders_status_history)) {
 // end //

$Comments = filter_text($row_orders_status_history["comments"]);

}

//echo $row_orders["orders_status"] . ':'.$_SESSION['language_id'];
//Orders_status_history for comments
 $orders_status_name_query = vam_db_query("select orders_status_name from orders_status where orders_status_id = ".$row_orders["orders_status"]." and language_id = '".$_SESSION['languages_id']."'");
 //$row_orders_status_history = vam_db_fetch_array($comments);
 $orders_status_name = vam_db_fetch_array($orders_status_name_query);
$OrdersStatusName = filter_text($orders_status_name["orders_status_name"]);

// --------------------    QUERIES 2  ------------------------------------//
//Orders_subtotal
$orders_subtotal = vam_db_query("select value from orders_total
where class = 'ot_subtotal' and orders_id = '" . $Orders_id . "'");
//$row_orders_subtotal = vam_db_fetch_array($orders_subtotal);
while($row_orders_subtotal = mysqli_fetch_array($orders_subtotal)) {
 // end //
$Order_Subtotal = ceil(filter_text($row_orders_subtotal["value"]));
}
// --------------------    QUERIES 3  ------------------------------------//
//Orders_tax
$orders_tax = vam_db_query("select value from orders_total
where class = 'ot_tax' and orders_id = '" . $Orders_id . "'");
//$row_orders_tax = vam_db_fetch_array($orders_tax);
while($row_orders_tax = mysqli_fetch_array($orders_tax)) {
 // end //
$Order_Tax = filter_text($row_orders_tax["value"]);
}
// --------------------    QUERIES 4  ------------------------------------//
//Orders_Insurance
$orders_insurance = vam_db_query("select value from orders_total
where class = 'ot_insurance' and orders_id = '" . $Orders_id . "'");
//$row_orders_insurance = vam_db_fetch_array($orders_insurance);
while($row_orders_insurance = mysqli_fetch_array($orders_insurance)) {
 // end //
$Order_Insurance = ceil(filter_text($row_orders_insurance["value"]));
}
$Tax_Exempt_Message = "";
// --------------------    QUERIES 5  ------------------------------------//
//Orders_Shipping
$orders_shipping = vam_db_query("select title, value from orders_total
where class = 'ot_shipping' and orders_id = '" . $Orders_id . "'");
//$row_orders_shipping = vam_db_fetch_array($orders_shipping);
while($row_orders_shipping = mysqli_fetch_array($orders_shipping)) {
 // end //
$Order_Shipping_Total = ceil($row_orders_shipping["value"]);
$Shipping_Method = filter_text($row_orders_shipping["title"]); // Shipping method from query 5
}
// --------------------    QUERIES 6  ------------------------------------//
//Orders_Residential Del Fee (Giftwrap)
$orders_residential_fee = vam_db_query("select value from orders_total
where class = 'ot_giftwrap' and orders_id = '" . $Orders_id . "'");
//$row_orders_residential_fee = vam_db_fetch_array($orders_residential_fee);
while($row_orders_residential_fee = mysqli_fetch_array($orders_residential_fee)) {
 // end //
$Small_Order_Fee = ceil($row_orders_residential_fee["value"]);
}
////////////////////////////////////
$Discount_Rate = "";
$Discount_Message  = "";
$CODAmount  = "";
// --------------------    QUERIES 7  ------------------------------------//
//Orders_Total
$orders_total = vam_db_query("select value from orders_total
where class = 'ot_total' and orders_id = '" . $Orders_id . "'");
//$row_orders_total = vam_db_fetch_array($orders_total);
while($row_orders_total = mysqli_fetch_array($orders_total)) {
 // end //
$Order_Grand_Total = ceil($row_orders_total["value"]);
}
// --------------------    QUERIES 8  ------------------------------------//
//Products COunt
$orders_count = vam_db_query("select count(products_quantity) as o_count from orders_products
where orders_id = '" . $Orders_id . "'");
//$row_orders_total = vam_db_fetch_array($orders_total);
while($row_orders_count = mysqli_fetch_array($orders_count)) {
 // end //
$Number_of_Items = $row_orders_count[0]; // used array to show the number of items ordered
}
//
$Shipping_Weight = "";
$Coupon_Code = "";
$Order_security_msg = "";
$Order_Surcharge_Amount = "";
$Order_Surcharge_Something = "";
$Affiliate_code = "";
$Sentiment_message = "";
$Checkout_form_type = "";
$Card_CVV_value = $row_orders["cvvnumber"];
$future1  = "";
$future2 = "";
$future3 = "";
$future4 = "";
$future5 = "";
$future6 = "";
$future7 = "";
$future8 = "";
$future9 = "";
// csv settings
$CSV_SEPARATOR = ";";
$CSV_NEWLINE = "\r\n";
$csv_output .= $Date . $CSV_SEPARATOR;
$csv_output .= $Orders_id . $CSV_SEPARATOR;
// $csv_output .= $Time . $CSV_SEPARATOR;
$csv_output .= $IDCustomer . $CSV_SEPARATOR;
$csv_output .= $First_Name . $CSV_SEPARATOR;
//$csv_output .= $Supplier . $CSV_SEPARATOR;
// $csv_output .= $Last_Name . $CSV_SEPARATOR;
// $csv_output .= $Name_On_Card . $CSV_SEPARATOR;
// $csv_output .= $Company . $CSV_SEPARATOR;
$csv_output .= $email . $CSV_SEPARATOR;
//$csv_output .= $phone . $CSV_SEPARATOR;
//$csv_output .= $Billing_Address_1 . $CSV_SEPARATOR;
// $csv_output .= $Billing_Address_2 . $CSV_SEPARATOR;
//$csv_output .= $Billing_City . $CSV_SEPARATOR;
//$csv_output .= $Billing_State . $CSV_SEPARATOR;
//$csv_output .= $Billing_Zip . $CSV_SEPARATOR;
// $csv_output .= $Billing_Country . $CSV_SEPARATOR;
//$csv_output .= $Billing_Phone . $CSV_SEPARATOR;
// $csv_output .= $ShipTo_First_Name . $CSV_SEPARATOR;
// $csv_output .= $ShipTo_Last_Name . $CSV_SEPARATOR;
//$csv_output .= $ShipTo_Name . $CSV_SEPARATOR;
// $csv_output .= $ShipTo_Company . $CSV_SEPARATOR;
//$csv_output .= $ShipTo_Address_1 . $CSV_SEPARATOR;
// $csv_output .= $ShipTo_Address_2 . $CSV_SEPARATOR;
//$csv_output .= $ShipTo_City . $CSV_SEPARATOR;
//$csv_output .= $ShipTo_State . $CSV_SEPARATOR;
//$csv_output .= $ShipTo_Zip . $CSV_SEPARATOR;
//$csv_output .= $ShipTo_Country . $CSV_SEPARATOR;
//$csv_output .= $ShipTo_Phone . $CSV_SEPARATOR;
//$csv_output .= $Card_Type . $CSV_SEPARATOR;
//$csv_output .= $Card_Number . $CSV_SEPARATOR;
//$csv_output .= $Exp_Date . $CSV_SEPARATOR;
//$csv_output .= $Bank_Name . $CSV_SEPARATOR;
//$csv_output .= $Gateway . $CSV_SEPARATOR;
//$csv_output .= $AVS_Code . $CSV_SEPARATOR;
// $csv_output .= $Transaction_ID . $CSV_SEPARATOR;
// $csv_output .= $Order_Special_Notes . $CSV_SEPARATOR;
//$csv_output .= $Comments . $CSV_SEPARATOR;
$csv_output .= $Order_Subtotal . $CSV_SEPARATOR;
// $csv_output .= $Order_Tax . $CSV_SEPARATOR;
// $csv_output .= $Order_Insurance . $CSV_SEPARATOR;
// $csv_output .= $Tax_Exempt_Message . $CSV_SEPARATOR;
$csv_output .= $Order_Shipping_Total . $CSV_SEPARATOR;
$csv_output .= $OrdersStatusName . $CSV_SEPARATOR;
//$csv_output .= $Small_Order_Fee . $CSV_SEPARATOR;
//$csv_output .= $Discount_Rate . $CSV_SEPARATOR;
//$csv_output .= $Discount_Message . $CSV_SEPARATOR;
//$csv_output .= $CODAmount . $CSV_SEPARATOR;
$csv_output .= $Order_Grand_Total . $CSV_SEPARATOR;
$csv_output .= $Number_of_Items . $CSV_SEPARATOR;
//$csv_output .= $Shipping_Method . $CSV_SEPARATOR;
// $csv_output .= $Shipping_Weight . $CSV_SEPARATOR;
//$csv_output .= $Coupon_Code . $CSV_SEPARATOR;
//$csv_output .= $Order_security_msg . $CSV_SEPARATOR;
//$csv_output .= $Order_Surcharge_Amount . $CSV_SEPARATOR;
//$csv_output .= $Order_Surcharge_Something . $CSV_SEPARATOR;
//$csv_output .= $Affiliate_code . $CSV_SEPARATOR;
//$csv_output .= $Sentiment_message . $CSV_SEPARATOR;
//$csv_output .= $Checkout_form_type . $CSV_SEPARATOR;
//$csv_output .= $Card_CVV_value . $CSV_SEPARATOR;
//$csv_output .= $future1 . $CSV_SEPARATOR;
//$csv_output .= $future2 . $CSV_SEPARATOR;
//$csv_output .= $future3 . $CSV_SEPARATOR;
//$csv_output .= $future4 . $CSV_SEPARATOR;
//$csv_output .= $future5 . $CSV_SEPARATOR;
//$csv_output .= $future6 . $CSV_SEPARATOR;
//$csv_output .= $future7 . $CSV_SEPARATOR;
//$csv_output .= $future8 . $CSV_SEPARATOR;
//$csv_output .= $future9 ;
// --------------------    QUERIES 9  ------------------------------------//
//Get list of products ordered
$orders_products = vam_db_query("select * from orders_products as op
where op.orders_id = '" . $Orders_id . "'" . (($tax_class_id != "") ? " '" . $tax_class_id . "'" : '')."");

// While loop to list the item


$countproducts = 0;
$csv_output_item = "";

$csv_output_order = str_replace($csv_output_ordersbefore, "", $csv_output);

while($row_orders_products = mysqli_fetch_array($orders_products)) {
	// loop through orders
	// More than one product per order, new line
	
	if ($countproducts>0){
		$csv_output .= "\n";
		
		$csv_output .= $csv_output_order; 
		 
		$csv_output_item = "";
	}
	
//	$csv_output_item .= $CSV_SEPARATOR . "BEGIN_ITEM". $CSV_SEPARATOR ;
	$csv_output_item .= $CSV_SEPARATOR;
	$csv_output_item .= filter_text($row_orders_products['products_model']) . $CSV_SEPARATOR;
	$csv_output_item .= ceil($row_orders_products['products_price']) . $CSV_SEPARATOR;
	//$csv_output_item .= ceil($row_orders_products['products_purchase_price']) . $CSV_SEPARATOR;
	$csv_output_item .= ceil($row_orders_products['products_price']*$row_orders_products['products_quantity']) . $CSV_SEPARATOR;
	//$csv_output_item .= ceil($row_orders_products['products_purchase_price']*$row_orders_products['products_quantity']) . $CSV_SEPARATOR;
	$csv_output_item .= $row_orders_products['products_quantity'] . $CSV_SEPARATOR;
	$csv_output_item .= filter_text($row_orders_products['products_name']) . $CSV_SEPARATOR;
	$Products_id = $row_orders_products['orders_products_id'];

	$orders_products_attributes = vam_db_query("select * from orders_products_attributes 
	where orders_id = '" . $Orders_id . "' and orders_products_id  = " . $Products_id);
	
	while($row_orders_products_attributes = mysqli_fetch_array($orders_products_attributes)) {
		$csv_output_item .= filter_text($row_orders_products_attributes['products_options']) . $CSV_SEPARATOR;
		$csv_output_item .= filter_text($row_orders_products_attributes['products_options_values']) . $CSV_SEPARATOR;
	} 

//	$csv_output_item .= "END_ITEM";
	
	$csv_output .= $csv_output_item;
	
	$countproducts += 1;

} // end while loop for products

// --------------------------------------------------------------------------//
$csv_output .= "\n";
} // while loop main first

//print
header("Content-Type: application/force-download\n");
header("Cache-Control: cache, must-revalidate");   
header("Pragma: public");
header("Content-Disposition: attachment; filename=ordersexports_" . date("Y-m-d\TH:i:s", $start).'_'.date("Y-m-d\TH:i:s", $end) . ".csv");
 print $csv_output;
  exit;
}//function main

function filter_text($text) {
$filter_array = array(",","\r","\n","\t");
return str_replace($filter_array,"",$text);
} // function for the filter
?>