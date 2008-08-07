<?php
/**
 * PayPal (credit card processsing) class library
 *
 * @package A_Cart
 */


class A_Cart_Payment_Payflow
{
	const SERVER_LIVE = 1;
	const SERVER_TEST = 2;
	const SERVER_NONE = 3;
	const TRXTYPE_SALE = 'S';
	const TRXTYPE_AUTHORIZATION = 'A';

	protected $server;
	protected $serverlist;
	protected $servermode;
	protected $transaction;
	protected $transactionID = 0;
	protected $response = null;
	protected $fraud = 'NO';
	protected $infomsg = '';
	protected $errmsg = '';
	
	public function __construct($user='', $passwd='', $partner='', $mode=A_Cart_Payment_Payflow::SERVER_LIVE)
	{
		$this->serverlist = array(
			self::SERVER_LIVE=>'https://payflowpro.verisign.com',
			self::SERVER_TEST=>'https://pilot-payflowpro.verisign.com',		//'test-payflow.verisign.com',
			self::SERVER_NONE=>'',
		);
		$this->transaction = array(
			'USER' => $user,
			'VENDOR' => $user,
			'PWD' => $passwd,
			'PARTNER' => $partner,
			'TRXTYPE' => self::TRXTYPE_AUTHORIZATION,		// A is Authorization, S is Sale
			'TENDER' => 'C',			// C is Credit card, P is PayPal account
			'VERBOSITY' => 'MEDIUM',	// LOW is normalized, MEDIUM for processor's raw response
			'CURRENCY' => 'USD',
		
			'INVNUM' => '',
			'AMT' => 0.0,
			'ACCT' => '',
			'ACCTTYPE' => '',
			'EXPDATE' => '',
			'FIRSTNAME' => '',
			'LASTNAME' => '',
			'STREET' => '',
			'CITY' => '',
			'STATE' => '',
			'ZIP' => '',
			'COUNTRY' => 'US',
			'EMAIL' => '',
			'CLIENTIP' => $_SERVER['REMOTE_ADDR'],
			'COMMENT1' => '',
			'INVNUM' => '',
			'ORDERDESC' => '',
		);
	
		$this->setServerMode($mode);
		
	}
	
	public function setServer($value) {
		$this->server = $value;
	}
	
	public function setServerMode($mode=A_Cart_Payment_Payflow::SERVER_LIVE) {
		switch ($mode) {
		case self::SERVER_TEST:
			$this->server = $this->serverlist[self::SERVER_TEST];
			break;
		case self::SERVER_NONE:
			$this->server = $this->serverlist[self::SERVER_NONE];
			break;
		case self::SERVER_LIVE:
		default:
			$this->server = $this->serverlist[self::SERVER_LIVE];
		}
		$this->servermode = $mode;
#echo "setServerMode: server={$this->server}, servermode={$this->servermode}<br/>";
	}
	
	public function setFraud($value) {
		$this->fraud = ($value === true) || (strtoupper($value)=='YES') ? 'YES' : 'NO';
	}
	
	public function setUser($value) {
		$this->transaction['USER'] = $value;
	}
	
	public function setPassword($value) {
		$this->transaction['PWD'] = $value;
	}
	
	public function setPartner($value) {
		$this->transaction['PARTNER'] = $value;
	}
	
	public function setTransactionType($value) {
		$this->transaction['TRXTYPE'] = $value;
	}
	
	public function setTransactionID($value) {
		$this->transactionID = $value;
	}
	
	public function setCurrency($value) {
		$this->transaction['CURRENCY'] = $value;	// USD,GBP
	}
	
	public function setAmount($value) {
		$this->transaction['AMT'] = $value;
	}
	
	public function setOrderNumber($value) {
		$this->transaction['INVNUM'] = $value;
	}
	
	public function setCardNumber($value) {
		$this->transaction['ACCT'] = $value;
	}
	
	public function setCardType($value) {
		$this->transaction['ACCTTYPE'] = $value;
	}
	
	public function setExpDate($month, $year) {
		if (strlen($year) > 2) {
			$year = substr($year, -2);
		}
		$this->transaction['EXPDATE'] = sprintf('%02d%02d', $month, $year);
	}
	
	public function setCVV2($value) {
		$this->transaction['CVV2'] = $value;
	}
	
	public function setName($value) {
		$this->transaction['NAME'] = $value;
	}
	
	public function setFirstName($value) {
		$this->transaction['FIRSTNAME'] = $value;
	}
	
	public function setLastName($value) {
		$this->transaction['LASTNAME'] = $value;
	}
	
	public function setStreet($value) {
		$this->transaction['STREET'] = $value;
	}
	
	public function setCity($value) {
		$this->transaction['CITY'] = $value;
	}
	
	public function setState($value) {
		$this->transaction['STATE'] = $value;
	}
	
	public function setZip($value) {
		$this->transaction['ZIP'] = $value;
	}
	
	public function setCountry($value) {
		$this->transaction['COUNTRY'] = $value;
	}
	
	public function setEmail($value) {
		$this->transaction['EMAIL'] = $value;
	}

	public function setDescription($value) {
		$this->transaction['ORDERDESC'] = $value;
	}
	
	public function setComments($comment1='', $comment2='') {
		if ($comment1) {
			$this->transaction['COMMENT1'] = $comment1;
		}
		if ($comment2) {
			$this->transaction['COMMENT2'] = $comment2;
		}
	}
	
	public function getVersion() {
	}
	
	public function getResponse($key='') {
		if ($key) {
			return $this->response[$key];
		} else {
			return $this->response;
		}
	}
	
	public function getTransaction($key='') {
		if ($key) {
			return $this->transaction[$key];
		} else {
			return $this->transaction;
		}
	}
	
	public function getReference() {
		if ($this->response) {
			return $this->response['PNREF'];
		}
		return '';
	}
	
	public function getResponseMessage() {
		if ($this->response) {
			return $this->response['RESPMSG'];
		}
		return 'Could not connect to credit card processor. ';
	}
	
	public function getMessage() {
		return $this->errmsg;
	}
	
	public function getInformation() {
		return $this->infomsg;
	}
	
	public function getResult() {
		if (isset($this->response['RESULT'])) {
			return $this->response['RESULT'];
		}
		return -1;
	}
	
	public function process() {
		
		if ($this->servermode == self::SERVER_NONE) {
			$this->response['RESULT'] = 0;
			$this->response['RESPMSG'] = 'Did not connect to credit card processor (A_Cart_Payment_Payflow::SERVER_NONE). ';
		} else {
			$params = array();
			foreach ($this->transaction as $key => $value) {
				if ($value != '') {
					$params[] = "$key=$value";
				}
			}
			$data = implode('&', $params);
			$unique_id = $order_num;
	
			// post data and get results in this->response
			$this->processTransaction($data);
		}
		return $this->response;
	}
	
	/*
	 * Post transaction, get response and check for errors
	 */
	protected function processTransaction($data) {
		
		// Here's your custom headers; adjust appropriately for your setup:
		$headers[] = "Content-Type: text/namevalue"; //or text/xml if using XMLPay.
		// Here I set the server timeout value to 45, but notice below in the cURL section, I set the timeout
		// for cURL to 90 seconds.  You want to make sure the server timeout is less, then the connection.
		$headers[] = "X-VPS-Timeout: 45";
		$headers[] = "X-VPS-Request-ID: " . ($this->transactionID ? $this->transactionID : $this->generateID());
			
		// Optional Headers.  If used adjust as necessary.
		//$headers[] = "X-VPS-VIT-OS-Name: Linux";  		// Name of your OS
		//$headers[] = "X-VPS-VIT-OS-Version: RHEL 4";  	// OS Version
		//$headers[] = "X-VPS-VIT-Client-Type: PHP/cURL";  	// What you are using
		//$headers[] = "X-VPS-VIT-Client-Version: 0.01";  	// For your info
		//$headers[] = "X-VPS-VIT-Client-Architecture: x86";  	// For your info
		//$headers[] = "X-VPS-VIT-Integration-Product: PHPv4::cURL";  // For your info, would populate with application name
		//$headers[] = "X-VPS-VIT-Integration-Version: 0.01"; 	// Application version
	
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $this->server);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_HEADER, 1); 		// tells curl to include headers in response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 	// return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 90); 		// times out after 90 secs
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 	// this line makes it work under https
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 	//adding POST data
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); 	//verifies ssl certificate
		curl_setopt($ch, CURLOPT_FORBID_REUSE, true); 	//forces closure of connection when done 
		curl_setopt($ch, CURLOPT_POST, 1); //data sent as POST 
								
		// Try to submit the transaction up to 3 times with 5 second delay.  This can be used
		// in case of network issues.  The idea here is since you are posting via HTTPS there
		// could be general network issues, so try a few times before you tell customer there 
		// is an issue.
	
		$i=1;
		while ($i++ <= 3) {
	        $result = curl_exec($ch);
			$headers = curl_getinfo($ch);
	        //print_r($headers);
			//echo '<br>';
			//print_r($result);
			//echo '<br>';
			if ($headers['http_code'] != 200) {
				sleep(5);  // Let's wait 5 seconds to see if its a temporary network issue.
			} elseif ($headers['http_code'] == 200) {
				// we got a good response, drop out of loop.
				break;
			}
		}
		curl_close($ch);

		// In this example I am looking for a 200 response from the server prior to continuing with
		// processing the order.  You can use this or other methods to validate a response from the 
		// server and/or timeout issues due to network.
	    if ($headers['http_code'] == 200) {
			// skip past http headers to response data
			$result = strstr($result, "RESULT");
			// covert "p1=v1&p2=v2" to array('p1'=>'v1', 'p2'=>'v2')
			parse_str($result, $this->response);
			$this->checkResponse();
	    } else {
	    	$this->response['RESULT'] = -1;
			$this->errmsg = 'Connection Error.  Please contact Customer Support.';  // Generic error for all results not captured below.
		}
	}	 
	
	protected function checkResponse() {
	
		$result_code = $this->response['RESULT']; // get the result code to validate.
		$this->errmsg = 'General Error.  Please contact Customer Support.';  // Generic error for all results not captured below.
	
		// Part of accepting credit cards or PayPal is to determine what your business rules are.  Basically, what risk are you
		// willing to take, especially with credit cards.  The code below gives you an idea of how to check the results returned
		// so you can determine how to handle the transaction.
	
		// This is not an exhaustive list of failures or issues that could arise.  Review the list of Result Code's in the
		// Developer Guides and add logic as you deem necessary.
		// These responses are just an example of what you can do and how you handle the response received
		// from the bank/PayPal is dependent on your own business rules and needs.
	
		// Evaluate Result Code returned from PayPal.
		// Since you are posting via HTTPS you would not see any negative result codes as documented in the developer's guide.
		// This is due to the fact that negative result codes are generated from the SDK, not the server.
		if ($result_code == 1 || $result_code == 26) {	
			// This is just checking for invalid login credentials.  You normally would not display this type of message.
			// Result code 26 will be issued if you do not provide both the <vendor> and <user> fields.
			// Remember: <vendor> = your merchant (login id), <user> = <vendor> unless you created a separate <user> for Payflow Pro.
			// 
			// The other most common error with authentication is result code 1, user authentication failed.  This is usually
			// due to invalid account information or ip restriction on the account.  You can verify ip restriction by logging 
			// into Manager.  See Service Settings >> Allowed IP Addresses.  Lastly it could be you forgot the path "/transaction"
			// on the URL.
	        $this->errmsg = "Account configuration issue.  Please verify your login credentials."; 

		} elseif ($result_code== 0) {
			// Example of a message you might want to display with an approved transaction.
	        $this->errmsg = '';
	        $this->infomsg = "Your transaction was approved. ";
			// Even though the transaction was approved, you still might want to check for AVS or CVV2(CSC) prior to
			// accepting the order.  Do realize that credit cards are approved (charged) regardless of the AVS/CVV2 results.
			// Should you decline (void) the transaction, the card will still have a temporary charge (approval) on it.
	
			// Check AVS - Street/Zip
			// In the message below it shows what failed, ie street, zip or cvv2.  To prevent fraud, it is suggested
			// you only give a generic billing error message and not tell the card-holder what is actually wrong.  However,
			// that decision is yours.
			//
			// Also, it is totally up to you on if you accept only "Y" or allow "N" or "X".  You need to decide what
			// business logic and liability you want to accept with cards that either don't pass the check or where
			// the bank does not participate or return a result.  Remember, AVS is mostly used in the US but some foreign
			// banks do participate.
			
			// Remember, this just an example of what you might want to do.
	        if (isset($this->response['AVSADDR']) && ($this->response['AVSADDR'] != "Y")) {
				// Display message that transaction was not accepted.  At this time, you
				// could display message that information is incorrect and redirect user 
				// to re-enter STREET and ZIP information.  However, there should be some sort of
				// 3 strikes your out check.
				$this->errmsg = "Your billing (street) information does not match. Please re-enter."; 
				// Here you might want to put in code to flag or void the transaction depending on your needs.
            }
			if (isset($this->response['AVSZIP']) && ($this->response['AVSZIP'] != "Y")) {
				// Display message that transaction was not accepted.  At this time, you
				// could display message that information is incorrect and redirect user 
				// to re-enter STREET and ZIP information.  However, there should be some sort of
				// 3 strikes your out check.
				$this->errmsg = "Your billing (zip) information does not match. Please re-enter."; 
				// Here you might want to put in code to flag or void the transaction depending on your needs.
            }
			if (isset($this->response['CVV2MATCH']) && ($this->response['CVV2MATCH'] != "Y")) {
				   // Display message that transaction was not accepted.  At this time, you
				   // could display message that information is incorrect.  Normally, to prevent
				   // fraud you would not want to tell a customer that the 3/4 digit number on
				   // the credit card was invalid.
				$this->errmsg = "Your billing (cvv2) information does not match. Please re-enter."; 
				// Here you might want to put in code to flag or void the transaction depending on your needs.
            }
		} elseif ($result_code == 12) {
	        // Hard decline from bank.
	        $this->errmsg = "Your transaction was declined."; 
		} else if ($result_code == 13) {  
	        // Voice authorization required.
	        $this->errmsg = "Your Transaction is pending. Contact Customer Service to complete your order."; 
		} else if ($result_code == 23 || $result_code == 24) {
	        // Issue with credit card number or expiration date.
	        $this->errmsg = "Invalid credit card information. Please re-enter."; 
		}
	
	    // Using the Fraud Protection Service.
	    // This portion of code would be is you are using the Fraud Protection Service, this is for US merchants only.
	    if ($this->fraud = 'YES') {
	        if ($result_code == 125) {
	            // 125, 126 and 127 are Fraud Responses.
	            // Refer to the Payflow Pro Fraud Protection Services User's Guide or
				// Website Payments Pro Payflow Edition - Fraud Protection Services User's Guide.
	
				// 125 = Fraud Filters set to Decline. 
	            $this->errmsg = "Your Transaction has been declined. Contact Customer Service to place your order."; 
			} elseif ($result_code == 126) {
				// One of more filters were triggered.  Here you would check the fraud message returned if you 
				// want to validate data.  For example, you might have 3 filters set, but you'll allow 2 out of the 
				// 3 to consider this a valid transaction.  You would then send the request to the server to modify the
				// status of the transaction.  This outside the scope of this sample.  Refer to the Fraud Developer's Guide.
	            $this->errmsg = "Your Transaction is Under Review. We will notify you via e-mail if accepted.";
			} elseif ($result_code == 127) {
				// 127 = Issue with fraud service.  Manually, approve?
	            $this->errmsg = "Your Transaction is Under Review. We will notify you via e-mail if accepted."; 
			}
		} 
	    if(isset($this->response['DUPLICATE'])) {
			$this->response['RESULT'] = -1;
			$this->errmsg = 'This is a duplicate of your previous order. ';
		} elseif (isset($this->response['PPREF']) && ($this->response['PENDINGREASON']=='echeck')) {
			// PayPal transaction
			$this->infomsg = 'The payment is pending because it was made by an eCheck that has not yet cleared.';
		}
	}
		
	protected function generateID ($length=32) {
	    return substr(str_shuffle("1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	
	public function isError() {
		if ($this->response && ($this->response['RESULT'] == 0)) {
			return false;
		}
		return true;
	}
	
	public function close() {
	}

} // end class A_Cart_Payment_PayflowPro
