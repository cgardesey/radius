<?php

	//include the dompdf class
	require_once("dompdf/dompdf_config.inc.php");

	//include the Pear Mail classes for sending out emails
	@require_once('Mail.php');
	@require_once('Mail/mime.php');
	
	$base = dirname(__FILE__);
	

	function createUserDetailsInvoiceNotification($customerInfo) {

		global $base;
		
		$html = prepareNotificationTemplate($customerInfo);
		$pdfDocument = createPDF($html);

		file_put_contents("$base/out4.pdf", $pdfDocument);
		
		return $pdfDocument;
		
	}

	

	function emailNotification($pdfDocument, $customerInfo, $smtpInfo, $from) {

		global $base;
		
		if (empty($customerInfo['business_email']))
			return;
		
		$headers = array(	"From"	=>	$from, 
							"Subject"	=>	"User Invoice Notification",
							"Reply-To"=> $from
					);
				
		$mime = new Mail_mime();
		$mime->setTXTBody("Notification letter of service"); 
		$mime->addAttachment($pdfDocument, "application/pdf", "invoice.pdf", false, 'base64');
		$body = $mime->get();
		$headers = $mime->headers($headers);
		$mail =& Mail::factory("smtp", $smtpInfo);
		$mail->send($customerInfo['business_email'], $headers, $body);
	
	}
	
	

	function prepareNotificationTemplate($customerInfo) {
	
		global $base;
		
		// the HTML template
		$notification_template = "$base/templates/user_invoice_details.html";
		$notification_html_template = file_get_contents($notification_template);
	
		$date = date("Y-m-d");
	
		$business_name = $customerInfo['business_name']; 
		$business_address = $customerInfo['business_address'];
		$business_phone = $customerInfo['business_phone'];
		$business_email = $customerInfo['business_email'];
		$service_plan_info = $customerInfo['service_plan_info'];
	
		$notification_html_template = str_replace("####__INVOICE_CREATION_DATE__####", $date, $notification_html_template);
		
		$notification_html_template = str_replace("####__BUSINESS_NAME__####", $business_name, $notification_html_template);
		$notification_html_template = str_replace("####__BUSINESS_ADDRESS__####", $business_address, $notification_html_template);
		$notification_html_template = str_replace("####__BUSINESS_PHONE__####", $business_phone, $notification_html_template);
		$notification_html_template = str_replace("####__BUSINESS_EMAIL__####", $business_email, $notification_html_template);
		$notification_html_template = str_replace("####__SERVICE_PLAN_INFO__####", $service_plan_info, $notification_html_template);
		

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
