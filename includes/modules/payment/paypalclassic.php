<?php
/* -----------------------------------------------------------------------------------------
   $Id: paypalclassic.php 11037 2017-12-16 08:08:01Z GTB $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


// include needed classes
require_once(DIR_FS_EXTERNAL.'paypal/classes/PayPalPayment.php');


class paypalclassic extends PayPalPayment {
	var $code, $title, $description, $extended_description, $enabled;


	function __construct() {
		global $order;
    
    PayPalPayment::__construct('paypalclassic');

		$this->tmpOrders = false;
	}


  function confirmation() {
    return array ('title' => $this->description);
  }


	function before_process() {
		// confirmed
		if (isset($_GET['PayerID']) && $_GET['PayerID'] != '' 
		    && isset($_GET['token']) && $_GET['token'] != '' 
		    && isset($_GET['paymentId']) && $_GET['paymentId'] != '' 
		    && $_GET['paymentId'] == $_SESSION['paypal']['paymentId']		
		    ) 
		{
   		return;
		}
 		$this->payment_redirect();
	}


  function before_send_order() {
		$this->validate_payment_paypal();
  }


	function after_process() {
		unset($_SESSION['paypal']);
	}


	function install() {
	  parent::install();
	  
	  require_once(DIR_FS_CATALOG.'includes/modules/payment/paypalcart.php');
	  $paypalcart = new paypalcart();
	  if ($paypalcart->check() != 1) {
	    $paypalcart->install();
	  }
	}


	function keys() {
		return array('MODULE_PAYMENT_PAYPALCLASSIC_STATUS', 
		             'MODULE_PAYMENT_PAYPALCLASSIC_ALLOWED', 
		             'MODULE_PAYMENT_PAYPALCLASSIC_ZONE',
		             'MODULE_PAYMENT_PAYPALCLASSIC_SORT_ORDER'
		             );
	}

}
?>