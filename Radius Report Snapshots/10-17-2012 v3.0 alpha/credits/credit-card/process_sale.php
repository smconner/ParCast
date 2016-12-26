<?php

require_once '../../radius-report-config.php';
require_once( '../../../../../' . 'wp-blog-header.php' );

/** ----------- PROCESS CREDIT CARD ----------- **/

	$customer_id =  $_POST['x_current_user_id'];

	$invoice_num = $customer_id . "-" . time();
	
	$invoice_description = $_POST['x_plan'] . " ASTM E1527-05 radius map report";
	
	$transaction_fields = array(
        'amount' => $_POST['x_amount'],
        'email' => $_POST['x_current_user_email'],
        'email_customer' => $_POST['x_email_customer'],
        'cust_id' => $customer_id,
		'invoice_num' => $invoice_num,
		'description' => $invoice_description,
        'card_num' => $_POST['x_card_num'], 
        'exp_date' => $_POST['x_exp_date'],
        'address' => $_POST['x_current_user_street_address'],
        'city' => $_POST['x_current_user_city'],
        'state' => $_POST['x_current_user_state'],
        'country' => $_POST['x_current_user_country'],
        'zip' => $_POST['x_current_user_zip'],
        'card_code' => $_POST['x_card_code'] );
        
	$transaction = new AuthorizeNetAIM( AUTHORIZENET_API_LOGIN_ID, AUTHORIZENET_TRANSACTION_KEY );
    $transaction->setSandbox( AUTHORIZENET_SANDBOX );
    $transaction->setFields( $transaction_fields );
    
    $response = $transaction->authorizeAndCapture();
    
    //error_log("Transaction DEFINITIONS - ID: " . AUTHORIZENET_API_LOGIN_ID . " KEY: " . AUTHORIZENET_TRANSACTION_KEY );
    //error_log("Transaction DEFINITIONS - SANDBOX: " . AUTHORIZENET_SANDBOX );
    //error_log("Transaction Fields: " . print_r($transaction_fields, 1) );
    //error_log("Transaction Response: " . print_r($response, 1) );
    
	//error_log( $response->response_code );
	//error_log( $response->response_subcode );
	//error_log( $response->response_reason_code );
	//error_log( $response->response_reason_text );

    
    if ( $response->approved && isset($customer_id) ) {		// Transaction approved
    	
        $credit_balance = get_user_meta( $customer_id, "radius_report_user_credit_balance", true );
        $new_credit_balance = $credit_balance + intval($_POST['x_plan']);
        update_user_meta( $customer_id, "radius_report_user_credit_balance", $new_credit_balance );
                
        header("Location: thank_you_page.php?transaction_id=$response->transaction_id&email_address=$response->email_address&new_credit_balance=$new_credit_balance");
		error_log("Customer ID: " . $customer_id . " |  Current credit balance: " . $credit_balance . " | New credit balance: " . $new_credit_balance);
		
    } else {	
    	
    	$sAltResponseReason = processAltResonseCode( $response->response_reason_code );
    	
    	$sHeader = "";
    	$sHeader .= "?response_reason_code=" . $response->response_reason_code;
    	$sHeader .= "&response_code=" . $response->response_code;
    	$sHeader .= "&response_reason_text=" . $response->response_reason_text;
    	$sHeader .= "&response_alt_reason_text=" . $sAltResponseReason;
    	$sHeader .= "&x_plan=" . $_POST['x_plan'];
    	$sHeader .= "&x_amount=" . $_POST['x_amount'];
    	$sHeader .= "&x_current_user_id=" . $_POST['x_current_user_id'];
    	$sHeader .= "&x_current_user_email=" . $_POST['x_current_user_email'];
    	$sHeader .= "&x_current_user_street_address=" . $_POST['x_current_user_street_address'];
    	$sHeader .= "&x_current_user_zip=" . $_POST['x_current_user_zip'];
    	$sHeader .= "&x_current_user_state=" . $_POST['x_current_user_state'];
    	$sHeader .= "&x_current_user_city=" . $_POST['x_current_user_city'];
    	$sHeader .= "&x_current_user_country=" . $_POST['x_current_user_country'];

    	
        error_log("HEADER STRING: $sHeader");
        error_log("Transaction error for customer ID: " .$customer_id);
        error_log( "ALT REASON FROM SWITCH : " .$sAltResponseReason );
        
        header("Location: error_page.php$sHeader");

    } // end if

    
    

/**
 * Given an AIM reason code, returns Secondary text reason.
 * @param string $response_reason_code
 * @return string
 */

function processAltResonseCode( $response_reason_code ) {

	switch ( $response_reason_code ) {
		
		case "1":
		return "";
		break;
		case "2":
		return "";
		break;
		case "3":
		return "";
		break;
		case "4":
		return "The code returned from the processor indicating that the card used needs to be picked up.";
		break;
		case "5":
		return "The value submitted in the amount field did not pass validation for a number.";
		break;
		case "6":
		return "";
		break;
		case "7":
		return "The format of the date submitted was incorrect.";
		break;
		case "8":
		return "";
		break;
		case "9":
		return "The value submitted in the x_bank_aba_code field did not pass validation or was not for a valid financial institution.";
		break;
		case "10":
		return "The value submitted in the x_bank_acct_num field did not pass validation.";
		break;
		case "11":
		return "A transaction with identical amount and credit card information was submitted two minutes prior.";
		break;
		case "12":
		return "A transaction that required x_auth_code to be present was submitted without a value.";
		break;
		case "13":
		return "";
		break;
		case "14":
		return "The Relay Response or Referrer URL does not match the merchants configured value(s) or is absent. Applicable only to SIM and WebLink APIs.";
		break;
		case "15":
		return "The transaction ID value is non-numeric or was not present for a transaction that requires it (i.e., VOID, PRIOR_AUTH_CAPTURE, and CREDIT).";
		break;
		case "16":
		return "The transaction ID sent in was properly formatted but the gateway had no record of the transaction.";
		break;
		case "17":
		return "The merchant was not configured to accept the credit card submitted in the transaction.";
		break;
		case "18":
		return "The merchant does not accept electronic checks.";
		break;
		case "19":
		return "";
		break;
		case "20":
		return "";
		break;
		case "21":
		return "";
		break;
		case "22":
		return "";
		break;
		case "23":
		return "";
		break;
		case "24":
		return "";
		break;
		case "25":
		return "";
		break;
		case "26":
		return "";
		break;
		case "27":
		return "";
		break;
		case "28":
		return "The Merchant ID at the processor was not configured to accept this card type.";
		break;
		case "29":
		return "";
		break;
		case "30":
		return "";
		break;
		case "31":
		return "The merchant was incorrectly set up at the processor.";
		break;
		case "32":
		return "";
		break;
		case "33":
		return "The word FIELD will be replaced by an actual field name. This error indicates that a field the merchant specified as required was not filled in.";
		break;
		case "34":
		return "The merchant was incorrectly set up at the processor.";
		break;
		case "35":
		return "The merchant was incorrectly set up at the processor.";
		break;
		case "36":
		return "";
		break;
		case "37":
		return "";
		break;
		case "38":
		return "The merchant was incorrectly set up at the processor.";
		break;
		case "40":
		return "";
		break;
		case "41":
		return "Only merchants set up for the FraudScreen.Net service would receive this decline. This code will be returned if a given transactions fraud score is higher than the threshold set by the merchant.";
		break;
		case "43":
		return "The merchant was incorrectly set up at the processor.";
		break;
		case "44":
		return "The card code submitted with the transaction did not match the card code on file at the card issuing bank and the transaction was declined";
		break;
		case "45":
		return "This error would be returned if the transaction received a code from the processor that matched the rejection criteria set by the merchant for both the AVS and Card Code filters.";
		break;
		case "46":
		return "";
		break;
		case "47":
		return "This occurs if the merchant tries to capture funds greater than the amount of the original authorization-only transaction.";
		break;
		case "48":
		return "The merchant attempted to settle for less than the originally authorized amount.";
		break;
		case "49":
		return "The transaction amount submitted was greater than the maximum amount allowed.";
		break;
		case "50":
		return "Credits or refunds may only be performed against settled transactions. The transaction against which the credit/refund was submitted has not been settled, so a credit cannot be issued.";
		break;
		case "51":
		return "";
		break;
		case "52":
		return "";
		break;
		case "53":
		return "If x_method = ECHECK, x_type cannot be set to CAPTURE_ONLY.";
		break;
		case "54":
		return "";
		break;
		case "55":
		return "The transaction is rejected if the sum of this credit and prior credits exceeds the original debit amount";
		break;
		case "56":
		return "The merchant processes eCheck.Net transactions only and does not accept credit cards.";
		break;
		case "57":
		return "";
		break;
		case "58":
		return "";
		break;
		case "59":
		return "";
		break;
		case "60":
		return "";
		break;
		case "61":
		return "";
		break;
		case "62":
		return "";
		break;
		case "63":
		return "";
		break;
		case "64":
		return "This error is applicable to Wells Fargo SecureSource merchants only.";
		break;
		case "65":
		return "The transaction was declined because the merchant configured their account through the Merchant Interface to reject transactions with certain values for a Card Code mismatch.";
		break;
		case "66":
		return "The transaction did not meet gateway security guidelines.";
		break;
		case "68":
		return "The value submitted in x_version was invalid.";
		break;
		case "69":
		return "The value submitted in x_type was invalid.";
		break;
		case "70":
		return "The value submitted in x_method was invalid.";
		break;
		case "71":
		return "The value submitted in x_bank_acct_type was invalid.";
		break;
		case "72":
		return "The value submitted in x_auth_code was more than six characters in length.";
		break;
		case "73":
		return "The format of the value submitted in x_drivers_license_dob was invalid.";
		break;
		case "74":
		return "The value submitted in x_duty failed format validation.";
		break;
		case "75":
		return "The value submitted in x_freight failed format validation.";
		break;
		case "76":
		return "The value submitted in x_tax failed format validation.";
		break;
		case "77":
		return "The value submitted in x_customer_tax_id failed validation.";
		break;
		case "78":
		return "The value submitted in x_card_code failed format validation.";
		break;
		case "79":
		return "The value submitted in x_drivers_license_num failed format validation.";
		break;
		case "80":
		return "The value submitted in x_drivers_license_state failed format validation.";
		break;
		case "81":
		return "The merchant requested an integration method not compatible with the AIM API.";
		break;
		case "82":
		return "The system no longer supports version 2.5; requests cannot be posted to scripts.";
		break;
		case "83":
		return "The system no longer supports version 2.5; requests cannot be posted to scripts.";
		break;
		case "84":
		return "";
		break;
		case "85":
		return "";
		break;
		case "86":
		return "";
		break;
		case "87":
		return "";
		break;
		case "88":
		return "";
		break;
		case "89":
		return "";
		break;
		case "90":
		return "";
		break;
		case "91":
		return "";
		break;
		case "92":
		return "";
		break;
		case "97":
		return "Applicable only to SIM API. Fingerprints are only valid for a short period of time. This code indicates that the transaction fingerprint has expired.";
		break;
		case "98":
		return "Applicable only to SIM API. The transaction fingerprint has already been used.";
		break;
		case "99":
		return "Applicable only to SIM API. The server-generated fingerprint does not match the merchant-specified fingerprint in the x_fp_hash field.";
		break;
		case "100":
		return "Applicable only to eCheck.Net. The value specified in the x_echeck_type field is invalid.";
		break;
		case "101":
		return "Applicable only to eCheck.Net. The specified name on the account and/or the account type do not match the NOC record for this account.";
		break;
		case "102":
		return "A password or Transaction Key was submitted with this WebLink request. This is a high security risk.";
		break;
		case "103":
		return "A valid fingerprint, Transaction Key, or password is required for this transaction.";
		break;
		case "104":
		return "Applicable only to eCheck.Net. The value submitted for country failed validation.";
		break;
		case "105":
		return "Applicable only to eCheck.Net. The values submitted for city and country failed validation.";
		break;
		case "106":
		return "Applicable only to eCheck.Net. The value submitted for company failed validation.";
		break;
		case "107":
		return "Applicable only to eCheck.Net. The value submitted for bank account name failed validation.";
		break;
		case "108":
		return "Applicable only to eCheck.Net. The values submitted for first name and last name failed validation.";
		break;
		case "109":
		return "Applicable only to eCheck.Net. The values submitted for first name and last name failed validation.";
		break;
		case "110":
		return "Applicable only to eCheck.Net. The value submitted for bank account name does not contain valid characters.";
		break;
		case "116":
		return "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. The ECI value for a Visa transaction; or the UCAF indicator for a MasterCard transaction submitted in the x_authentication_indicator field is invalid.";
		break;
		case "117":
		return "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. The CAVV for a Visa transaction; or the AVV/UCAF for a MasterCard transaction is invalid.";
		break;
		case "118":
		return "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. The combination of authentication indicator and cardholder authentication value for a Visa or MasterCard transaction is invalid. For more information, see the 'Cardholder Authentication' topic.";
		break;
		case "119":
		return "This error is only applicable to Verified by Visa and MasterCard SecureCode transactions. Transactions submitted with a value in x_authentication_indicator and x_recurring_billing=YES will be rejected.";
		break;
		case "120":
		return "The system-generated void for the original timed-out transaction failed. (The original transaction timed out while waiting for a response from the authorizer.)";
		break;
		case "121":
		return "The system-generated void for the original errored transaction failed. (The original transaction experienced a database error.)";
		break;
		case "122":
		return "The system-generated void for the original errored transaction failed. (The original transaction experienced a processing error.)";
		break;
		case "123":
		return "The transaction request must include the API Login ID associated with the payment gateway account.";
		break;
		case "127":
		return "The system-generated void for the original AVS-rejected transaction failed.";
		break;
		case "128":
		return "The customers financial institution does not currently allow transactions for this account.";
		break;
		case "130":
		return "IFT: The payment gateway account status is Blacklisted.";
		break;
		case "131":
		return "IFT: The payment gateway account status is Suspended-STA.";
		break;
		case "132":
		return "IFT: The payment gateway account status is Suspended-Blacklist.";
		break;
		case "141":
		return "The system-generated void for the original FraudScreen-rejected transaction failed.";
		break;
		case "145":
		return "The system-generated void for the original card code-rejected and AVS-rejected transaction failed.";
		break;
		case "152":
		return "The system-generated void for the original transaction failed. The response for the original transaction could not be communicated to the client.";
		break;
		case "165":
		return "The system-generated void for the original card code-rejected transaction failed.";
		break;
		case "170":
		return "Concord EFS Provisioning at the processor has not been completed.";
		break;
		case "171":
		return "Concord EFS This request is invalid.";
		break;
		case "172":
		return "Concord EFS The store ID is invalid.";
		break;
		case "173":
		return "Concord EFS The store key is invalid.";
		break;
		case "174":
		return "Concord EFS This transaction type is not accepted by the processor.";
		break;
		case "175":
		return "Concord EFS This transaction is not allowed. The Concord EFS processing platform does not support voiding credit transactions. Please debit the credit card instead of voiding the credit.";
		break;
		case "180":
		return "The processor response format is invalid.";
		break;
		case "181":
		return "The system-generated void for the original invalid transaction failed. (The original transaction included an invalid processor response format.)";
		break;
		case "185":
		return "";
		break;
		case "193":
		return "The transaction was placed under review by the risk management system.";
		break;
		case "200":
		return "This error code applies only to merchants on FDC Omaha. The credit card number is invalid.";
		break;
		case "201":
		return "This error code applies only to merchants on FDC Omaha. The expiration date is invalid.";
		break;
		case "202":
		return "This error code applies only to merchants on FDC Omaha. The transaction type is invalid.";
		break;
		case "203":
		return "This error code applies only to merchants on FDC Omaha. The value submitted in the amount field is invalid.";
		break;
		case "204":
		return "This error code applies only to merchants on FDC Omaha. The department code is invalid.";
		break;
		case "205":
		return "This error code applies only to merchants on FDC Omaha. The value submitted in the merchant number field is invalid.";
		break;
		case "206":
		return "This error code applies only to merchants on FDC Omaha. The merchant is not on file.";
		break;
		case "207":
		return "This error code applies only to merchants on FDC Omaha. The merchant account is closed.";
		break;
		case "208":
		return "This error code applies only to merchants on FDC Omaha. The merchant is not on file.";
		break;
		case "209":
		return "This error code applies only to merchants on FDC Omaha. Communication with the processor could not be established.";
		break;
		case "210":
		return "This error code applies only to merchants on FDC Omaha. The merchant type is incorrect.";
		break;
		case "211":
		return "This error code applies only to merchants on FDC Omaha. The cardholder is not on file.";
		break;
		case "212":
		return "This error code applies only to merchants on FDC Omaha. The bank configuration is not on file";
		break;
		case "213":
		return "This error code applies only to merchants on FDC Omaha. The merchant assessment code is incorrect.";
		break;
		case "214":
		return "This error code applies only to merchants on FDC Omaha. This function is currently unavailable.";
		break;
		case "215":
		return "This error code applies only to merchants on FDC Omaha. The encrypted PIN field format is invalid.";
		break;
		case "216":
		return "This error code applies only to merchants on FDC Omaha. The ATM term ID is invalid.";
		break;
		case "217":
		return "This error code applies only to merchants on FDC Omaha. This transaction experienced a general message format problem.";
		break;
		case "218":
		return "This error code applies only to merchants on FDC Omaha. The PIN block format or PIN availability value is invalid.";
		break;
		case "219":
		return "This error code applies only to merchants on FDC Omaha. The ETC void is unmatched.";
		break;
		case "220":
		return "This error code applies only to merchants on FDC Omaha. The primary CPU is not available.";
		break;
		case "221":
		return "This error code applies only to merchants on FDC Omaha. The SE number is invalid.";
		break;
		case "222":
		return "This error code applies only to merchants on FDC Omaha. Duplicate auth request (from INAS).";
		break;
		case "223":
		return "This error code applies only to merchants on FDC Omaha. This transaction experienced an unspecified error.";
		break;
		case "224":
		return "This error code applies only to merchants on FDC Omaha. Please re-enter the transaction.";
		break;
		case "243":
		return "The combination of values submitted for x_recurring_billing and x_echeck_type is not allowed.";
		break;
		case "244":
		return "The combination of values submitted for x_bank_acct_type and x_echeck_type is not allowed.";
		break;
		case "245":
		return "The value submitted for x_echeck_type is not allowed when using the payment gateway hosted payment form.";
		break;
		case "246":
		return "The merchants payment gateway account is not enabled to submit the eCheck.Net type.";
		break;
		case "247":
		return "The combination of values submitted for x_type and x_echeck_type is not allowed.";
		break;
		case "250":
		return "This transaction was submitted from a blocked IP address.";
		break;
		case "251":
		return "The transaction was declined as a result of triggering a Fraud Detection Suite filter.";
		break;
		case "252":
		return "The transaction was accepted, but is being held for merchant review. The merchant may customize the customer response in the Merchant Interface.";
		break;
		case "253":
		return "The transaction was accepted and was authorized, but is being held for merchant review. The merchant may customize the customer response in the Merchant Interface.";
		break;
		case "254":
		return "The transaction was declined after manual review.";
		break;
		case "261":
		return "The transaction experienced an error during sensitive data encryption and was not processed. Please try again.";
		break;
		case "270":
		return "A value submitted in x_line_item for the item referenced is invalid.";
		break;
		case "271":
		return "The number of line items submitted exceeds the allowed maximum of 30.";
		break;
		case "271":
		return "The number of line items submitted exceeds the allowed maximum of 30.";
		break;
		case "288":
		return "The merchant has not indicated participation in any Cardholder Authentication Programs in the Merchant Interface.";
		break;
		case "289":
		return "Your credit card processing service does not yet accept zero dollar authorizations for Visa credit cards. You can find your credit card processor listed on your merchant profile.";
		break;
		case "290":
		return "When submitting authorization requests for Visa, the address and zip code fields must be entered.";
		break;
		case "295":
		return "The merchant must have partial authorization enabled in the merchant interface in order to receive this error.";
		break;
		case "296":
		return "";
		break;
		case "297":
		return "";
		break;
		case "300":
		return "Invalid x_device_id value";
		break;
		case "301":
		return "Invalid x_device_batch_id value";
		break;
		case "302":
		return "Invalid x_reversal value";
		break;
		case "303":
		return "The current device batch must be closed manually from the POS device.";
		break;
		case "304":
		return "The original transaction has been settled and cannot be reversed.";
		break;
		case "305":
		return "This merchant is configured for auto-close and cannot manually close batches.";
		break;
		case "306":
		return "The batch is already closed.";
		break;
		case "307":
		return "The reversal was processed successfully.";
		break;
		case "308":
		return "The transaction submitted for reversal was not found.";
		break;
		case "309":
		return "The device has been disabled.";
		break;
		case "310":
		return "This transaction has already been voided.";
		break;
		case "311":
		return "This transaction has already been captured.";
		break;
		case "315":
		return "This is a processor-issued decline.";
		break;
		case "316":
		return "This is a processor-issued decline.";
		break;
		case "317":
		return "This is a processor-issued decline.";
		break;
		case "318":
		return "This is a processor-issued decline.";
		break;
		case "319":
		return "This is a processor-issued decline.";
		break;
	
	} // end switch
	
} // end function

   
    
    
?>