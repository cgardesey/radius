<?php


include('library/config_read.php');				// read configuration information

if (isset($_POST['submit'])) {

	$sid = $_POST['business'];						// vendor id
	$cart_order_id = $_POST['item_number'];		// product/plan id 
	$total = $_POST['amount'] + $_POST['tax'];	// cost + taxes = total cost
	$tco_currency = $_POST['currency_code'];		// currency
	$custom = $_POST['custom'];					// transaction id for daloradius
	$item_name = $_POST['item_name'];				// name of the product that is puchased
	$quantity = $_POST['quantity'];				// quantity
	$product_id = $_POST['product_id'];

	// Include the 2checkout library
	include_once ('include/merchant/TwoCo.php');

	// Create an instance of the authorize.net library
	$my2CO = new TwoCo();

	// Specify your 2CheckOut vendor id
	$my2CO->addField('sid', $sid);

	// Specify the order information
	$my2CO->addField('cart_order_id', $cart_order_id);
	$my2CO->addField('total', $total);
	$my2CO->addField('tco_currency', $tco_currency);
	$my2CO->addField('custom', $custom);
	$my2CO->addField('x_email_merchant', 'true');
	$my2CO->addField('c_name_1', $item_name);
	$my2CO->addField('product_id', $product_id);
	$my2CO->addField('c_prod1', $product_id);
	$my2CO->addField('quantity1', $quantity);

	// Specify the url where authorize.net will send the IPN
	$my2CO->addField('x_Receipt_Link_URL', $configValues['CONFIG_MERCHANT_IPN_URL_ROOT']."/".
												$configValues['CONFIG_MERCHANT_IPN_URL_RELATIVE_DIR']);

	// Enable test mode if needed
	$my2CO->enableTestMode();

	// Let's start the train!
	$my2CO->submitPayment();

} else {
	
	echo "

		<html>
		<body>
			error: illegal action <br/>
			<a href='".$configValues['CONFIG_MERCHANT_IPN_URL_ROOT']."'>return</a>to Return home...
		</body>
		</html>	

	";

}



?>
