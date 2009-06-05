<?php
include_once 'A/Pagination/Adapter/Abstract.php';

/**
 * Datasource access class for paging through lines in a file  
 * 
 * @package A_Pagination 
 */

class A_Pagination_Adapter_File extends A_Pagination_Adapter_Abstract	{
	protected $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function getNumItems() {
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

    public function getItems($start, $length) {
    	$counter = 0;
        $rows = array();
        $fp = fopen($this->filename, 'r');
        if ($fp) {
            while (!feof($fp) && $counter < $begin) {
                fgets($fp, 4096);
                ++$counter;
            }
            while (!feof($fp) && $counter < $end) {
                $rows[] = array('line' => fgets($fp, 4096));
                ++$counter;
            }
            fclose($fp);
        }
        return $rows;
    }

}

