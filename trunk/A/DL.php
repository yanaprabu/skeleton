<?php
/**
 * DL.php
 *
 * @package  A
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_DL
 *
 * Encapsulate a path, class, and method into an objectZ
 */
class A_DL {
	public $dir = '';
	public $class = '';
	public $method = '';
	public $args = null;
	public $instance = null;

	public function __construct($dir, $class, $method, $args=null) {
		$this->dir = $dir;
		$this->class = $class;
		$this->method = $method;
		$this->args = $args;
	}

	public function run($locator) {
		if ($this->method) {
			$method = $this->method;

			if (! $this->instance && $locator->loadClass($this->class, $this->dir)) {
				$this->instance = new $this->class($locator);
			}

			if ($this->instance && method_exists($this->instance, $method)) {
				return $this->instance->$method($locator);
			}
		}
	}

}

class A_DLInstance extends A_DL {

	public function __construct($object, $method) {
		$this->instance = $object;
		$this->method = $method;
	}

}
