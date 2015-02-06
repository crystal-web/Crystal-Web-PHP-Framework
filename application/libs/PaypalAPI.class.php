<?php
/**
* @title Connection
* @author Christophe BUFFET <developpeur@crystal-web.org> 
* @license Creative Commons By 
* @license http://creativecommons.org/licenses/by-nd/3.0/
* @description Sand box apparait encore ? 
 * https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_WPCustomizing
 * https://developer.paypal.com/webapps/developer/docs/classic/api/
*/
 
Class PaypalAPI extends ShopPaypalModel {
	private $preprocess = array();

	private $payPalVersion = '76.0';
	
	
	// Utilisateur API
	private $paypalApiUsername = '';
	// Mot de passe API
	private $paypalApiPassword = '';
	// Signature de l'API
	private $payPalApiSignature = '';
	// Site de l'API PayPal.
	private $payPalMode = '';
 
	private $payPalCurrencyCode = 'EUR';
	private $payPalLocaleCode = 'FR'; 

    public function setAPI($paypalApiUsername, $paypalApiPassword, $payPalApiSignature) {
        $this->paypalApiUsername = $paypalApiUsername;
        $this->paypalApiPassword = $paypalApiPassword;
        $this->payPalApiSignature = $payPalApiSignature;
    }


	public function setCurrencyCode($currencyCode) {
		$this->payPalCurrencyCode = urlencode($currencyCode);
	}
	
	public function setLocaleCode($localeCode) {
		$this->payPalLocaleCode = $localeCode;
	}
	
	public function setExpressCheckout($success = false, $cancel = false, $logo = false /* 190/60 */) {
		$paypalmode = ($this->payPalMode=='sandbox') ? '.sandbox' : NULL;
		$success = (!$success) ? Router::selfURL() : Router::url($success);
		$cancel = (!$cancel) ? Router::selfURL() : Router::url($cancel);
		
		$nvpStr_ = '&CURRENCYCODE='.$this->payPalCurrencyCode.
                '&PAYMENTACTION=Sale'.
                '&ALLOWNOTE=1' . 
				'&LOCALECODE=FR' . 
                '&RETURNURL='.urlencode( $success ).
                '&CANCELURL='.urlencode( $cancel );
				
        if ($logo) { $nvpStr_ .= '&LOGOIMG=' . urlencode($logo); }
		
		$ItemTotalPrice = 0;
		for($i=0;$i<count($this->preprocess);$i++) {
			$ItemTotalPrice += $this->preprocess[$i]['ItemTotalPrice'];
			$nvpStr_ .=
					'&L_PAYMENTREQUEST_0_QTY'.$i.'='. urlencode($this->preprocess[$i]['ItemQty']) .
					'&L_PAYMENTREQUEST_0_AMT'.$i.'='.urlencode($this->preprocess[$i]['ItemPrice']) .
					'&L_PAYMENTREQUEST_0_NAME'.$i.'='.urlencode($this->preprocess[$i]['ItemName']) .
					'&L_PAYMENTREQUEST_0_NUMBER'.$i.'='.urlencode($this->preprocess[$i]['ItemNumber']);
		}
		$nvpStr_ .=	'&PAYMENTREQUEST_0_CURRENCYCODE='.$this->payPalCurrencyCode .
					'&PAYMENTREQUEST_0_ITEMAMT='.urlencode($ItemTotalPrice) . 
				//	'&PAYMENTREQUEST_0_TAXAMT='.urlencode(7) . 
					'&PAYMENTREQUEST_0_AMT='.urlencode($ItemTotalPrice);

		
		$httpParsedResponseAr = $this->post('SetExpressCheckout', $nvpStr_);


		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			if (!$this->registerTransaction(7, $this->payPalCurrencyCode, $httpParsedResponseAr['TOKEN'], $httpParsedResponseAr['TIMESTAMP'], $ItemTotalPrice)) {
				throw new Exception("Error Processing Request");
			}
			return 'https://www'.$paypalmode.'.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token='.$httpParsedResponseAr["TOKEN"];
		} else {
            //Show error message
            throw new Exception('<b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]));
		}
	}

	public function doExpressCheckoutPayment($token, $payerId) {
		$token = urldecode($token);
		$nvpStr_ = '&TOKEN=' . urlencode($token) . 
			'&PAYERID=' . urlencode($payerId) . 
			'&PAYMENTREQUEST_0_CURRENCYCODE='. $this->payPalCurrencyCode . 
			'&PAYMENTACTION=Sale';
			
		$ItemTotalPrice = 0;
		for($i=0;$i<count($this->preprocess);$i++) {
			$ItemTotalPrice += $this->preprocess[$i]['ItemTotalPrice'];
		}
		
		$nvpStr_ .= '&PAYMENTREQUEST_0_AMT=' . urlencode($ItemTotalPrice);
		$payrespon = $this->post('DoExpressCheckoutPayment', $nvpStr_);
		return $this->updateTransaction($token, $payrespon);
	} 

	public function preprocess($ItemName, $ItemPrice, $ItemNumber, $ItemQty) {
		
		$this->preprocess[] = array(
			'ItemName' => $ItemName,
			'ItemPrice' => $ItemPrice,
			'ItemNumber' => $ItemNumber,
			'ItemQty' => $ItemQty,
			'ItemTotalPrice' => ($ItemPrice*$ItemQty)
			);
		return $this;
	}


	private function post($methodName_, $nvpStr_) {
		$paypalmode = ($this->payPalMode=='sandbox') ? '.sandbox' : NULL;
		$API_Endpoint = "https://api-3t".$paypalmode.".paypal.com/nvp";
		$version = urlencode($this->payPalVersion);
		
		// Set up your API credentials, PayPal end point, and API version.
		$API_UserName = urlencode($this->paypalApiUsername);
		$API_Password = urlencode($this->paypalApiPassword);
		$API_Signature = urlencode($this->payPalApiSignature);
		
		// Set the curl parameters.
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		// Turn off the server and peer verification (TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		 
		// Set the API operation, version, and API signature in the request.
		$nvpreq = "PAGESTYLE=ImagineYourCraft&METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";
		 
		// Set the request as a POST FIELD for curl.
		curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);
		
		// Get response from the server.
		$httpResponse = curl_exec($ch);
		
		if(!$httpResponse) {
		   throw new Exception("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
		}
		
		// Extract the response details.
		$httpResponseAr = explode("&", $httpResponse);
		
		$httpParsedResponseAr = array();
		foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value); 
			if(sizeof($tmpAr) > 1) {
				$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
			}
		}
	
		if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
			throw new Exception('<b>Error : </b>'.'Invalid HTTP Response for POST request(' . $nvpreq. ') to ' . $API_Endpoint . '.');
		}
		
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) {
			return $httpParsedResponseAr;
		} else {
			throw new Exception('<b>Error : </b>'.urldecode($httpParsedResponseAr["L_LONGMESSAGE0"]), $httpParsedResponseAr['L_ERRORCODE0']);
        }
		return $httpParsedResponseAr;
	}

}
