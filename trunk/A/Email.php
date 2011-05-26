<?php
/**
 * Email.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Email
 *
 * Basic simple and multipart email functionality
 */
class A_Email
{
	protected $connection;
	protected $subject = '';
	protected $message = '';
	protected $headers = '';
	protected $extra_headers = '';
	protected $to;
	protected $from='';
	protected $replyto='';
	protected $cc = '';
	protected $bcc = '';
	protected $errorMsg = '';
	protected $mailer;
	
	/**
	 *  
	 **/
	public function __construct($connection=null, $mailer='')
	{
		if ($connection == null) {
			$this->connection = new A_Email_Phpmail();
		} else {
			$this->connection = $connection;
		}
		$this->mailer = $mailer ? $mailer : 'A_Email_Multipart';
	}
	
	public function setSubject($value)
	{
		$this->subject = $value;
		return $this;
	}
	
	public function setMessage($value)
	{
		$this->message = $value;
		return $this;
	}
	
	public function setTo($address, $name='')
	{
		$this->to = $name ? $this->addressNamed($address, $name) : $address;
		return $this;
	}
	
	public function setFrom($address, $name='')
	{
		$this->from = $name ? $this->addressNamed($address, $name) : $address;
		return $this;
	}
	
	public function setReplyto($address, $name='')
	{
		$this->replyto = $name ? $this->addressNamed($address, $name) : $address;
		return $this;
	}
	
	public function setCC($address, $name='')
	{
		$this->cc = $name ? $this->addressNamed($address, $name) : $address;
		return $this;
	}
	
	public function setBCC($address, $name='')
	{
		$this->bcc = $name ? $this->addressNamed($address, $name) : $address;
		return $this;
	}
	
	public function addHeaders($value)
	{
		if (substr($value, -1, 2) == "\r\n") {
			$value .= "\r\n";
		}
		$this->extra_headers[] = $value;
		return $this;
	}
	
	public function send($from='', $to='', $subject='', $message='')
	{
		if ($from) $this->from = $from;
		if ($to) $this->to = $to;
		if ($subject) $this->subject = $subject;
		if ($message) $this->message = $message;
	
		if($this->to && $this->from && $this->message){
			$this->buildHeaders();
			if ($this->connection->send($this->to, $this->subject, $this->message, $this->headers) == false) {
				$this->errorMsg = $this->connection->getErrorMsg();
			} else {
				$this->errorMsg = '';
			}
		} else {
			$this->errorMsg = 'No to, from, or message. ';
		}
		return $this->errorMsg;
	}
	
	/**
	 *  build routing part of email address
	 **/
	public function buildHeaders()
	{ 
		$headers = array();
		if($this->from){
			$headers[] = 'From: ' . $this->from;
		}
		if($this->replyto){
			$headers[] = 'Reply-To: ' . $this->replyto;
		}
		if($this->cc){
			$headers[] = 'Cc: ' . $this->cc;
		}
		if($this->bcc){
			$headers[] = 'Bcc: ' . $this->bcc;
		}
		if($this->mailer){
			$headers[] = 'X-Mailer: ' . $this->mailer;
		}
		if($this->extra_headers){
			$headers[] = implode('', $this->extra_headers);
		}
		$this->headers = implode("\r\n", $headers);
	}
	
	/**
	 *  build named email address in form: "Name" <email@email.com>
	 **/
	public function addressNamed($address, $name)
	{ 
		if(empty($name)){
			$email = $address; 
		}else{
			$email = '"' . $name . '" <' . $address . '>'; 
		}
		
		return $email; 
	} 
	
	/**
	 *  
	 **/
	public function addressValidateEreg($address)
	{ 
		if ($address) {
			return (ereg ('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+' . '@' . '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address) ); 
		} else {
			return 0;
		}
	} 

}

