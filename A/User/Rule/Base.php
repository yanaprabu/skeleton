<?php
/**
 * Base.php
 *
 * @package  A_User
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_User_Rule_Base
 * 
 * Base class for A_User_Rule classes.
 */
abstract class A_User_Rule_Base
{

	protected $field;
	protected $forward;
	protected $user;
	protected $errorMsg = '';
	
	public function setForward($forward)
	{
		$this->forward = $forward;
		return $this;
	}
	
	public function setField($field)
	{
		$this->field = $field;
		return $this;
	}
	
	public function setUser($user)
	{
		$this->user = $user;
		return $this;
	}
	
	public function getUser($user=null)
	{
		return isset($user) ? $user : $this->user;
	}
	
	abstract public function isValid($user=null);
	
	/**
	 * Gets the error message that is to be returned if isValid fails
	 * 
	 * @return string Forward
	 */
	public function getErrorMsg()
	{
		return $this->errorMsg;
	}

}
