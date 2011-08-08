<?php
/**
 * Phpmail.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Email_Phpmail
 *
 * Connection to default PHP mail() function
 * 
 * @package A_Email
 */
class A_Email_Phpmail
{

	protected $error = 0;
	
	public function connect()
	{
		return true;
	}
	
	public function disconnect()
	{
		return true;
	}
	
	public function send($to='', $subject='', $message='', $headers='')
	{
		$this->error = mail($to, $subject, $message, $headers);
		return $this->error;
	}
	
	public function getErrorMsg()
	{
		return $this->error ? "Send error {$this->error} using mail. " : '';
	}

}
