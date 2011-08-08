<?php
/**
 * Captcha.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Rule_Captcha
 * 
 * Rule to check for captcha value.  Class acts as both a Rule for Validator and Renderer for View
 * 
 * @package A_Rule
 */
class A_Rule_Captcha extends A_Rule_Base
{

	const ERROR = 'A_Rule_Captcha';
	
	protected $field;
	protected $errorMsg;
	protected $charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	protected $length = 5;
	protected $base_path = '';
	protected $script_name = 'captcha_image.php';
	protected $params = array(
		'field' => '', 
		'errorMsg' => '', 
		'renderer' => null, 
		'session' => null, 
		'sessionkey' => 'A_Rule_Captcha', 
		'optional' => false
	);
	
/*
	public function __construct($field, $errorMsg, $renderer, $session, $sessionkey='') {
*/
	
	public function setCharset($value)
	{
		return $this->charset = $value;
		return $this;
	}
	
	public function setLength($value)
	{
		return $this->length = $value;
		return $this;
	}
	
	public function setBasePath($value)
	{
		return $this->base_path = $value;
		return $this;
	}
	
	public function setScriptName($value)
	{
		return $this->script_name = $value;
		return $this;
	}
	
	function validate()
	{
		return $this->getValue($this->params['field']) == $this->getCode();
	}
	
	public function getParameter()
	{
		return $this->params['field'];
	}
	
	public function getSessionKey()
	{
		return strlen($this->params['sessionkey']) > 0 ? $this->params['sessionkey'] : __CLASS__;
	}
	
	public function generateCode($length=0)
	{
		if ($length > 0) {
			$this->length = $length;
		}
		$code = substr(str_shuffle($this->charset), 0, $this->length);
		$this->params['session']->set($this->getSessionKey(),  $code);
		return $this;
	}
	
	public function getCode()
	{
		$code = $this->params['session']->get($this->getSessionKey());
		if ($code == '') {
			$this->generateCode();
		}
		return $this->params['session']->get($this->getSessionKey());
	}
	
	public function render()
	{
		if ($this->params['renderer']) {
			$this->params['renderer']->set('url', $this->base_path . $this->script_name);
			return $this->params['renderer']->render();
		} else {
			return "<img src=\"{$this->base_path}{$this->script_name}\"/>";
		}
	}

}
