<?php
/**
 * Captcha.php
 *
 * @package  A_Rule
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Rule_Captcha
 * 
 * Rule to check for captcha value.  Class acts as both a Rule for Validator and Renderer for View
 */
class A_Rule_Captcha extends A_Rule_Base {
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
	
	public function __construct($field, $renderer, $session, $sessionkey='', $errorMsg='', $optional=false)
	{
		$this->params['renderer'] = $renderer;
		$this->params['session'] = $session;
		$this->params['sessionKey'] = $sessionKey;
		parent::__construct($field, $errorMsg, $optional);
	}
	
    public function setCharset($value) {
		return $this->charset = $value;
		return $this;
    }

    public function setLength($value) {
		return $this->length = $value;
		return $this;
    }

    public function setBasePath($value) {
		return $this->base_path = $value;
		return $this;
    }

    public function setScriptName($value) {
		return $this->script_name = $value;
		return $this;
    }

    function validate() {
		return $this->getValue($this->params['field']) == $this->getCode();
	}
	
    public function getParameter() {
		return $this->params['field'];
    }

	public function getSessionKey() {
		return strlen($this->params['sessionkey']) > 0 ? $this->params['sessionkey'] : __CLASS__;
	}
	
    public function generateCode($length=0) {
		if ($length > 0) {
			$this->length = $length;
		}
		$code = substr(str_shuffle($this->charset), 0, $this->length);
		$this->params['session']->set($this->getSessionKey(),  $code);
		return $this;
	}
	
	public function getCode(){
		$code = $this->params['session']->get($this->getSessionKey());
		if ($code == '') {
			$this->generateCode();
		}
		return $this->params['session']->get($this->getSessionKey());
	}
	
    public function render() {
		if ($this->params['renderer']) {
			$this->params['renderer']->set('url', $this->base_path . $this->script_name);
			return $this->params['renderer']->render();
		} else {
			return "<img src=\"{$this->base_path}{$this->script_name}\"/>";
    	}
    }

}

/**
 * Generate an image using GD for captcha
 * 
 * @package A_Rule
 */
class A_Rule_Captcha_Image {
	protected $captcha;
	protected $length;

	public function __construct($captcha) {
		$this->captcha = $captcha;
	}
	
	public function out(){
		header("Content-type: image/png");
		$width = 75;
		$height = 25;
		$num_lines = 10;
		$im = imagecreate($width, $height);
		if ($im) {
			$bg_color = imagecolorallocate($im, 255, 255, 255);
			imagefill($im, 0, 0, $bg_color);
			$text_color = imagecolorallocate($im, 0, 0, 0);
			for( $i=0; $i<$num_lines; $i++ ) {
				imageline($im, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $text_color);
			}
			imagestring($im, 5, 12, 5,  $this->captcha->getCode(), $text_color);
			imagepng($im);
			imagedestroy($im);
		} else {
			return '';
		}
	}
}
