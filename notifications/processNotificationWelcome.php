<?php

	//include the dompdf class
	require_once("dompdf/dompdf_config.inc.php");

	//include the Pear Mail classes for sending out emails
	@require_once('Mail.php');
	@require_once('Mail/mime.php');
	
	$base = dirname(__FILE__);
	
	
	function sendWelcomeNotification($customerInfo, $smtpInfo, $from) {

		global $base;
		
		if (empty($customerInfo['customer_email']))
			return;
		
		$headers = array(	"From"	=>	$from, 
							"Subject"	=>	"Welcome new customer!",
							"Reply-To"=> $from
					);
		
		$html = prepareNotificationTemplate($customerInfo);
		$pdfDocument = createPDF($html);
		
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
		$notification_template = "$base/templates/welcome.html";
		$notification_html_template = file_get_contents($notification_template);
	
		$date = date("Y-m-d");
	
		$customer_name = $customerInfo['customer_name'];
		$customer_address = $customerInfo['customer_address']; 
		$customer_phone = $customerInfo['customer_phone'];
		$customer_email = $customerInfo['customer_email'];
	
		$notification_html_template = str_replace("####__INVOICE_CREATION_DATE__####", $date, $notification_html_template);
		$notification_html_template = str_replace("####__CUSTOMER_NAME__####", $customer_name, $notification_html_template);
		$notification_html_template = str_replace("####__CUSTOMER_ADDRESS__####", $customer_address, $notification_html_template);
		$notification_html_template = str_replace("####__CUSTOMER_PHONE__####", $customer_phone, $notification_html_template);
		$notification_html_template = str_replace("####__CUSTOMER_EMAIL__####", $customer_email, $notification_html_template);

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
