<?php
include 'config.php';
#include 'A/Config/Ini.php';
class A_Config_Ini extends A_Config_Abstract { 
   protected function _loadFile() {
      return parse_ini_file($this->_filename, true);
   }
}


abstract class A_Config_Abstract {
   protected $_filename;
   protected $_section;
   protected $_exception;
   protected $_error = 0;
   protected $_errmsg = '';

   public function __construct($filename, $section='', $exception=null) {
      $this->_filename = $filename;
      $this->_section = $section;
      $this->_exception = $exception;
   }
   
   public function loadFile() {
      set_error_handler(array($this, 'errorHandler'));
      $data = $this->_loadFile();
      restore_error_handler();
      if (!$this->_error) {
         if (!count($data)) {
            //dump(error_get_last());
            return false;
         }

         if ($this->_section) {
            //we want to avoid spitting notices, so lets check if the section exists first
            return isset($data[$this->_section]) ? $data[$this->_section] : false;
         } else {
            return $data;
         }
      } else {
         if ($this->_exception) {
            if (!class_exists('A_Exception')) include 'A/Exception.php';
            throw A_Exception::getInstance($this->_exception, $this->_errmsg);
         }
         return $this->_error == 0;
      }
   } 
   
   public function errorHandler($errno, $errstr, $errfile, $errline) {
      $this->_error = $errno;
      $this->_errmsg = $errstr;
echo "errorHandler($errno, $errstr, $errfile, $errline)<br/>";
   }   
}

$config = new A_Config_Ini('example1.inix', '');
$data = $config->loadFile();
var_export($data);
if ($data === false) {
	echo "Error found: loading file<br/>";
}

$config = new A_Config_Ini('example1.inix', '', new Exception('Ini file error.'));
try {
	$data = $config->loadFile();
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), '<br/>';
}

dump($data);

