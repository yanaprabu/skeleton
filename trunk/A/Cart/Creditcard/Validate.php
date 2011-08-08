<?php
/**
 * Validate.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Cart_Creditcard_Validate
 * 
 * Validate credit card numbers using Luhn formula
 * 
 * @package A_Cart
 */
class A_Cart_Creditcard_Validate
{

	protected $number = '';
	protected $type = '';
	
	public function __construct($number='', $type='')
	{
		$this->number = $this->filterNumber($number);
		$this->type = $type;
	}
	
	public function filterNumber($number='')
	{
		if (!$number) {
			$number = $this->number;
		}
		$n = preg_replace('/[^0-9]/', '', $number);
		if (!$number) {
			$this->number = $n;
		}
		return $n;
	}
	
	public function isValidNumber($number='')
	{
		if (!$number) {
			$number = $this->number;
		} else {
			$number = $this->filterNumber($number);
		}
		
		//  The Luhn formula works right to left, so reverse the number.
		$number = strrev($number);
	
		$checksum = 0;
		$n = strlen ($number);
		for ($i = 0; $i < $n; $i++) {
			$digit = substr($number, $i, 1);
			
			// if it's an odd digit, double it
			$j = $i / 2;
			if ($j != floor($j)) {
				$digit *= 2;
				
				// if the result is two digits, add them
				if (strlen($digit) == 2) {
					$digit = substr($digit, 0, 1) + substr($digit, 1, 1);
				}
			}
			
			// add the current digit, doubled and added if applicable, to the total
			$checksum += $digit;
		}
		
		// checksum must be a multiple of 10
		if ($checksum && ! ($checksum % 10)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getType($number='')
	{
		if (!$number) {
			$number = $this->number;
		}
	
		$type = '';
		if (ereg('^5[1-5].{14}$', $number)) {
		     $type = 'mc';
	 	} elseif (ereg('^4.{15}$|^4.{12}$', $number)) {
			$type = 'visa';
	  	} elseif (ereg('^3[47].{13}$', $number)) {
			$type = 'amex';
	  	} elseif (ereg('^6011.{12}$', $number)) {
			$type = 'disc';
	  	} elseif (ereg('^30[0-5].{11}$|^3[68].{12}$', $number)) {
			$type = 'dc';
	  	} elseif (ereg('^3.{15}$|^2131|1800.{11}$', $number)) {
			$type = 'jcb';
	  	}
	
		if (!$number) {
			$this->type = $type;
		}
	
		return $type;
	}
	
	public function isValidType($type='', $number='')
	{
	 	if (!$type) {
			$type = $this->type;
		}
	 	if (!$number) {
			$number = $this->number;
		}
		$result = 0;
		switch ($type) {
		case 'mc':
			$result = ereg('^5[1-5].{14}$', $number);
			break;
		case 'visa':
			$result = ereg('^4.{15}$|^4.{12}$', $number);
			break;
		case 'amex':
			$result = ereg('^3[47].{13}$', $number);
			break;
		case 'disc':
			$result = ereg('^6011.{12}$', $number);
			break;
		case 'dc':
			$result = ereg('^30[0-5].{11}$|^3[68].{12}$', $number);
			break;
		case 'jcb':
			$result = ereg('^3.{15}$|^2131|1800.{11}$', $number);
			break;
		}
	
		return $result;
	}
	
	public function isValidDate($month='', $year='')
	{
		$date = "$year$month";
		if (strlen($date) == 4) {
			$current_date = date('ym');
		} else {
			$current_date = date('Ym');
		}
		if ($date >= $current_date) {
			return true;
		}
		return false;
	}

}
