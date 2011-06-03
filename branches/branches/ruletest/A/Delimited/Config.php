<?php
/**
 * Config.php
 *
 * @package  A_Delimited
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 * @author   Christopher Thompson
 */

/**
 * A_Delimited_Config
 *
 * Configuration value object for reading/writing delimited text files
 */

class A_Delimited_Config {
    public $lineDelimiter = "\r\n";
    public $fieldDelimiter = "\t";
    public $fieldEnclosure = '"';
    public $fieldEscape = "\\";
    public $fieldNamesInFirstRow = true;
}