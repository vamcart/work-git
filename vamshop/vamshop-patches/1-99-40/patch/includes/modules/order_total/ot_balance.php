<?php
/* -----------------------------------------------------------------------------------------
   $Id: ot_balance.php 1002 2007-02-06 21:01:37 VaM $

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ot_balance.php,v 1.14 2003/02/14); www.oscommerce.com  
   (c) 2003	 nextcommerce (ot_balance.php,v 1.11 2003/08/24); www.nextcommerce.org
   (c) 2004	 xt:Commerce (ot_balance.php,v 1.11 2003/08/24); xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
//@ini_set('display_errors', TRUE);
//error_reporting(version_compare(PHP_VERSION, 5.3, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE : version_compare(PHP_VERSION, 6.0, '>=') ? E_ALL & ~E_DEPRECATED & ~E_NOTICE & ~E_STRICT : E_ALL & ~E_NOTICE);
 
  class ot_balance {
    var $title, $output;

    function __construct() {
    	global $vamPrice;
      $this->code = 'ot_balance';
      $this->title = defined('MODULE_ORDER_TOTAL_BALANCE_TITLE') ? MODULE_ORDER_TOTAL_BALANCE_TITLE : false;
      $this->description = defined('MODULE_ORDER_TOTAL_BALANCE_DESCRIPTION') ? MODULE_ORDER_TOTAL_BALANCE_DESCRIPTION : false;
      $this->enabled = ((defined('MODULE_ORDER_TOTAL_BALANCE_STATUS') && MODULE_ORDER_TOTAL_BALANCE_STATUS == 'true') ? true : false);
      $this->sort_order = defined('MODULE_ORDER_TOTAL_BALANCE_SORT_ORDER') ? MODULE_ORDER_TOTAL_BALANCE_SORT_ORDER : false;
      $this->credit_class = true;
	  $this->output = array ();
    }

    function process() {
      global $order, $vamPrice;
	
	  	$order_total = $this->get_order_total();
		$od_amount = $this->calculate_balance($order_total);
		$tod_amount = 0.0; //Fred
		$this->deduction = $od_amount;
	  //echo ' hf'.$od_amount.'kj '.$order_total;
		if ($od_amount > 0) {
			$order->info['total'] = $order->info['total'] - $od_amount;
			$order->info['deduction'] = $od_amount;
			$this->output[] = array ('title' => MODULE_ORDER_TOTAL_BALANCE_DESCRIPTION.':', 
			'text' => '<b class="cupon_sale"><font color="ff0000"><span class="big_minus">-</span>'.$vamPrice->Format($od_amount, true).'</font></b>', 
			'value' => $od_amount); //Fred added hyphen
			
			$_SESSION['balance_minused'] = $od_amount;
		}
		
    }
	
	function pre_confirmation_check($order_total) {

		return $this->calculate_balance($order_total);
	}
	
	function use_credit_amount() {
		return $output_string;
	}
	
	function collect_posts() {
		return false;
	}
	
	function apply_credit() {
		global $insert_id, $REMOTE_ADDR;

		unset ($_SESSION['pay_balance']);
	}
	function credit_selection() {
		
			$selection_string = '';
			$selection_string .= '<tr>' . "\n";
			$selection_string .= ' <td  width="10">' . vam_draw_separator('pixel_trans.gif', '10', '1') .'</td>';
			$selection_string .= ' <td  nowrap class="main">' . "\n";
			$selection_string .=  TEXT_ENTER_COUPON_CODE . '</td>';
			$selection_string .= ' <td  align="right">'. vam_draw_input_field('gv_redeem_code').'</td>';
			$selection_string .= ' <td  width="10">' . vam_draw_separator('pixel_trans.gif', '10', '1') . '</td>';
			$selection_string .= '</tr>' . "\n";
		
		return false;
	}
	function calculate_balance($order_total) {
		global $order, $vamPrice;
		
		$order_total_40_persent = ($order_total/100)*KOVALSKY_CONST_MAX_PAY_PERSENT;
		//$order_total_40_persent = $order_total;
		$od_amount = 0;
		if (isset ($_SESSION['pay_balance']) && $_SESSION['pay_balance'] == 'true') {
			//echo 'dfg';
					$customers_balance_query_select = "select
					c.customers_balance
					from ".TABLE_CUSTOMERS." c
					where c.customers_id = '".$_SESSION['customer_id']."' ";
					$customers_balance_query = vam_db_query($customers_balance_query_select);
					$customers_balance = vam_db_fetch_array($customers_balance_query);
					
		if ($order_total_40_persent <= $customers_balance['customers_balance']){
			$od_amount = $order_total_40_persent;
		}
		
		if ($order_total_40_persent > $customers_balance['customers_balance']){
			$od_amount = $customers_balance['customers_balance'];
		}
		//echo  $order->info['total'].' '.$customers_balance['customers_balance'].'wer';
		}
		
		return $od_amount;
	}

	function update_credit_account($i) {
		return false;
	}
	
	function get_order_total() {
		global $order, $vamPrice;

		$order_total = $order->info['total'] - $order->info['shipping_cost'];
		return $order_total;
	}
    function check() {
      if (!isset($this->_check)) {
        $check_query = vam_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ORDER_TOTAL_BALANCE_STATUS'");
        $this->_check = vam_db_num_rows($check_query);
      }

      return $this->_check;
    }

    function keys() {
      return array('MODULE_ORDER_TOTAL_BALANCE_STATUS', 'MODULE_ORDER_TOTAL_BALANCE_SORT_ORDER');
    }

    function install() {
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_ORDER_TOTAL_BALANCE_STATUS', 'true', '6', '1','vam_cfg_select_option(array(\'true\', \'false\'), ', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, date_added) values ('MODULE_ORDER_TOTAL_BALANCE_SORT_ORDER', '90', '6', '2', now())");
    }

    function remove() {
      vam_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }
  }
?>