<?php
/**
 * Flash.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Controller_Helper_Flash
 *
 * Special helper provides flash style session variables
 *
 * @package A_Controller
 */
class A_Controller_Helper_Flash
{

	protected $locator;
	protected $session;
	protected $get_pos = 0;
	protected $set_pos = 0;

	public function __construct($locator)
	{
		$this->locator = $locator;
		if ($locator) {
			$this->session = $locator->get('Session', 'A_Session', null, __CLASS__);
		}
	}

	public function set($name, $value=null)
	{
		// only one parameter pushes on stack with integer indexes
		if ($value === null) {
			$value = $name;
			$name = $this->set_pos++;
		}
		$this->session->set($name, $value, 1);
		return $this;
	}

	public function get($name)
	{
		$value = $this->session->get($name);
		return $value;
	}

	public function now($name, $value)
	{
		$this->session->set($name, $value, 0);
		return $this;
	}

	public function keep($name=null)
	{
		$this->session->expire($name, 1);
		return $this;
	}

	public function discard($name=null)
	{
		$this->session->expire($name, 0);
		return $this;
	}

	public function escape($name, $escape_quote_style=null, $character_set=null)
	{
		return htmlspecialchars($this->get($name), $escape_quote_style, $character_set);
	}

	function __toString()
	{
		return $this->get($this->get_pos++);
	}

}
