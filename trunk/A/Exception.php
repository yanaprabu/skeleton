<?php
/**
 * Exception.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Exception
 *
 * Instantiation of exception objects
 *
 * @package A
 */
class A_Exception
{

	public static function getInstance($class_or_obj, $message, $code=0)
	{
		$obj = null;
		if (is_string($class_or_obj)) {
			if (! class_exists($class_or_obj)) {
				$file_name = str_replace('_', '/', $class_or_obj) . '.php';
				include $file_name;
			}
			if (class_exists($class_or_obj)) {
				$obj = new $class_or_obj($message, $code);
			}
		} elseif ($class_or_obj instanceof Exception) {
			return $class_or_obj;
		}
		return $obj;
	}

}
