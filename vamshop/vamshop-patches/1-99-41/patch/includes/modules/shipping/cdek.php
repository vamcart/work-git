<?php
/* -----------------------------------------------------------------------------------------
   $Id: flat.php 899 2007-02-06 21:19:57 VaM $   

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(flat.php,v 1.40 2003/02/05); www.oscommerce.com 
   (c) 2003	 nextcommerce (flat.php,v 1.7 2003/08/24); www.nextcommerce.org
   (c) 2004	 xt:Commerce (flat.php,v 1.7 2003/08/24); xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/


  class cdek {
    var $code, $title, $description, $icon, $enabled;


    function __construct() {
      global $order;

      $this->code = 'cdek';
      $this->title = defined('MODULE_SHIPPING_CDEK_TEXT_TITLE') ? MODULE_SHIPPING_CDEK_TEXT_TITLE : null;
      $this->description = defined('MODULE_SHIPPING_CDEK_TEXT_DESCRIPTION') ? MODULE_SHIPPING_CDEK_TEXT_DESCRIPTION : null;
      $this->icon = DIR_WS_ICONS . 'cdek.png';
      $this->tax_class = defined('MODULE_SHIPPING_CDEK_TAX_CLASS') ? MODULE_SHIPPING_CDEK_TAX_CLASS : null;
      $this->sort_order = defined('MODULE_SHIPPING_CDEK_SORT_ORDER') ? MODULE_SHIPPING_CDEK_SORT_ORDER : null;
      $this->enabled = ((defined('MODULE_SHIPPING_CDEK_STATUS') && MODULE_SHIPPING_CDEK_STATUS == 'True') ? true : false);

      if ( ($this->enabled == true) && ((int)MODULE_SHIPPING_CDEK_ZONE > 0) ) {
        $check_flag = false;
        $check_query = vam_db_query("select zone_id from " . TABLE_ZONES_TO_GEO_ZONES . " where geo_zone_id = '" . MODULE_SHIPPING_CDEK_ZONE . "' and zone_country_id = '" . $order->delivery['country']['id'] . "' order by zone_id");
        while ($check = vam_db_fetch_array($check_query)) {
          if ($check['zone_id'] < 1) {
            $check_flag = true;
            break;
          } elseif ($check['zone_id'] == $order->delivery['zone_id']) {
            $check_flag = true;
            break;
          }
        }

        if ($check_flag == false) {
          $this->enabled = false;
        }
      }
    }


    function quote($method = '') {
      global $order, $shipping_weight, $total_count, $length, $width, $height, $volume;

		$api_key = MODULE_SHIPPING_CDEK_API_KEY;
		$api_password = MODULE_SHIPPING_CDEK_API_PASSWORD;
		$sender_city = MODULE_SHIPPING_CDEK_SENDER_CITY;
		$total_weight = $shipping_weight;
		$shipping_cost = 0;
		$error_block = false;

		// боевой адрес апи
		$url = 'https://api.cdek.ru/v2/oauth/token?client_id='.$api_key.'&client_secret='.$api_password.'&grant_type=client_credentials';
		
		$request = array (
		
		'grant_type' => 'client_credentials',
		'client_id' => $api_key,
		'client_secret' => $api_password
		
		);
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($request));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$data = curl_exec($curl);
		 
		curl_close($curl);
		 
		if($data === false)
		{
		return 'error';
		}
		 
		$js = json_decode($data, $assoc=true);
		 
		//echo var_dump($js);
		//exit;
		
		$token = $js["access_token"];
		
		//Получаем информацию из АПИ СДЭКА по внутреннму id номеру города отправителя
		$senderCityUrl = 'https://api.cdek.ru/v2/location/cities?city='.$sender_city;
		
		
		$headerSenderCity = array(
		                'http'=>array(
		                    'method'=>"GET",
		                    'header'=>"Content-Type: application/json\r\n" .
		                    "Authorization: Bearer ".$token."\r\n"
		                )
		            );
		
		$responseSenderCity = file_get_contents($senderCityUrl, false, stream_context_create($headerSenderCity));
		
		$senderCity = json_decode($responseSenderCity, $assoc=true);
		$senderCityId = $senderCity[0]["code"];

		if($responseSenderCity === false) {
				if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= "ID номер для города получателя посылки не найден.";
		}
		
		//echo $senderCityId;
		//exit;

		$dev_city = str_replace('г. ','',$order->delivery['city']);
		$dev_city = str_replace('г.','',$dev_city);
		$dev_city = str_replace('г ','',$dev_city);
	    
		//Получаем информацию из АПИ СДЭКА по внутреннму id номеру города получателя
		$receiverCityUrl = 'https://api.cdek.ru/v2/location/cities?city='.$dev_city;
		
		//echo $dev_city;
		
		
		$headerReceiverCity = array(
		                'http'=>array(
		                    'method'=>"GET",
		                    'header'=>"Content-Type: application/json\r\n" .
		                    "Authorization: Bearer ".$token."\r\n"
		                )
		            );
		
		$responseReceiverCity = file_get_contents($receiverCityUrl, false, stream_context_create($headerReceiverCity));
		
		$receiverCity = json_decode($responseReceiverCity, $assoc=true);
		$receiverCityId = $receiverCity[0]["code"];

		if($responseReceiverCity === false) {
				if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= "ID номер для города получателя посылки не найден.";
		}
	    
	    
	   //запрос расчета стоимости отправления 
	   //$ret = $this->sdekpvz_api_calc($total_weight, $receiverCityId, $senderCityId, $this->order_volume, $token, rawurlencode($order->delivery['city']));
		//echo var_dump($ret);
		//exit;	
			    
		//echo $receiverCityId;
		//exit;

		//подключаем файл с классом CalculatePriceDeliveryCdek
		include_once(DIR_FS_CATALOG.'includes/external/cdek/'.'CalculatePriceDeliveryCdek.php');
		
		try {
		
			//создаём экземпляр объекта CalculatePriceDeliveryCdek
			$calc = $this->sdekpvz_api_calc($total_weight, $receiverCityId, $senderCityId, $this->order_volume, $token, rawurlencode($order->delivery['city']));
			
			if ($calc["delivery_sum"] > 0) {
				$res = $calc;
				
				if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= 'Цена доставки: ' . $res["delivery_sum"] . 'руб.<br />';
				if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= 'Срок доставки: ' . $res['result']['calendar_min'] . '-' . 
										 $res['calendar_max'] . ' дн.<br />';
				if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= 'Планируемая дата доставки: c ' . $res["delivery_date_range"]["min"] . ' по ' . $res["delivery_date_range"]["max"] . '.<br />';
			} else {
				$err = $calc->getError();
				if( isset($err['error']) && !empty($err) ) {
					if (MODULE_SHIPPING_CDEK_DEBUG == 'test') var_dump($err);
					foreach($err['error'] as $e) {
						if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= 'Код ошибки: ' . $e['code'] . '.<br />';
						if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= 'Текст ошибки: ' . $e['text'] . '.<br />';
					}
				}
			}
		    
		    //раскомментируйте, чтобы просмотреть исходный ответ сервера
		     //var_dump($calc->getResult());
		     //var_dump($calc->getError());
		
		} catch (Exception $e) {
		    if (MODULE_SHIPPING_CDEK_DEBUG == 'test') $error_block .= 'Ошибка: ' . $e->getMessage() . " | ";
		}
			
		$shipping_cost = $calc["delivery_sum"];
		
		if ($shipping_cost > 0) {
			$shipping_cost = $shipping_cost;
		} else {
			$shipping_cost = MODULE_SHIPPING_CDEK_COST;
		}

        $error_block = !empty($error_block) ? ' <span style=\'color: red;\'>| ' . $error_block . '</span>' : '';

      $this->quotes = array('id' => $this->code,
                            'module' => MODULE_SHIPPING_CDEK_TEXT_TITLE,
                            'methods' => array(array('id' => $this->code,
                                                     'title' => MODULE_SHIPPING_CDEK_TEXT_PUBLIC_TITLE . $error_block,
                                                     'cost' => $shipping_cost)));

      if ($this->tax_class > 0) {
        $this->quotes['tax'] = vam_get_tax_rate($this->tax_class, $order->delivery['country']['id'], $order->delivery['zone_id']);
      }

      if (vam_not_null($this->icon)) $this->quotes['icon'] = vam_image($this->icon, $this->title);

      return $this->quotes;
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = vam_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_SHIPPING_CDEK_STATUS'");
        $this->_check = vam_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function install() {
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_CDEK_STATUS', 'True', '6', '0', 'vam_cfg_select_option(array(\'True\', \'False\'), ', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_CDEK_ALLOWED', '', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_CDEK_COST', '0', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_CDEK_API_KEY', '', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_CDEK_API_PASSWORD', '', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_CDEK_SENDER_CITY', 'Москва', '6', '0', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_SHIPPING_CDEK_TAX_CLASS', '0', '6', '0', 'vam_get_tax_class_title', 'vam_cfg_pull_down_tax_classes(', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, use_function, set_function, date_added) values ('MODULE_SHIPPING_CDEK_ZONE', '0', '6', '0', 'vam_get_zone_class_title', 'vam_cfg_pull_down_zone_classes(', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value, configuration_group_id, sort_order, set_function, date_added) values ('MODULE_SHIPPING_CDEK_DEBUG', 'production', '6', '6', 'vam_cfg_select_option(array(\'test\', \'production\'), ', now())");
      vam_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_key, configuration_value,  configuration_group_id, sort_order, date_added) values ('MODULE_SHIPPING_CDEK_SORT_ORDER', '0', '6', '0', now())");
    }

    function remove() {
      vam_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_SHIPPING_CDEK_STATUS', 'MODULE_SHIPPING_CDEK_COST','MODULE_SHIPPING_CDEK_API_KEY','MODULE_SHIPPING_CDEK_API_PASSWORD','MODULE_SHIPPING_CDEK_SENDER_CITY','MODULE_SHIPPING_CDEK_ALLOWED', 'MODULE_SHIPPING_CDEK_TAX_CLASS', 'MODULE_SHIPPING_CDEK_ZONE', 'MODULE_SHIPPING_CDEK_DEBUG', 'MODULE_SHIPPING_CDEK_SORT_ORDER');
    }
    
	private function _sdekpvz_api_communicate($request, $token, $city_name)
	{
		  $request = json_encode($request);										   
	
	
	        $curl = curl_init();
	
	        curl_setopt_array($curl, array(
	            CURLOPT_VERBOSE => true,
	            CURLOPT_SSL_VERIFYHOST => false,
	            CURLOPT_SSL_VERIFYPEER => false,
	            CURLOPT_TIMEOUT => 1,
	            CURLOPT_URL => 'https://api.cdek.ru/v2/calculator/tariff',
	            CURLOPT_RETURNTRANSFER => true,
	            CURLOPT_POST => true,
	            CURLOPT_POSTFIELDS => $request,
	            CURLOPT_HTTPHEADER => array(
			       "authorization: Bearer ".$token,
			       "content-type: application/json"
	            ),
	//          CURLOPT_SSLVERSION => 6,
	//          CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
	            CURLOPT_ENCODING, "gzip",
	            CURLOPT_ENCODING, '',
	        ));
	
	        $data = curl_exec($curl);
	        
	        //echo 'test'.var_dump($response);
	        
	        curl_close($curl);
	
	        $js = json_decode($data, $assoc=true);
	        
	    if($js['error'] > 0) { 
		return 'error';
	    } else {
	    	
	    if($data === false)
	    {
		return '10000 server error';
	    } else {
	        
		    //$stream = $data;
			$fp = fopen($file,"w");
	        fwrite($fp, $data);
	        fclose($fp);
	        
	        }
	        }
			
	//echo var_dump($request);	    
	//echo var_dump($data);	        
	    $js = json_decode($data, $assoc=true);
	       
	    return $js;
		
	}
	
	private function sdekpvz_api_calc($weight, $idCity, $idCitySender, $volume, $token, $city_name)
	{	
	
		$weight = number_format($weight*1000, 0, '.', '');
		
		//echo $idCitySender; exit;
	
	    $request = array(
						'from_location' => array('code' => $idCitySender),
						'to_location' => array('code' => $idCity),
						'tariff_code' => 136,
						'weight' => $weight,
						'packages' => array(array('weight' => $weight,
											   'volume' => $volume )));
	
	      
	        $ret = $this->_sdekpvz_api_communicate($request, $token, $city_name);
		
		return $ret;
	}   
    
    
  }
?>
