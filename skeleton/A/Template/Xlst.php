<?php
include_once 'A/Template.php';

class A_Template_Xslt extends A_Template {
	protected $filenamexml = '';

	public function __construct($filenamexsl='', $data=array()) {
	    $this->A_Template($filenamexsl, $data);
	}
	
	public function setXML($xml) {
	    $this->template = $xml;
		return $this;
	}
	
	public function setXMLFilename($filename) {
	    $this->filenamexml = $filename;
		return $this;
	}
	
	public function render() {
		if ($this->filename && ($this->template || $this->filenamexml)) {
			$xml = new DOMDocument();
			if ($this->template) {
				$xml->loadXML($this->template);
			} elseif ($this->filenamexml) {
				$xml->load($this->filenamexml);
			}
			
			$xsl = new DOMDocument();
			$xsl->load($this->filename);
			
			$processor = new XSLTProcessor();
			$processor->importStyleSheet($xsl);
			return $processor->transformToXML($xml);
		} 
	}

}
