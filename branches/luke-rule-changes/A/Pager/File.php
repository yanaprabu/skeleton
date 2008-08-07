<?php
/**
 * Datasource access class for Pager using a file 
 * 
 * @package A_Pager 
 */

class A_Pager_File {
	protected $filename;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function getNumRows() {
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

    public function getRows($begin, $end) {
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

