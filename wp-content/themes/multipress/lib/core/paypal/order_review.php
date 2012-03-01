<?php
/*==================================================================
 PayPal Express Checkout Call
 ===================================================================
*/
// Check to see if the Request object contains a variable named 'token'	
$token = "";
if (isset($_REQUEST['token']))
{
	$token = $_REQUEST['token'];
	
}

// If the Request object contains the variable 'token' then it means that the user is coming from PayPal site.	
if ( $token != "" )
{

	require_once ("paypalfunctions.php");

	/*
	'------------------------------------
	' Calls the GetExpressCheckoutDetails API call
	'
	' The GetShippingDetails function is defined in PayPalFunctions.jsp
	' included at the top of this file.
	'-------------------------------------------------
	*/
	

	$resArray = GetShippingDetails( $token );
	//var_dump($resArray);
	$ack = strtoupper($resArray["ACK"]);
	if( $ack == "SUCCESS" || $ack == "SUCESSWITHWARNING") 
	{
		/*
		' The information that is returned by the GetExpressCheckoutDetails call should be integrated by the partner into his Order Review 
		' page		
		*/
		
		$customer_name = '';
		$email 				= $resArray["EMAIL"]; // ' Email address of payer.
		$payerId 			= $resArray["PAYERID"]; // ' Unique PayPal customer account identification number.
		
		$payerStatus		= $resArray["PAYERSTATUS"]; // ' Status of payer. Character length and limitations: 10 single-byte alphabetic characters.

		if(isset($resArray["SALUTATION"])){
			$salutation			= $resArray["SALUTATION"]; // ' Payer's salutation.
		}
		if(isset($resArray["FIRSTNAME"])){
			$firstName			= $resArray["FIRSTNAME"]; // ' Payer's first name.
			$customer_name .= $firstName.' ';
		}	
		if(isset($resArray["MIDDLENAME"])){
			$middleName			= $resArray["MIDDLENAME"]; // ' Payer's middle name.
			$customer_name .= $middleName.' ';
		}
		if(isset($resArray["LASTNAME"])){	
			$lastName			= $resArray["LASTNAME"]; // ' Payer's last name.
			$customer_name .= $lastName.' ';
		}	
		if(isset($resArray["SUFFIX"])){
			$suffix				= $resArray["SUFFIX"]; // ' Payer's suffix.
		}	
		$cntryCode			= $resArray["COUNTRYCODE"]; // ' Payer's country of residence in the form of ISO standard 3166 two-character country codes.
		if(isset($resArray["BUSINESS"])){
			$business			= $resArray["BUSINESS"]; // ' Payer's business name.
		}	
		$shipToName			= $resArray["PAYMENTREQUEST_0_SHIPTONAME"]; // ' Person's name associated with this address.
		$shipToStreet		= $resArray["PAYMENTREQUEST_0_SHIPTOSTREET"]; // ' First street address.
		if(isset($resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"])){
			$shipToStreet2		= $resArray["PAYMENTREQUEST_0_SHIPTOSTREET2"]; // ' Second street address.
		}	
		$shipToCity			= $resArray["PAYMENTREQUEST_0_SHIPTOCITY"]; // ' Name of city.
		$shipToState		= $resArray["PAYMENTREQUEST_0_SHIPTOSTATE"]; // ' State or province
		$shipToCntryCode	= $resArray["PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE"]; // ' Country code. 
		$shipToZip			= $resArray["PAYMENTREQUEST_0_SHIPTOZIP"]; // ' U.S. Zip code or other country-specific postal code.
		$addressStatus 		= $resArray["ADDRESSSTATUS"]; // ' Status of street address on file with PayPal   
		if(isset($resArray["INVNUM"])){
			$invoiceNumber		= $resArray["INVNUM"]; // ' Your own invoice or tracking number, as set by you in the element of the same name in SetExpressCheckout request .
		}	
		if(isset($resArray["PHONENUM"])){
			$phonNumber			= $resArray["PHONENUM"]; // ' Payer's contact telephone number. Note:  PayPal returns a contact telephone number only if your Merchant account profile settings require that the buyer enter one. 
		}	
		
		$_SESSION['customer_name'] = $customer_name; /*this will be used in email and invoice/ticket */
		//_e("Billing Information:",_DEV_);
		//echo '<br/>';
		
		/*if(isset($firstName)) echo $firstName.' ';
		if(isset($middleName)) echo $middleName.' ';
		if(isset($lastName)) echo $lastName.' ';
		echo '<br/>';*/
		
		
		echo '<p class="button blue confirm_payment txt_r fr">
					<input type="submit" value="'.__('Confirm',_DEV_).'">
				</p>';
		echo '<div class="response_msg"></div>';		
	} 
	else  
	{
		//Display a user friendly Error on the page using any of the following error information returned by PayPal
		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
		
		echo "GetExpressCheckoutDetails API call failed. ";
		echo "Detailed Error Message: " . $ErrorLongMsg;
		echo "Short Error Message: " . $ErrorShortMsg;
		echo "Error Code: " . $ErrorCode;
		echo "Error Severity Code: " . $ErrorSeverityCode;
	}
}
		
?>