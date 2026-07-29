<?php
/* -----------------------------------------------------------------------------------------
   $Id: schet.php 998 2007/02/07 13:24:46 VaM $
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

$qr_str = rawurlencode("ST00012|Purpose=Заказ ".$order->info['id']."|Contract=Заказ ".$order->info['id']."|Sum=".number_format($order->info['total']*100,0,".","")."|Name=".MODULE_PAYMENT_QR_COMPANY . "|PersonalAcc=".MODULE_PAYMENT_QR_RS ."|BankName=".MODULE_PAYMENT_QR_BANK . "|BIC=".MODULE_PAYMENT_QR_BIK ."|CorrespAcc=".MODULE_PAYMENT_QR_KS . "|PayeeINN=".MODULE_PAYMENT_QR_INN ."|KPP=".MODULE_PAYMENT_QR_KPP . "|OKTMO=|LastName=".$order->customer['lastname']."|FirstName=".$order->customer['firstname']."|MiddleName=|PayerAddress=|Flat=|Phone=".$order->customer['telephone']."");

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

  define('MODULE_PAYMENT_SCHET_TEXT_TITLE', 'Оплата по счёту');
  define('MODULE_PAYMENT_SCHET_TEXT_DESCRIPTION', $qrcode.'<br />Информация для оплаты:<br />' .
                                                         '<br />Поставщик: ' . (defined('MODULE_PAYMENT_SCHET_1')? MODULE_PAYMENT_SCHET_1 : false) .
                                                         '<br />Адрес: ' . (defined('MODULE_PAYMENT_SCHET_2')? MODULE_PAYMENT_SCHET_2 : false) .
                                                         '<br />Телефон: ' . (defined('MODULE_PAYMENT_SCHET_3')? MODULE_PAYMENT_SCHET_3 : false) .
                                                         '<br />Факс: ' . (defined('MODULE_PAYMENT_SCHET_4')? MODULE_PAYMENT_SCHET_4 : false) .
                                                         '<br />Р/c: ' . (defined('MODULE_PAYMENT_SCHET_5')? MODULE_PAYMENT_SCHET_5 : false) .
                                                         '<br />Название банка: ' . (defined('MODULE_PAYMENT_SCHET_6')? MODULE_PAYMENT_SCHET_6 : false) .
                                                         '<br />К/c: ' . (defined('MODULE_PAYMENT_SCHET_7')? MODULE_PAYMENT_SCHET_7 : false) .
                                                         '<br />БИК: ' . (defined('MODULE_PAYMENT_SCHET_8')? MODULE_PAYMENT_SCHET_8 : false) .
                                                         '<br />ИНН: ' . (defined('MODULE_PAYMENT_SCHET_9')? MODULE_PAYMENT_SCHET_9 : false) .
                                                         '<br />КПП: ' . (defined('MODULE_PAYMENT_SCHET_10')? MODULE_PAYMENT_SCHET_10 : false) .
                                                         '<br />ОГРН: ' . (defined('MODULE_PAYMENT_SCHET_11')? MODULE_PAYMENT_SCHET_11 : false) .
                                                         '<br />ОКПО: ' . (defined('MODULE_PAYMENT_SCHET_12')? MODULE_PAYMENT_SCHET_12 : false) .
                                                         '<br /><br />Ваш заказ будет выполнен только после получения оплаты.<br />');
  define('MODULE_PAYMENT_SCHET_TEXT_EMAIL_FOOTER', str_replace('<br />','\n',MODULE_PAYMENT_SCHET_TEXT_DESCRIPTION));

  define('MODULE_PAYMENT_SCHET_STATUS_TITLE','Разрешить модуль Оплата по счёту');
  define('MODULE_PAYMENT_SCHET_STATUS_DESC','Разрешить использование модуля Оплата по счёту при оформлении заказа в магазине?');

  define('MODULE_PAYMENT_SCHET_TEXT_INFO','');

  define('MODULE_PAYMENT_SCHET_1_TITLE','Поставщик');
  define('MODULE_PAYMENT_SCHET_1_DESC','Укажите название организации.');

  define('MODULE_PAYMENT_SCHET_2_TITLE','Адрес');
  define('MODULE_PAYMENT_SCHET_2_DESC','Укажите адрес организации.');

  define('MODULE_PAYMENT_SCHET_3_TITLE','Телефон');
  define('MODULE_PAYMENT_SCHET_3_DESC','Укажите телефон.');

  define('MODULE_PAYMENT_SCHET_4_TITLE','Факс');
  define('MODULE_PAYMENT_SCHET_4_DESC','Укажите факс.');

  define('MODULE_PAYMENT_SCHET_5_TITLE','Р/с');
  define('MODULE_PAYMENT_SCHET_5_DESC','Укажите р/с.');

  define('MODULE_PAYMENT_SCHET_6_TITLE','Название банка');
  define('MODULE_PAYMENT_SCHET_6_DESC','Укажите название банка.');

  define('MODULE_PAYMENT_SCHET_7_TITLE','К/c');
  define('MODULE_PAYMENT_SCHET_7_DESC','Укажите к/c.');

  define('MODULE_PAYMENT_SCHET_8_TITLE','БИК');
  define('MODULE_PAYMENT_SCHET_8_DESC','Укажите БИК.');

  define('MODULE_PAYMENT_SCHET_9_TITLE','ИНН');
  define('MODULE_PAYMENT_SCHET_9_DESC','Укажите ИНН.');

  define('MODULE_PAYMENT_SCHET_10_TITLE','КПП');
  define('MODULE_PAYMENT_SCHET_10_DESC','Укажите КПП.');

  define('MODULE_PAYMENT_SCHET_11_TITLE','ОГРН');
  define('MODULE_PAYMENT_SCHET_11_DESC','Укажите ОГРН.');

  define('MODULE_PAYMENT_SCHET_12_TITLE','ОКПО');
  define('MODULE_PAYMENT_SCHET_12_DESC','Укажите ОКПО.');

  define('MODULE_PAYMENT_SCHET_SORT_ORDER_TITLE','Порядок сортировки');
  define('MODULE_PAYMENT_SCHET_SORT_ORDER_DESC','Укажите порядок сортировки модуля.');

  define('MODULE_PAYMENT_SCHET_ALLOWED_TITLE' , 'Разрешённые страны');
  define('MODULE_PAYMENT_SCHET_ALLOWED_DESC' , 'Укажите коды стран, для которых будет доступен данный модуль (например RU,DE (оставьте поле пустым, если хотите что б модуль был доступен покупателям из любых стран))');

  define('MODULE_PAYMENT_SCHET_ZONE_TITLE' , 'Зона');
  define('MODULE_PAYMENT_SCHET_ZONE_DESC' , 'Если выбрана зона, то данный модуль оплаты будет виден только покупателям из выбранной зоны.');

  define('MODULE_PAYMENT_SCHET_ORDER_STATUS_ID_TITLE' , 'Статус заказа');
  define('MODULE_PAYMENT_SCHET_ORDER_STATUS_ID_DESC' , 'Заказы, оформленные с использованием данного модуля оплаты будут принимать указанный статус.');

  define('MODULE_PAYMENT_SCHET_J_NAME_TITLE' , 'Информация о плательщике');
  define('MODULE_PAYMENT_SCHET_J_NAME_DESC' , '');

  define('MODULE_PAYMENT_SCHET_J_NAME' , 'Название организации:');
  define('MODULE_PAYMENT_SCHET_J_NAME_IP' , ' или ФИО предпринимателя');
  define('MODULE_PAYMENT_SCHET_J_INN' , 'ИНН:');
  define('MODULE_PAYMENT_SCHET_J_KPP' , 'КПП:');
  define('MODULE_PAYMENT_SCHET_J_OGRN' , 'ОГРН:');
  define('MODULE_PAYMENT_SCHET_J_OKPO' , 'ОКПО:');
  define('MODULE_PAYMENT_SCHET_J_RS' , 'Р/с:');
  define('MODULE_PAYMENT_SCHET_J_BANK_NAME' , 'Название банка:');
  define('MODULE_PAYMENT_SCHET_J_BANK_NAME_HELP' , ' Пример: ОАО АКБ "РОСБАНК" Ставропольский филиал, г. Ставрополь');
  define('MODULE_PAYMENT_SCHET_J_BIK' , 'БИК:');
  define('MODULE_PAYMENT_SCHET_J_KS' , 'К/с:');
  define('MODULE_PAYMENT_SCHET_J_ADDRESS' , 'Почтовый адрес:');
  define('MODULE_PAYMENT_SCHET_J_ADDRESS_HELP' , ' Пример: 355029, г. Ставрополь, ул. Мира 111, оф. 11');
  define('MODULE_PAYMENT_SCHET_J_YUR_ADDRESS' , 'Юридический адрес');
  define('MODULE_PAYMENT_SCHET_J_FAKT_ADDRESS' , 'Фактический адрес');
  define('MODULE_PAYMENT_SCHET_J_TELEPHONE' , 'Телефон');
  define('MODULE_PAYMENT_SCHET_J_FAX' , 'Факс');
  define('MODULE_PAYMENT_SCHET_J_EMAIL' , 'Email');
  define('MODULE_PAYMENT_SCHET_J_DIRECTOR' , 'Руководитель');
  define('MODULE_PAYMENT_SCHET_J_ACCOUNTANT' , 'Бухгалтер');

?>