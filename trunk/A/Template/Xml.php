<?php
/**
 * Template class using XML files as templates 
 * 
 * @package A_Template
 */

class A_Template_Xml extends A_Template_Base {
	protected $data = array();
	protected $depth = -1;
	protected $filename = '';
	protected $errorMsg = '';

	public function __construct($filename='') {
		$this->filename = $filename;
		$this->xml_parser = xml_parser_create();
		if ($this->xml_parser) {
			xml_set_object($this->xml_parser, $this);
			xml_set_element_handler($this->xml_parser, "_startElement", "_endElement");
			xml_set_character_data_handler($this->xml_parser, "_characterData");
		} else {
			$this->errorMsg = 'Error creating xml_parser';
		}
	}

	public function free() {
		xml_parser_free($this->xml_parser);
	}

	protected function _startElement($parser,$tagname,$attr) {
		$this->tags[++$this->depth] = $tagname;
		$this->data[$tagname] = '';
	}

	protected function _characterData($parser, $data) {
		$this->data[$this->tags[$this->depth]] .= trim($data);
	}

	protected function _endElement($parser,$tagname) {
		--$this->depth;
	}

	public function read($filename='') {
		if ($this->filename) {
			$this->filename = $this->filename;
		}
		if ($this->xml_parser) {
			$fp = fopen($this->filename, 'r');
			if ($fp) {
				while ($data = fread($fp, 4096)) {
				   if (! xml_parse($this->xml_parser, $data, feof($fp))) {
				       $this->errorMsg = sprintf("XML error: %s at line %d", 
				           xml_error_string(xml_get_error_code($this->xml_parser)), 
				           xml_get_current_line_number($this->xml_parser));
						break;
				   }
				}
				fclose($fp);
			} else {
				$this->errorMsg = "Error reading RSS data.";
			}
		} else {
			$this->errorMsg = "No xml_parser";
		}
	}

}
