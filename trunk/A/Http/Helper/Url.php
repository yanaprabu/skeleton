<?php
/**
 * URL View Helper
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Http_Helper_Url
 *
 * A helper class for handling the url
 */
class A_Http_Helper_Url extends A_Http_Url {
	protected $locator;
	protected $protocol = 'http';
	protected $module = '';
	protected $controller = '';
	protected $action = '';
	
	public function __construct($locator) {
		$this->locator = $locator;
		$request = $locator->get('Request');
		if ($request) {
			$this->action = $request->get('action');
		}
	}

	public function render($page='') {
#echo "A_Http_Helper_Url::render()<br/>\n";
		return $this->protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
	}
}
