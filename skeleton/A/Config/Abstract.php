<?php

abstract class A_Config_Abstract {
   protected $filename;
   protected $section;
   protected $exception;
   protected $error = 0;
   
   public function __construct($filename, $section='', $exception=null) {
      $this->filename = $filename;
      $this->section = $section;
      $this->exception = $exception;
   }
   
   public function loadFile() {
      set_error_handler(array($this, 'errorHandler'));
      $data = $this->_loadFile();
      restore_error_handler();
     
      //if there was a problem loading the file
      if (($this->error || !count($data))
      //if the requested section does not exist
      || ($this->section && !isset($data[$this->section]))) {
         return false;
      }
   
      return new A_DataContainer($this->_section ? $data[$this->section] : $data);
   }
   
   public function errorHandler($errno, $errstr, $errfile, $errline) {
      if ($this->_exception) {
         if (!class_exists('A_Exception')) {
            include 'A/Exception.php';
         } 
         throw A_Exception::getInstance($this->exception, $errstr);
      } else {
         $this->error = $errno;
      }
   }   
}
