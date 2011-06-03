<?php
/**
 * Smartquotes.php
 *
 * @package  A_Filter
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Filter_Smartquotes
 * 
 * Convert smart quotes to standard quotes
 */
class A_Filter_Smartquotes extends A_Filter_Base {
	
	public function filter () {
		$smartQuotes = array(
			chr(145) => "'",
			chr(146) => "'",
			chr(147) => '"',
			chr(148) => '"',
			chr(151) => '-'
		);
	    return str_replace(array_keys($search), array_values($search), $this->getValue()); 
	}

}
