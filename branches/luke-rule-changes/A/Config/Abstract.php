<?php
require_once 'A/DataContainer.php';
/**
 * Abstract base class for configuration
 *
 * @package A_Config
 */

abstract class A_Config_Abstract {
   protected $_filename;
   protected $_section;
   protected $_exception;
   protected $_error = 0;
   
   public function __construct($filename, $section='', $exception=null) {
      $this->_filename = $filename;
      $this->_section = $section;
      $this->_exception = $exception;
   }
   
   public function loadFile() {
      set_error_handler(array($this, 'errorHandler'));
      $data = $this->_loadFile();
      restore_error_handler();
     
      //if there was a problem loading the file
      if (($this->_error || !count($data))
      //if the requested section does not exist
      || ($this->_section && !isset($data[$this->_section]))) {
         return false;
      }
   
      return new A_DataContainer($this->_section ? $data[$this->_section] : $data);
   }
   
   public function errorHandler($errno, $errstr, $errfile, $errline) {
      if ($this->_exception) {
        	include_once 'A/Exception.php';
         throw A_Exception::getInstance($this->_exception, $errstr);
      } else {
         $this->_error = $errno;
      }
   }   
}
