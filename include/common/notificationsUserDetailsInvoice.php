<?php


	require_once(dirname(__FILE__)."/../../notifications/processNotificationUserDetailsInvoice.php");
	//require_once(dirname(__FILE__)."/../../library/config_read.php");


	
	function getCustomerInfo($row) {
	
		//global $configValues;
		
		$customerInfo = array();
		

		getCustomerInfo_customer_info($row, $customerInfo);
		getCustomerInfo_service_plan($row, $customerInfo);
		
		return $customerInfo;
		
	}
	
	
	
	function getCustomerInfo_customer_info($row, &$customerInfo) {
				
		global $configValues;
		require(dirname(__FILE__)."/../../lang/main.php");
		
		$tableTags = "width='580px' ";
		$tableTrTags = "bgcolor='#ECE5B6'";
		
		
		if (!empty($row['email1']))
			$invoice_email = $row['email1'];
		else if (!empty($row['email2']))
			$invoice_email = $row['email2'];
		else if (!empty($row['email3']))
			$invoice_email = $row['email3'];
		else
			$invoice_email = "";
		
		if (!empty($row['mobilephone']))
			$invoice_phone = $row['mobilephone'];
		else if (!empty($row['workphone']))
			$invoice_phone = $row['mobilephone'];
		else if (!empty($row['homephone']))
			$invoice_phone = $row['homephone'];
		else
			$invoice_phone = "Unavailable";
			
		$invoice_address = "";
		if (!empty($row['address']))
			$invoice_address = $row['address'];
		
		if (!empty($row['city']))
			$invoice_address .= ", ".$row['city'];
		
		if (!empty($row['state']))
			$invoice_address .= "<br/>".$row['state'];
		
		if (!empty($row['zip']))
			$invoice_address .= " ".$row['zip'];
		
		if (empty($invoice_address))
			$invoice_address = "Unavailable";
		
		$customerInfo['business_name'] = $row['firstname']. " " .$row['lastname'];
		$customerInfo['business_address'] = $invoice_address;
		$customerInfo['business_phone'] = $invoice_phone;
		$customerInfo['business_email'] = $invoice_email;


	}
	
	
	function getCustomerInfo_service_plan($row, &$customerInfo) {
				
		global $configValues;
		require(dirname(__FILE__)."/../../lang/main.php");
		
		$tableTags = "width='580px' ";
		$tableTrTags = "bgcolor='#ECE5B6'";
		
		$service_plan_info = "";
		$service_plan_info = "<table $tableTags>";
		
		$service_plan_info .= "".

					"<tr $tableTrTags'>
					<td>".t('all','Username')."</td>
					<td>".$row['username']."</td>
					</tr>".
		
					"<tr $tableTrTags'>
					<td>".t('all','PlanName')."</td>
					<td>".$row['planname']."</td>
					</tr>".
					"<tr $tableTrTags'>
					<td>".t('all','PlanRecurring')."</td>
					<td>".$row['planRecurring']."</td>
					</tr>".
					"<tr $tableTrTags'>
					<td>".t('all','PlanRecurringPeriod')."</td>
					<td>".$row['planRecurringPeriod']."</td>
					</tr>".
					"<tr $tableTrTags'>
					<td>".t('all','PlanCost')."</td>
					<td>".$row['planCost']."</td>
					</tr>".
					"<tr $tableTrTags'>
					<td>".t('all','NextBill')."</td>
					<td>".$row['nextbill']."</td>
					</tr>".
					"<tr $tableTrTags'>
					<td>".t('all','BillDue')."</td>
					<td>".$row['billdue']."</td>
					</tr>".
					"";
		
		$service_plan_info .= "</table>";
		$customerInfo['service_plan_info'] = $service_plan_info;	
		
	}
	
	

?>