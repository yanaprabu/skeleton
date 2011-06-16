<?php
/**
 * Doctype.php
 *
 * @package  A_Html
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Html_Doctype
 * 
 * Generate HTML doctype tag
 */
class A_Html_Doctype
{

	const HTML_4_01_STRICT = 1;
	const HTML_4_01_TRANSITIONAL = 2;
	const HTML_4_01_FRAMESET = 3;
	const XHTML_1_0_STRICT = 4;
	const XHTML_1_0_TRANSITIONAL = 5;	
	const XHTML_1_0_FRAMESET = 6;
	const XHTML_1_1 = 7; 
	const HTML_5 = 8; 
	
	protected $_config = array('doctype' => '');
	
	public function __construct($doctype='')
	{
		$this->setDoctype($doctype);
	}
	
	/*
	* name=string, value=string or renderer
	*/
	public function config($config)
	{
		if (isset($config['doctype'])) {
			$this->setDoctype($config['doctype']);
		}
		return $this;
	}
	
	public function setDoctype($doctype='')
	{
		$this->_config['doctype'] = $doctype;
		return $this;
	}
	
	/*
	* name=string, value=string or renderer
	*/
	public function render($doctype=null)
	{
		$doctypes = array(
			self::HTML_5 => '<!DOCTYPE html>',
			self::HTML_4_01_STRICT => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01//EN\"\n\"http://www.w3.org/TR/html4/strict.dtd\">",
			self::HTML_4_01_TRANSITIONAL => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\"\n\"http://www.w3.org/TR/html4/loose.dtd\">",
			self::HTML_4_01_FRAMESET => "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Frameset//EN\"\n\"http://www.w3.org/TR/html4/frameset.dtd\">",
			self::XHTML_1_0_STRICT => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">",
			self::XHTML_1_0_TRANSITIONAL => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">",	
			self::XHTML_1_0_FRAMESET => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\"\n\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">",
			self::XHTML_1_1 => "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n\"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">",
		);
		if (($doctype === null) && isset($this->_config['doctype'])) {
			$doctype = $this->_config['doctype'];
		}
		// allow using string names of constants
		if ($doctype && !is_integer($doctype)) {
			$doctype = constant("A_Html_Doctype::$doctype");
		}
		if (isset($doctypes[$doctype])) {
			return $doctypes[$doctype] . "\n";
		}
		return '';
	}

}
