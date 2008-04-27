<?php
// $Id: page_request.php 1782 2008-04-25 17:09:06Z pp11 $

class PageRequest {
    var $_parsed;
    
    function PageRequest($raw) {
        $statements = explode('&', $raw);
        $this->_parsed = array();
        foreach ($statements as $statement) {
            if (strpos($statement, '=') === false) {
                continue;
            }
            $this->parseStatement($statement);
        }
    }
    
    private function parseStatement($statement) {
        list($key, $value) = explode('=', $statement);
        $key = urldecode($key);
        if (preg_match('/(.*)\[\]$/', $key, $matches)) {
            $key = $matches[1];
            if (! isset($this->_parsed[$key])) {
                $this->_parsed[$key] = array();
            }
            $this->addValue($key, $value);
        } elseif (isset($this->_parsed[$key])) {
            $this->addValue($key, $value);
        } else {
            $this->setValue($key, $value);
        }
    }
    
    private function addValue($key, $value) {
        if (! is_array($this->_parsed[$key])) {
            $this->_parsed[$key] = array($this->_parsed[$key]);
        }
        $this->_parsed[$key][] = urldecode($value);
    }
    
    private function setValue($key, $value) {
        $this->_parsed[$key] = urldecode($value);
    }
    
    function getAll() {
        return $this->_parsed;
    }
    
    function get() {
        $request = &new PageRequest($_SERVER['QUERY_STRING']);
        return $request->getAll();
    }
    
    function post() {
        global $HTTP_RAW_POST_DATA;
        $request = &new PageRequest($HTTP_RAW_POST_DATA);
        return $request->getAll();
    }
}
?>