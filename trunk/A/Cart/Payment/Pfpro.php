<?php
/**
 * PayflowPro pfpro (credit card processsing) class library
 *
 * @package A_Cart
 */

define('A_CART_PAYMENT_PFPRO_SERVER_LIVE', 1);
define('A_CART_PAYMENT_PFPRO_SERVER_TEST', 2);
define('A_CART_PAYMENT_PFPRO_SERVER_NONE', 3);
define('A_CART_PAYMENT_PFPRO_TRXTYPE_SALE', 'S');
define('A_CART_PAYMENT_PFPRO_TRXTYPE_AUTHORIZATION', 'A');


class A_Cart_Payment_Pfpro
{	protected $certpath = '/usr/local/verisign/payflowpro/linux/certs/';	protected $server;	protected $serverlist;	protected $servermode;	protected $transaction;	protected $response = null;	protected $errmsg;
	
	public function __construct($user='', $passwd='', $partner='', $mode=A_CART_PAYMENT_PFPRO_SERVER_LIVE)
	{
		putenv('PFPRO_CERT_PATH=' . $this->certpath);
		$this->serverlist = array(
			A_CART_PAYMENT_PFPRO_SERVER_LIVE=>'payflow.verisign.com',
			A_CART_PAYMENT_PFPRO_SERVER_TEST=>'test-payflow.verisign.com',
			A_CART_PAYMENT_PFPRO_SERVER_NONE=>'',
		);
		$this->transaction = array(
			'USER' => $user,
			'PWD' => $passwd,
			'PARTNER' => $partner,
			'TRXTYPE' => A_CART_PAYMENT_PFPRO_TRXTYPE_SALE,
			'TENDER' => 'C',
			'AMT' => 0,
			'ACCT' => '',
			'EXPDATE' => '',
			'NAME' => '',
			'STREET' => '',
			'ZIP' => '',
			);
	
		$this->setServerMode($mode);
		
		pfpro_init();
	
	}
	
	public function setServer($value) {
		$this->server = $value;
		return $this;
	}
	
	public function setServerMode($mode=A_CART_PAYMENT_PFPRO_SERVER_LIVE) {
		switch ($mode) {
		case A_CART_PAYMENT_PFPRO_SERVER_TEST:
			$this->server = $this->serverlist[A_CART_PAYMENT_PFPRO_SERVER_TEST];
			break;
		case A_CART_PAYMENT_PFPRO_SERVER_NONE:
			$this->server = $this->serverlist[A_CART_PAYMENT_PFPRO_SERVER_NONE];
			break;
		case A_CART_PAYMENT_PFPRO_SERVER_LIVE:
		default:
			$this->server = $this->serverlist[A_CART_PAYMENT_PFPRO_SERVER_LIVE];
		}
		$this->servermode = $mode;
		return $this;
	}
	
	public function setUser($value) {
		$this->transaction['USER'] = $value;
		return $this;
	}
	
	public function setPassword($value) {
		$this->transaction['PWD'] = $value;
		return $this;
	}
	
	public function setPartner($value) {
		$this->transaction['PARTNER'] = $value;
		return $this;
	}
	
	public function setTransactionType($value) {
		$this->transaction['TRXTYPE'] = $value;
		return $this;
	}
	
	public function setAmount($value) {
		$this->transaction['AMT'] = $value;
		return $this;
	}
	
	public function setCardNumber($value) {
		$this->transaction['ACCT'] = $value;
		return $this;
	}
	
	public function setExpDate($month, $year) {
		if (strlen("$year") > 2) {
			$year = substr("$year", -2);
		}
		$this->transaction['EXPDATE'] = sprintf('%02d%02d', $month, $year);
		return $this;
	}
	
	public function setName($value) {
		$this->transaction['NAME'] = $value;
		return $this;
	}
	
	public function setStreet($value) {
		$this->transaction['STREET'] = $value;
		return $this;
	}
	
	public function setZip($value) {
		$this->transaction['ZIP'] = $value;
		return $this;
	}
	
	public function setComments($comment1='', $comment2='') {
		if ($comment1) {
			$this->transaction['COMMENT1'] = $comment1;
		}
		if ($comment2) {
			$this->transaction['COMMENT2'] = $comment2;
		}
		return $this;
	}
	
	public function getReference() {
		if ($this->response) {
			return $this->response['PNREF'];
		}
		return '';
	}
	
	public function getVersion() {
		return pfpro_version();
	}
	
	public function process() {
		
		if ($this->servermode == A_CART_PAYMENT_PFPRO_SERVER_NONE) {
			$this->response['RESULT'] = 0;
			$this->response['RESPMSG'] = 'Did not connect to credit card processor (A_CART_PAYMENT_PFPRO_SERVER_NONE). ';
		} else {
			$this->response = pfpro_process($this->transaction, $this->server);
		}
		return $this->response;
	}
	
	public function isError() {
		if ($this->response && ($this->response['RESULT'] == 0)) {
			return false;
		}
		return true;
	}
	
	public function getErrorMsg() {
		if ($this->response) {
			return $this->response['RESPMSG'];
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
			return $this->response['RESULT'];
		}
		return -1;
	}
	
	public function close() {
		pfpro_cleanup();
	}

} // end class A_Cart_Payment_PayflowPro
