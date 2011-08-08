<?php
/**
 * File.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Pagination_Adapter_File
 * 
 * Datasource access class for paging through lines in a file.
 * 
 * @package A_Pagination
 */
class A_Pagination_Adapter_File extends A_Pagination_Adapter_Base
{

	protected $filename;
	protected $session = null;
	
	public function __construct($filename)
	{
		$this->filename = $filename;
	}
	
	public function setSession($session)
	{
		$this->session = $session;
	}
	
	public function getNumItems()
	{
		$counter = 0;
		$fp = fopen($this->filename, 'r');
		if ($fp) {
			while (!feof($fp)) {
				fgets($fp, 4096);
				++$counter;
			}
			fclose($fp);
		}
		return $counter;
	}
	
	public function getItems($start, $length)
	{
		$counter = 1;
		$rows = array();
		$fp = fopen($this->filename, 'r');
		if ($fp) {
			while (!feof($fp) && $counter < $start) {
				fgets($fp, 4096);
				++$counter;
			}
			$end = $start + $length;
			while (!feof($fp) && $counter < $end) {
				$rows[] = array('line' => fgets($fp, 4096));
				++$counter;
			}
			fclose($fp);
		}
		return $rows;
	}
	
	public function setOrderBy($field, $descending = 0)
	{}

	public function constructOrderBy()
	{}

}
