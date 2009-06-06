<?php
/**
 * A_Delimited_Config
 *
 * Configuration value object for reading/writing delimited text files
 *
 * @author Christopher Thompson
 * @package A_Delimited
 * @version @package_version@
 */

class A_Delimited_Config {
    public $lineDelimiter = "\r\n";
    public $fieldDelimiter = "\t";
    public $fieldEnclosure = '"';
    public $fieldEscape = "\\";
    public $fieldNamesInFirstRow = true;
}