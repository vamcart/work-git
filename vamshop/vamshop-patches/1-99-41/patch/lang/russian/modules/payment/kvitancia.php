<?php
/* -----------------------------------------------------------------------------------------
   $Id: eustandardtransfer.php 998 2007/02/07 13:24:46 VaM $
витанц
   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(ptebanktransfer.php,v 1.4.1 2003/09/25 19:57:14); www.oscommerce.com
   (c) 2004	 xt:Commerce (eustandardtransfer.php,v 1.5 2003/08/13); xt-commerce.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

//use Endroid\QrCode\Color\Color;
//use Endroid\QrCode\Encoding\Encoding;
//use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
//use Endroid\QrCode\QrCode;
//use Endroid\QrCode\Label\Label;
//use Endroid\QrCode\Logo\Logo;
//use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
//use Endroid\QrCode\Writer\PngWriter;
//use Endroid\QrCode\Writer\ValidationException;

if (defined('MODULE_PAYMENT_QR_STATUS') && MODULE_PAYMENT_QR_STATUS == 'True') {

$qrcode = '';

if (strstr($PHP_SELF, FILENAME_CHECKOUT_SUCCESS) 
or strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY) 
or strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO) 
or strstr($PHP_SELF, FILENAME_KVITANCIA) 
or strstr($PHP_SELF, FILENAME_CHECKOUT_PROCESS) 
or strstr($PHP_SELF, FILENAME_SCHET) 
or strstr($PHP_SELF, FILENAME_PRINT_ORDER) 
or strstr($PHP_SELF, FILENAME_PRINT_PACKINGSLIP)) {

$qr_str = "ST00012|Purpose=Заказ ".$order->info['id']."|Contract=Заказ ".$order->info['id']."|Sum=".number_format($order->info['total']*100,0,".","")."|Name=".MODULE_PAYMENT_QR_COMPANY . "|PersonalAcc=".MODULE_PAYMENT_QR_RS ."|BankName=".MODULE_PAYMENT_QR_BANK . "|BIC=".MODULE_PAYMENT_QR_BIK ."|CorrespAcc=".MODULE_PAYMENT_QR_KS . "|PayeeINN=".MODULE_PAYMENT_QR_INN ."|KPP=".MODULE_PAYMENT_QR_KPP . "|OKTMO=|LastName=".$order->customer['lastname']."|FirstName=".$order->customer['firstname']."|MiddleName=|PayerAddress=|Flat=|Phone=".$order->customer['telephone']."";

//$writer = new PngWriter();

// Create QR code
//$qrCode = QrCode::create($qr_str)
    //->setEncoding(new Encoding('UTF-8'))
    //->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
    //->setSize(150)
    //->setMargin(10)
    //->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
    //->setForegroundColor(new Color(0, 0, 0))
    //->setBackgroundColor(new Color(255, 255, 255));

// Create generic logo
//$logo = Logo::create(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'logo.png')
//    ->setResizeToWidth(50);

// Create generic label
//$label = Label::create('VamShop')
//    ->setTextColor(new Color(255, 0, 0));

//$result = $writer->write($qrCode, $logo, $label);

// Validate the result
//$writer->validateResult($result, $qr_str);

// Directly output the QR code
//header('Content-Type: '.$result->getMimeType());
//echo $result->getString();
//$qrcode = '<img class="logo" src="' . $result->getDataUri() . '">';
$qrcode = '<img class="logo" src="https://image-charts.com/chart?chs=150x150&cht=qr&chl=' . $qr_str . '">';
  
}
}  

  define('MODULE_PAYMENT_KVITANCIA_TEXT_TITLE', 'Квитанция СБ РФ');
  define('MODULE_PAYMENT_KVITANCIA_TEXT_DESCRIPTION', $qrcode.'<br />Информация для оплаты:<br />' .
                                                         '<br />Название банка: ' . (defined('MODULE_PAYMENT_KVITANCIA_1')? MODULE_PAYMENT_KVITANCIA_1 : false) .
                                                         '<br />Расчётный счёт: ' . (defined('MODULE_PAYMENT_KVITANCIA_2')? MODULE_PAYMENT_KVITANCIA_2 : false) .
                                                         '<br />БИК: ' . (defined('MODULE_PAYMENT_KVITANCIA_3')? MODULE_PAYMENT_KVITANCIA_3 : false) .
                                                         '<br />Кор./счет: ' . (defined('MODULE_PAYMENT_KVITANCIA_4')? MODULE_PAYMENT_KVITANCIA_4 : false) .
                                                         '<br />ИНН: ' . (defined('MODULE_PAYMENT_KVITANCIA_5')? MODULE_PAYMENT_KVITANCIA_5 : false) .
                                                         '<br />Получатель: ' . (defined('MODULE_PAYMENT_KVITANCIA_6')? MODULE_PAYMENT_KVITANCIA_6 : false) .
                                                         '<br />КПП: ' . (defined('MODULE_PAYMENT_KVITANCIA_7')? MODULE_PAYMENT_KVITANCIA_7 : false) .
                                                         '<br /><br />Ваш заказ будет выполнен только после получения оплаты.<br />');
  define('MODULE_PAYMENT_KVITANCIA_TEXT_EMAIL_FOOTER', str_replace('<br />','\n',(defined('MODULE_PAYMENT_KVITANCIA_TEXT_DESCRIPTION')? MODULE_PAYMENT_KVITANCIA_TEXT_DESCRIPTION : false)));

  define('MODULE_PAYMENT_KVITANCIA_STATUS_TITLE','Разрешить модуль Квитанция СБ РФ');
  define('MODULE_PAYMENT_KVITANCIA_STATUS_DESC','Разрешить использование модуля Квитанция СБ РФ при оформлении заказа в магазине?');

  define('MODULE_PAYMENT_KVITANCIA_TEXT_INFO','');

  define('MODULE_PAYMENT_KVITANCIA_1_TITLE','Название банка');
  define('MODULE_PAYMENT_KVITANCIA_1_DESC','Укажите название банка.');

  define('MODULE_PAYMENT_KVITANCIA_2_TITLE','Расчётный счёт');
  define('MODULE_PAYMENT_KVITANCIA_2_DESC','Укажите Ваш расчетный счет.');

  define('MODULE_PAYMENT_KVITANCIA_3_TITLE','БИК');
  define('MODULE_PAYMENT_KVITANCIA_3_DESC','Укажите БИК.');

  define('MODULE_PAYMENT_KVITANCIA_4_TITLE','Кор./счет');
  define('MODULE_PAYMENT_KVITANCIA_4_DESC','Укажите Кор./счет.');

  define('MODULE_PAYMENT_KVITANCIA_5_TITLE','ИНН');
  define('MODULE_PAYMENT_KVITANCIA_5_DESC','Укажите ИНН.');

  define('MODULE_PAYMENT_KVITANCIA_6_TITLE','Получатель');
  define('MODULE_PAYMENT_KVITANCIA_6_DESC','Укажите получателя платежа.');

  define('MODULE_PAYMENT_KVITANCIA_7_TITLE','КПП');
  define('MODULE_PAYMENT_KVITANCIA_7_DESC','Укажите КПП.');

  define('MODULE_PAYMENT_KVITANCIA_8_TITLE','Назначение платежа');
  define('MODULE_PAYMENT_KVITANCIA_8_DESC','Укажите название платежа.');

  define('MODULE_PAYMENT_KVITANCIA_SORT_ORDER_TITLE','Порядок сортировки');
  define('MODULE_PAYMENT_KVITANCIA_SORT_ORDER_DESC','Укажите порядок сортировки модуля.');

  define('MODULE_PAYMENT_KVITANCIA_ALLOWED_TITLE' , 'Разрешённые страны');
  define('MODULE_PAYMENT_KVITANCIA_ALLOWED_DESC' , 'Укажите коды стран, для которых будет доступен данный модуль (например RU,DE (оставьте поле пустым, если хотите что б модуль был доступен покупателям из любых стран))');

  define('MODULE_PAYMENT_KVITANCIA_ZONE_TITLE' , 'Зона');
  define('MODULE_PAYMENT_KVITANCIA_ZONE_DESC' , 'Если выбрана зона, то данный модуль оплаты будет виден только покупателям из выбранной зоны.');

  define('MODULE_PAYMENT_KVITANCIA_ORDER_STATUS_ID_TITLE' , 'Статус заказа');
  define('MODULE_PAYMENT_KVITANCIA_ORDER_STATUS_ID_DESC' , 'Заказы, оформленные с использованием данного модуля оплаты будут принимать указанный статус.');

define('MODULE_PAYMENT_KVITANCIA_NAME_TITLE','Информация о плательщике');
define('MODULE_PAYMENT_KVITANCIA_NAME_DESC','');
define('MODULE_PAYMENT_KVITANCIA_NAME','ФИО:');
define('MODULE_PAYMENT_KVITANCIA_ADDRESS','Адрес:');
define('MODULE_PAYMENT_KVITANCIA_ADDRESS_HELP',' Пример: г. Ставрополь, ул. Мира 111, оф. 11');

?>