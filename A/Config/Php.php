<?php

/**
 * Support for reading INI configuration files
 *
 * @package A_Config
 */

class A_Config_Php extends A_Config_Abstract {
   protected function _loadFile() {
      include $this->_filename;
      return $config;
   }
}
