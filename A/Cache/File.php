<?php
/**
 * File.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Cache_File
 *
 * @package A_Cache
 */
class A_Cache_File
{

	protected $path = '';
	protected $prefix = 'a_cache---';
	protected $serialize = false;
	protected $lock_file = false;
	protected $chmod = 0;

	/**
	 * Constructor
	 *
	 * @param string $path
	 */
	public function __construct($path=null)
	{
		if ($path) {
			$this->path = $path;
		}
		$this->rule = new A_Cache_File_Mtime();
	}

	/**
	 * Save name from cache
	 *
	 * @param string $name
	 * @param int $timeout
	 * @return mixed
	 */
	public function load($name, $timeout)
	{
		return $this->_load($this->path . $this->prefix . $name, $timeout);
	}

	/**
	 * Save name to cache
	 *
	 * @param string $path
	 * @param string $name
	 * @return bool
	 */
	public function save($data, $name)
	{
		return $this->_save($data, $this->path . $this->prefix . $name);
	}

	/**
	 * Remove a name in cache
	 *
	 * @param string $path
	 * @return bool
	 */
	public function remove($name)
	{
		return $this->_remove($this->path . $this->prefix . $name);
	}

	/**
	 * Remove everything in cache
	 *
	 * @return bool
	 */
	public function clean()
	{
		return $this->_clean();
	}

	/**
	 * Load data from cache file
	 *
	 * @param string $path
	 * @param int $timeout
	 * @param bool $serialize
	 * @return mixed
	 */
	protected function _load($path, $timeout, $serialize=false)
	{
		$hit = $this->rule->isValid($path, $timeout);
		$result = false;
		if ($hit && is_file($path)) {
			$fh = fopen($path, 'rb');
			if ($fh) {
				if ($this->lock_file) {
					flock($fh, LOCK_UN);
				}
				$result = stream_get_contents($fh);
				if ($this->lock_file) {
					flock($fh, LOCK_UN);
				}
				fclose($fh);
				if ($serialize || $this->serialize) {
					return unserialize($result);
				}
			}
		}
		return $result;
	}

	/**
	 * write data to cache file
	 *
	 * @param string $data
	 * @param string $path
	 * @param bool $serialize
	 * @return bool
	 */
	protected function _save($data, $path, $serialize=false)
	{
		$result = false;
		$fh = @fopen($path, 'ab+');
		if ($fh) {
			fseek($fh, 0);
			if ($this->lock_file) {
				flock($fh, LOCK_UN);
			}
			ftruncate($fh, 0);
			if ($serialize || $this->serialize) {
				$tmp = fwrite($fh, serialize($data));
			} else {
				$tmp = fwrite($fh, $data);
			}
			fclose($fh);
			if ($tmp !== FALSE) {
				$result = true;
			}
		}
		if ($this->chmod) {
			chmod($path, $this->chmod);
		}
		return $result;
	}

	/**
	 * write data to cache file
	 *
	 * @param string $path
	 * @return bool
	 */
	protected function _remove($path)
	{
		return unlink($path);
	}

	/**
	 * write data to cache file
	 */
	protected function _clean()
	{
	    foreach (glob("{$this->path}/{$this->prefix}*") as $path) {
	        unlink($path);
	    }
	}

}
