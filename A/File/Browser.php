<?php
/**
 * Browser.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_File_Browser
 *
 * Get list of directories and files in a directory.
 *
 * @package A_File
 */
class A_File_Browser
{

	const SORT_NONE = 0;
	const SORT_CASE_SENSITIVE = 1;
	const SORT_CASE_INSENSITIVE = 2;

	protected $param_path = 'browse_path';
	protected $param_area = 'browse_area';
	protected $base_path;
	protected $areas;
	protected $rel_path = '';
	protected $rel_area = '';
	protected $entries = array();
	protected $dirs = array();
	protected $files = array();
	protected $entry_n = 0;
	protected $dir_n = 0;
	protected $file_n = 0;
	protected $dir_current_n = 0;
	protected $file_current_n = 0;
	protected $entry_max = 0;
	protected $dir_max = 0;
	protected $file_max = 0;
	protected $sort;
	protected $request;
	protected $path_separator = ';';
	protected $extra_params = array();
	protected $errorMsg = '';

	public function __construct($request, $base_path='', $default_area='', $areas=array())
	{
		$this->request = $request;
		$this->base_path = $this->_addTrailingSlash($base_path);
		$this->areas = $areas;
		$this->rel_area = $default_area;
		$this->sort = self::SORT_CASE_INSENSITIVE;
	}

	protected function _formatParam($param)
	{
		$value = trim(preg_replace(array("/[\{$this->path_separator}]/", '/[^a-zA-Z0-9\-\/\.\ \%]/'), array('/', ''), urldecode($this->request->get($param))), '/');
		if ($value != '') {
			$value .= '/';
		}
		return $value;
	}

	protected function _addTrailingSlash($value)
	{
		if (substr($value, -1, 1) != '/') {
			$value .= '/';
		}
		return $value;
	}

	public function processRequest()
	{
		$this->rel_path = $this->_formatParam($this->param_path);
		$area = $this->_formatParam($this->param_area);
		if ($area && in_array($area, $this->areas)) {
			$this->rel_area = $area;
		}
		$this->rel_area = $this->_addTrailingSlash($this->rel_area);
		$this->rel_path = $this->_addTrailingSlash($this->rel_path);
	}

	public function readDir()
	{
		$this->processRequest();

		$path = $this->base_path . $this->rel_area . $this->rel_path;
		if (file_exists($path)) {
			$directory = new DirectoryIterator($path);
		} else {
			$this->errorMsg = "$path does not exist<br/>";
			return -1;
		}

		$result = array();
		$this->entry_n = 0;
		$this->dir_n = 0;
		$this->file_n = 0;
		$this->entry_max = 0;
		$this->dir_max = 0;
		$this->file_max = 0;

		$param = array($this->param_area=>$this->rel_area, $this->param_path=>'');

		while ($directory->valid()) {
			$filename = $directory->getFileName();
			if ($directory->isDir()) {
				if ($filename == '..') {
					if ($this->rel_path == '') {
						$filename = '.';	// don't show
					} else {
						$pos = strrpos(trim($this->rel_path, '/'), '/');
						if ($pos === false) {
							$dir = '';
						} else {
							$dir = substr($this->rel_path, 0, $pos);
						}
					}
				} else {
					$dir = $this->rel_path . $filename;
				}
				if ($filename != '.') {
					$param[$this->param_path] = $dir;
					$this->entries[$this->entry_max++] = array('type'=>'dir','param'=>$param, 'filename'=>$filename);
				}
			} else {
				$param[$this->param_path] = $this->rel_path;
				$this->entries[$this->entry_max++] = array('type'=>'file','param'=>$param, 'filename'=>$filename);
			}
			$directory->next();
		}

		if ($this->sort) {
			usort($this->entries, array($this, '_entrySort'));
		}

		for ($i = 0; $i < $this->entry_max; $i++) {
			if ($this->entries[$i]['type'] == 'dir') {
				$this->dirs[$this->dir_max++] = $i;
			} else {
				$this->files[$this->file_max++] = $i;
			}
		}

		return $this->entry_max;
	}

	public function _entrySort($e1, $e2)
	{
		if ($this->sort == self::SORT_CASE_SENSITIVE) {
			$a = $e1['filename'];
			$b = $e2['filename'];
		} else {
			$a = strtolower($e1['filename']);
			$b = strtolower($e2['filename']);
		}
		if ($a == $b) {
			return 0;
		}
		return ($a < $b) ? -1 : 1;
	}

	public function getBasePath()
	{
		return $this->base_path;
	}

	public function getPath()
	{
		return $this->base_path . $this->rel_area . $this->rel_path;
	}

	public function getAreaPath()
	{
		return $this->rel_area;
	}

	public function getRelativePath()
	{
		return $this->rel_path;
	}

	public function rewindFiles()
	{
		$this->file_n = 0;
	}

	public function nextFile()
	{
		if ($this->file_n < $this->file_max) {
			$this->file_current_n = $this->file_n;
			return $this->entries[$this->files[$this->file_n++]];
		}
	}

	public function rewindDirs()
	{
		$this->dir_n = 0;
	}

	public function nextDir()
	{
		if ($this->dir_n < $this->dir_max) {
			$this->dir_current_n = $this->dir_n;
			return $this->entries[$this->dirs[$this->dir_n++]];
		}
	}

	public function getFiles()
	{
		$files = array();
		foreach ($this->files as $n) {
			$files[] = $this->entries[$n];
		}
		return $files;
	}

	public function getDirs()
	{
		$dirs = array();
		foreach ($this->dirs as $n) {
			$dirs[] = $this->entries[$n];
		}
		return $dirs;
	}

	public function buildParam($params, $sep1='&', $sep2='=')
	{
		return $this->buildParameters($params, $sep1, $sep2);
	}

	public function buildParameters($params, $sep1='&', $sep2='=')
	{
		foreach ($this->extra_params as $name => $value) {
			if (($name != $this->param_area) && ($name != $this->param_path)) {
				$params[$name] = $value;
			}
		}
		$str = '';
		foreach ($params as $name => $value) {
			if ($str) {
				$str .= $sep1;
			}
			$str .= "$name$sep2" . str_replace('/', $this->path_separator, $value);
		}
		return $str;
	}

	public function getParam()
	{
		return $this->getParameters();
	}

	public function getParameters()
	{
		$params = array(
			$this->param_area => $this->rel_area,
			$this->param_path => $this->rel_path,
		);
		foreach ($this->extra_params as $name => $value) {
			if (($name != $this->param_area) && ($name != $this->param_path)) {
				$params[$name] = $value;
			}
		}
		return $params;
	}

	public function setExtraParameters($params=array())
	{
		$this->extra_params = $params;
		return $this;
	}

	public function setSort($sort)
	{
		$this->sort = $sort;
		return $this;
	}

}
