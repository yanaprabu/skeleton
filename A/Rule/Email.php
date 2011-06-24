<?php
/**
 * Email.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Email
 * 
 * Rule to check if value is a valid email address, and that the domain exists
 */
class A_Rule_Email extends A_Rule_Base
{

	const ERROR = 'A_Rule_Email';
	
	protected $check_dns = false;

	protected function validate()
	{
		/*
		 $user      = '[a-zA-Z0-9_\-\.\+\^!#\$%&*+\/\=\?\|\{\}~\']+';
		 $doIsValid = '(?:[a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9]\.?)+';
		 $ipv4      = '[0-9]{1,3}(\.[0-9]{1,3}){3}';
		 $ipv6      = '[0-9a-fA-F]{1,4}(\:[0-9a-fA-F]{1,4}){7}';

		 return (preg_match("/^$user@($doIsValid|(\[($ipv4|$ipv6)\]))$/", $this->getValue()));
		 */
		
		$email = $this->getValue();
		/*
		 Validate an email address.
		 Provide email address (raw input)
		 Returns true if the email address has the email
		 address format and the domain exists.
		 */
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex) {
			$isValid = false;
		} else {
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64) {
				// local part length exceeded
				$isValid = false;
			} else if ($domainLen < 1 || $domainLen > 255) {
				// domain part length exceeded
				$isValid = false;
			} else if ($local[0] == '.' || $local[$localLen-1] == '.') {
				// local part starts or ends with '.'
				$isValid = false;
			} else if (preg_match('/\\.\\./', $local)) {
				// local part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) {
				// character not valid in domain part
				$isValid = false;
			} else if (preg_match('/\\.\\./', $domain)) {
				// domain part has two consecutive dots
				$isValid = false;
			} else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
			str_replace("\\\\","",$local))) {
				// character not valid in local part unless
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) {
					$isValid = false;
				}
			}
			if ($isValid && $this->check_dns && function_exists('checkdnsrr') && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) {
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}

}
