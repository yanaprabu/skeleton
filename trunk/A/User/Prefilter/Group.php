<?php
/**
 * Group.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_User_Prefilter_Group
 * 
 * Front Controller pre-filter for group based access control.
 * 
 * @package A_User
 */
class A_User_Prefilter_Group
{

	protected $session;
	protected $forward = array('','login','');
	protected $method = '_requireGroups';
	protected $field = '';
	
	function __construct($session, $forward='', $method='', $field='')
	{
		if ($session) {
			$this->session = $session;
		} else {
			$this->session = new A_Session();
		}
		if ($forward) {
			$this->forward = $forward;
		}
		if ($method) {
			$this->method = $method;
		}
		if ($field) {
			$this->field = $field;
		}
	}
	
	function setForward($forward)
	{
		$this->forward = $forward;
	}
	
	function setPreMethod($method)
	{
		$this->method = $method;
	}
	
	function setField($field)
	{
		$this->field = $field;
	}
	
	function run($controller)
	{
		if (method_exists($controller, $this->method)) {
			if (is_a($this->session, 'A_User_Session')) {
				$user = $this->session;
			} else {
				$this->session->start();
				$user = new A_User_Session($this->session);
			}
			$groups = $controller->{$this->method}();
			$access = new A_User_Rule_Ingroup($groups, 'Access Denied.');
			if ($this->field) {
				$access->setField($this->field);		// change default from 'access'
			}
			if (!$access->isValid($user)) {
				if ($this->forward) {
					return $this->forward;
				}
			}
		}
	}

}
