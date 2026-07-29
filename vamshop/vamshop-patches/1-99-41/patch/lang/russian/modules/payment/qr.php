<?php
/* -----------------------------------------------------------------------------------------
   $Id: qr.php 998 2007/02/07 13:24:46 VaM $

   VaM Shop - open source ecommerce solution
   http://vamshop.ru
   http://vamshop.com

   Copyright (c) 2007 VaM Shop
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(moneyorder.php,v 1.8 2003/02/16); www.oscommerce.com 
   (c) 2003	 nextcommerce (moneyorder.php,v 1.4 2003/08/13); www.nextcommerce.org
   (c) 2004	 xt:Commerce (qr.php,v 1.4 2003/08/13); xt-commerce.com

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

global $order;
//echo var_dump($order);

$qrcode = '';

//use Endroid\QrCode\Color\Color;
//use Endroid\QrCode\Encoding\Encoding;
//use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
//use Endroid\QrCode\QrCode;
//use Endroid\QrCode\Label\Label;
//use Endroid\QrCode\Logo\Logo;
//use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
//use Endroid\QrCode\Writer\PngWriter;
//use Endroid\QrCode\Writer\ValidationException;

if (strstr($PHP_SELF, FILENAME_CHECKOUT_SUCCESS) 
or strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY) 
or strstr($PHP_SELF, FILENAME_ACCOUNT_HISTORY_INFO) 
or strstr($PHP_SELF, FILENAME_KVITANCIA) 
or strstr($PHP_SELF, FILENAME_SCHET) 
or strstr($PHP_SELF, FILENAME_CHECKOUT_PROCESS) 
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
  
//echo $qrcode;  

define('MODULE_PAYMENT_QR_TEXT_TITLE', 'Оплата по QR коду');
define('MODULE_PAYMENT_QR_TEXT_DESCRIPTION', '<div><p>Отсканируйте QR код и оплатите в приложении банка.</p>'.$qrcode.'</div><br><div>В назначении платежа укажите: <b>Оплата заказа '.$order->info['id'].'</b></div>');
define('MODULE_PAYMENT_QR_TEXT_EMAIL_FOOTER', '<div><p>Отсканируйте QR код и оплатите в приложении банка.</p>'.$qrcode.'</div><br><div>В назначении платежа укажите: <b>Оплата заказа '.$order->info['id'].'</b></div>');
define('MODULE_PAYMENT_QR_TEXT_INFO','');
define('MODULE_PAYMENT_QR_STATUS_TITLE' , 'Разрешить модуль Перевод с карты на карту');
define('MODULE_PAYMENT_QR_STATUS_DESC' , 'Вы хотите разрешить использование модуля при оформлении заказов?');
define('MODULE_PAYMENT_QR_ALLOWED_TITLE' , 'Разрешённые страны');
define('MODULE_PAYMENT_QR_ALLOWED_DESC' , 'Укажите коды стран, для которых будет доступен данный модуль (например RU,DE (оставьте поле пустым, если хотите что б модуль был доступен покупателям из любых стран))');
define('MODULE_PAYMENT_QR_ID_TITLE' , 'Номер карточки:');
define('MODULE_PAYMENT_QR_ID_DESC' , 'Укажите Ваш номер карточки');
define('MODULE_PAYMENT_QR_SORT_ORDER_TITLE' , 'Порядок сортировки');
define('MODULE_PAYMENT_QR_SORT_ORDER_DESC' , 'Порядок сортировки модуля.');
define('MODULE_PAYMENT_QR_ZONE_TITLE' , 'Зона');
define('MODULE_PAYMENT_QR_ZONE_DESC' , 'Если выбрана зона, то данный модуль оплаты будет виден только покупателям из выбранной зоны.');
define('MODULE_PAYMENT_QR_ORDER_STATUS_ID_TITLE' , 'Статус заказа');
define('MODULE_PAYMENT_QR_ORDER_STATUS_ID_DESC' , 'Заказы, оформленные с использованием данного модуля оплаты будут принимать указанный статус.');

define('MODULE_PAYMENT_QR_COMPANY_TITLE' , 'Название компании:');
define('MODULE_PAYMENT_QR_COMPANY_DESC' , 'Укажите название компании');
define('MODULE_PAYMENT_QR_BANK_TITLE' , 'Название банка:');
define('MODULE_PAYMENT_QR_BANK_DESC' , 'Укажите название Вашего банка');
define('MODULE_PAYMENT_QR_RS_TITLE' , 'Расчётный счёт:');
define('MODULE_PAYMENT_QR_RS_DESC' , 'Укажите номер Вашего расчётного счёта');
define('MODULE_PAYMENT_QR_KS_TITLE' , 'Корреспондентский счёт:');
define('MODULE_PAYMENT_QR_KS_DESC' , 'Укажите номер Вашего корреспондентского счёта');
define('MODULE_PAYMENT_QR_BIK_TITLE' , 'БИК:');
define('MODULE_PAYMENT_QR_BIK_DESC' , 'Укажите БИК');
define('MODULE_PAYMENT_QR_INN_TITLE' , 'ИНН:');
define('MODULE_PAYMENT_QR_INN_DESC' , 'Укажите Ваш ИНН');
define('MODULE_PAYMENT_QR_KPP_TITLE' , 'КПП:');
define('MODULE_PAYMENT_QR_KPP_DESC' , 'Укажите Ваш КПП');


?>