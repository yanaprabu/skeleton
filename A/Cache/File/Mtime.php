<?php
/**
 * Mtime.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Cache_File_Mtime
 *
 * @package A_Cache
 */
class A_Cache_File_Mtime
{

	protected $path = '';
	protected $timeout;

	/**
	 * Constructor
	 *
	 * @param string $path
	 * @param int $timeout
	 */
	public function __construct($path=null, $timeout=10)
	{
		if ($path) {
			$this->path = $path;
		}
		$this->timeout = $timeout;
	}

	/**
	 * Save name from cache
	 *
	 * @param string $name
	 * @param int $timeout
	 * @return bool
	 */
	public function isValid($path=null, $timeout=null)
	{
		if ($path === null) {
			$path = $this->path;
		}
		if ($timeout === null) {
			$timeout = $this->timeout;
		}
		$hit = true;
		clearstatcache(true, $path);
		$mtime = filemtime($path);
		if ($mtime !== false) {
			$time = time();
			if (($mtime + $timeout) < $time) {
				$hit = false;
			}
		}
		return $hit;
	}

}
