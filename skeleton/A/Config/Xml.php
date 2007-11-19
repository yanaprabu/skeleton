<?php

class A_Config_Xml extends A_Config_Abstract {
   protected function _loadFile() {
      return simplexml_load_file($this->_filename);
   }
}
