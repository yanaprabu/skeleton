<?php
/**
 * Collection class returned by Config readers
 *
 * @package A_Config
 */

class A_Config_Collection extends A_Collection {

	/**
	 * Pass configuration data registered by class name to an object's config() method
	 */
	protected function configure($obj) {
		$class = get_class($obj);
		if (($class !== false) && $this->has($class) && method_exists($obj, 'config')) {
			$obj->config($this->get($class));
		}
	}

}