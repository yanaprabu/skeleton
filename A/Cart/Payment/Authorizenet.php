<?php
/**
 * Authorizenet (credit card processsing) class library
 * 
 * @package A_Cart
 */

define('A_CART_PAYMENT_AUTHORIZENET_SERVER_LIVE', 1);
define('A_CART_PAYMENT_AUTHORIZENET_SERVER_TEST', 2);
define('A_CART_PAYMENT_AUTHORIZENET_SERVER_NONE', 3);
define('A_CART_PAYMENT_AUTHORIZENET_TRXTYPE_SALE', 'S');
define('A_CART_PAYMENT_AUTHORIZENET_TRXTYPE_AUTHORIZATION', 'A');


class A_Cart_Payment_Authorizenet
{	const SERVER_LIVE = 1;
	const SERVER_TEST = 2;
	const SERVER_NONE = 3;
	const TRXTYPE_SALE = 'S';
	const TRXTYPE_AUTHORIZATION = 'A';
	protected $server;	protected $serverlist;	protected $servermode;	protected $delimiter = '|';	protected $transaction;	protected $response = array();	// split on delimiters	protected $response_raw = '';	protected $errmsg;
	
	public function __construct($user='', $passwd='', $partner='', $mode=self::SERVER_LIVE)
	{
		$this->serverlist = array(
			self::SERVER_LIVE => 'https://secure.authorize.net/gateway/transact.dll',
			self::SERVER_TEST => 'https://test.authorize.net/gateway/transact.dll',
			self::SERVER_NONE => '',
		);
		$this->transaction = array(
				'x_login'				=> $user,
				'x_test_request'		=> 'TRUE',
				'x_version'				=> '3.1',
				'x_delim_char'			=> $this->delimiter,
				'x_delim_data'			=> 'TRUE',
				'x_type'				=> 'AUTH_CAPTURE',
				'x_method'				=> 'CC',
			 	'x_tran_key'			=> $passwd,
			 	'x_relay_response'		=> 'FALSE',
				'x_invoice_num'			=> '',
				'x_cust_id'				=> '',
				'x_card_num'			=> '',
				'x_exp_date'			=> '',
				'x_description'			=> '',
				'x_amount'				=> '',
				'x_first_name'			=> '',
				'x_last_name'			=> '',
				'x_company'				=> '',
				'x_address'				=> '',
				'x_city'				=> '',
				'x_state'				=> '',
				'x_country'				=> '',
				'x_phone'				=> '',
				'x_fax'					=> '',
				'x_email'				=> '',
				'x_ship_to_first_name'	=> '',
				'x_ship_to_last_name'	=> '',
				'x_ship_to_company'		=> '',
				'x_ship_to_address'		=> '',
				'x_ship_to_city'		=> '',
				'x_ship_to_state'		=> '',
				'x_ship_to_country'		=> '',
				'x_tax'					=> '',
				'x_tax_exempt'			=> '',
				'x_duty'				=> '',
				'x_freight'				=> '',
				'x_po_num'				=> '',
			);
	
		$this->setServerMode($mode);
	}
	
	public function setServer($value) {
		$this->server = $value;
		return $this;
	}
	
	public function setServerMode($mode=self::SERVER_LIVE) {
		switch ($mode) {
		case self::SERVER_TEST:
			$this->transaction['x_test_request'] = 'TRUE';
			$this->server = $this->serverlist[self::SERVER_LIVE];
			break;
		case self::SERVER_NONE:
			$this->transaction['x_test_request'] = 'TRUE';
			$this->server = $this->serverlist[self::SERVER_NONE];
			break;
		case self::SERVER_LIVE:
		default:
			$this->transaction['x_test_request'] = 'FALSE';
			$this->server = $this->serverlist[self::SERVER_LIVE];
		}
		$this->servermode = $mode;
		return $this;
	}
	
	public function set($name, $value) {
		if ($value !== null) {
			$this->transaction[$name] = $value;
		} else {
			unset($this->transaction[$name]);
		}
		return $this;
	}
	
	public function get($name) {
		return isset($this->transaction[$name]) ? $this->transaction[$name] : '';
	}
	
	public function setUser($value) {
		$this->transaction['x_login'] = $value;
		return $this;
	}
	
	public function setPassword($value) {
		$this->transaction['x_tran_key'] = $value;
		return $this;
	}
	
	public function setPartner($value) {
	#	$this->transaction['PARTNER'] = $value;
		return $this;
	}
	
	public function setTransactionType($value) {
		if (in_array($value, array('AUTH_CAPTURE', 'AUTH_ONLY', 'CAPTURE_ONLY', 'CREDIT', 'VOID', 'PRIOR_AUTH_CAPTURE'))) {
			$this->transaction['x_type'] = $value;
		}
		return $this;
	}
	
	public function setPaymentMethod($value) {
		if (in_array($value, array('CC', 'ECHECK'))) {
			$this->transaction['x_method'] = $value;
		}
		return $this;
	}
	
	public function setOrderNumber($value) {
		$this->transaction['x_invoice_num'] = $value;
		return $this;
	}
	
	public function setAmount($value) {
		$this->transaction['x_amount'] = $value;
		return $this;
	}
	
	public function setCardNumber($value) {
		$this->transaction['x_card_num'] = $value;
		return $this;
	}
	
	public function setExpDate($month, $year) {
		if (strlen("$year") > 2) {
			$year = substr("$year", -2);
		}
		$this->transaction['x_exp_date'] = sprintf('%02d%02d', $month, $year);
		return $this;
	}
	
	public function setName($value) {
		$names = explode(' ', $value);
		$this->transaction['x_last_name'] = array_pop($names);
		$this->transaction['x_first_name'] = array_shift($names);
		return $this;
	}
	
	public function setFirstName($value) {
		$this->transaction['x_first_name'] = $value;
		return $this;
	}
	
	public function setLastName($value) {
		$this->transaction['x_last_name'] = $value;
		return $this;
	}
	
	public function setStreet($value) {
		$this->transaction['x_address'] = $value;
		return $this;
	}
	
	public function setCity($value) {
		$this->transaction['x_city'] = $value;
		return $this;
	}
	
	public function setState($value) {
		$this->transaction['x_state'] = $value;
		return $this;
	}
	
	public function setZip($value) {
		$this->transaction['x_zip'] = $value;
		return $this;
	}
	
	public function setComments($comment1='', $comment2='') {
		$this->transaction['x_description'] = $comment1;
		return $this;
	}
	
	public function getReference() {
		if ($this->response) {
			return $this->response['PNREF'];
		}
		return '';
	}
	
	public function getVersion() {
		return $this->transaction['x_version'];
	}
	
	public function process() {
		
		if ($this->servermode == self::SERVER_NONE) {
			$this->response[0] = 0;
			$this->response[3] = 'Did not connect to credit card processor (self::SERVER_NONE). ';
		} else {
			
			$fields = '';
			foreach($this->transaction as $key => $value) {
				$fields .= "$key=" . urlencode( $value ) . "&";
			}
			
			$ch = curl_init($this->server); 
	
			curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
			curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
			### curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
	
			$this->response_raw = curl_exec($ch); //execute post and get results
			$this->response = explode($this->delimiter, $this->response_raw);
			curl_close ($ch);
		}
	#echo 'RESPONSE=<pre>' . print_r($this->response, 1) . '</pre>';
		return $this->isError();
	}
	
	public function isError() {
		if ($this->response && ($this->response[0] != 1)) {
			return true;
		}
		return false;
	}
	
	public function getErrorMsg() {
		if ($this->response) {
			if ($this->response[0] == 1) {
				return '';
			} else {
				return $this->response[3];
			}
		}
		return 'Could not connect to credit card processor. ';
	}
	
	/**
	 * depricated name for getErrorMsg()
	 */
	public function getMessage() {
		return $this->getErrorMsg();
	}
	
	public function getResult() {
		if ($this->response) {
			return $this->response[0];
		}
		return -1;
	}
	
	public function close() {
	}

}
