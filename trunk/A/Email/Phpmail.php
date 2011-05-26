<?php
/**
 * Phpmail.php
 * 
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Email_Phpmail
 * 
 * Provides email functionality through PHP's built-in mail() function.  To be used in composition with A_Email.
 */
class A_Email_Phpmail
{
	protected $error = 0;
	
	/**
	 * Sends an email with the given settings
	 * 
	 * @param string $to
	 * @param string $subject
	 * @param string $message
	 * @param string $headers
	 * @return true upon success
	 */
	public function send($to='', $subject='', $message='', $headers='')
	{
		$this->error = mail($to, $subject, $message, $headers);
		return $this->error;
	}
	
	/**
	 * Produces a textual error message
	 * 
	 * @return error message
	 */
	public function getErrorMsg()
	{
		return $this->error ? "Send error {$this->error} using mail. " : '';
	}

}