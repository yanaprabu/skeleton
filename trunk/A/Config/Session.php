<?php
/**
 * Session.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Config_Session
 *
 * Support storing configuration data in a PHP session.  Config data is loaded from multiple sources if session not in session
 *
 * @package A_Config
 */
class A_Config_Session
{
	protected $session;
	protected $config;

	protected function __construct($session, $config=array())
	{
		$this->session = $session;
		$this->config = $config;
	}

}
