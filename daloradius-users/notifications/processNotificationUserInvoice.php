<?php

	//include the dompdf class
	require_once("dompdf/dompdf_config.inc.php");

	//include the Pear Mail classes for sending out emails
	@require_once('Mail.php');
	@require_once('Mail/mime.php');
	
	$base = dirname(__FILE__);
	
	
	function createNotification($customerInfo) {

		global $base;
		
		$html = prepareNotificationTemplate($customerInfo);
		$pdfDocument = createPDF($html);
		
		return $pdfDocument;
		
	}

	
	
	function emailNotification($pdfDocument, $customerInfo, $smtpInfo, $from) {

		global $base;
		
		if (empty($customerInfo['customer_email']))
			return;
		
		$headers = array(	"From"	=>	$from, 
							"Subject"	=>	"Invoice Information",
							"Reply-To"=> $from
					);
				
		$mime = new Mail_mime();
		$mime->setTXTBody("Notification letter of service"); 
		$mime->addAttachment($pdfDocument, "application/pdf", "notification.pdf", false, 'base64');
		$body = $mime->get();
		$headers = $mime->headers($headers);
		$mail =& Mail::factory("smtp", $smtpInfo);
		$mail->send($customerInfo['customer_email'], $headers, $body);
	
	}
	
	
	
	function prepareNotificationTemplate($customerInfo) {
	
		global $base;
		
		// the HTML template
		$notification_template = "$base/templates/user_invoice.html";
		$notification_html_template = file_get_contents($notification_template);
	
		$date = date("Y-m-d");
		
		$name = $customerInfo['customer_name'];
		$address = $customerInfo['customer_address'];
		$phone = $customerInfo['customer_phone'];
		$email = $customerInfo['customer_email'];
		
		$invoice_details = $customerInfo['invoice_details'];
		$invoice_items = $customerInfo['invoice_items'];

		// notification date
		$notification_html_template = str_replace("####__INVOICE_CREATION_DATE__####", $date, $notification_html_template);
		
		// user details
		$notification_html_template = str_replace("####__CUSTOMER_NAME__####", $name, $notification_html_template);
		$notification_html_template = str_replace("####__CUSTOMER_ADDRESS__####", $address, $notification_html_template);
		$notification_html_template = str_replace("####__CUSTOMER_PHONE__####", $phone, $notification_html_template);
		$notification_html_template = str_replace("####__CUSTOMER_EMAIL__####", $email, $notification_html_template);
		
		// invoice information
		$notification_html_template = str_replace("####__INVOICE_DETAILS__####", $invoice_details, $notification_html_template);
		$notification_html_template = str_replace("####__INVOICE_ITEMS__####", $invoice_items, $notification_html_template);
		

		return $notification_html_template;
	}
	
	
	function createPDF($html) {
	
		global $base;
		
		// instansiate the pdf document
		$dompdf = new DOMPDF();
		$dompdf->set_base_path("$base/templates/");
		$dompdf->load_html($html);
		$dompdf->render();
	
		$notification_pdf = $dompdf->output();
		
		return $notification_pdf;

	}

?>
