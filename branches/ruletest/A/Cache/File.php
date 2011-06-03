<?php

/**
 * 
 */
class A_Cache_File {
	protected $path = '';
	protected $prefix = 'a_cache---';
	protected $serialize = false;
	protected $lock_file = false;
	protected $chmod = 0;
	
	/**
	 * 
	 */
	public function __construct($path=null) {
		if ($path) {
			$this->path = $path;
		}
		$this->rule = new A_Cache_File_Mtime();
	}
	
	/**
	 * Save name from cache
	 *
	 * @param  string $name
	 * @return data
	 */
	public function load($name, $timeout) {
		return $this->_load($this->path . $this->prefix . $name, $timeout);
	}
	
	/**
	 * Save name to cache
	 *
	 * @param  string $path
	 * @return true/false
	 */
	public function save($data, $name) {
		return $this->_save($data, $this->path . $this->prefix . $name);
	}
	
	/**
	 * Remove a name in cache
	 *
	 * @param  string $path
	 * @return true/false
	 */
	public function remove($name) {
		return $this->_remove($this->path . $this->prefix . $name);
	}
	
	/**
	 * Remove everything in cache
	 *
	 * @return true/false
	 */
	public function clean() {
		return $this->_clean();
	}
	
	/**
	 * Load data from cache file
	 *
	 * @param  string $path
	 * @return true/false
	 */
	protected function _load($path, $timeout, $serialize=false) {
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
	 * @param  $path
	 * @param  $data
	 * @return true/false
	 */
	protected function _save($data, $path, $serialize=false) {
		$result = false;
		$fh = @fopen($path, 'ab+');
		if ($fh) {
			fseek($fh, 0);
			if ($this->lock_file) {
				flock($fh, LOCK_UN);
			}
			ftruncate($fh, 0);
echo "WRITE TO CACHE $path, data=$data<br/>";
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
	 * @param  $path
	 * @param  $data
	 * @return true/false
	 */
	protected function _remove($path) {
		return unlink($path);
	}

	/**
	 * write data to cache file
	 *
	 * @param  $path
	 * @param  $data
	 * @return true/false
	 */
	protected function _clean() {
	    foreach(glob("{$this->path}/{$this->prefix}*") as $path) {
	        unlink($path);
	    }
	}

}


class A_Cache_File_Mtime {
	protected $path = '';
	protected $timeout;
	
	/**
	 * 
	 */
	public function __construct($path=null, $timeout=10) {
		if ($path) {
			$this->path = $path;
		}
		$this->timeout = $timeout;
	}
	
	/**
	 * Save name from cache
	 *
	 * @param  string $name
	 * @return data
	 */
	public function isValid($path=null, $timeout=null) {
		if ($path === null) {
			$path = $this->path;
		}
		if ($timeout === null) {
			$timeout = $this->timeout;
		}
		$hit = true;
		clearstatcache(true, $path);
		$mtime = filemtime($path);
		if ($mtime !== false) {		// cache file exists
			$time = time();
			if ($mtime + $timeout < $time) {
				$hit = false;
			}
		}
		return $hit;
	}
	
}