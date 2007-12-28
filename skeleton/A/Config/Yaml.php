<?php
include_once 'A/Config/Abstract.php';

class A_Config_Yaml extends A_Config_Abstract {

	protected function _loadFile() {
		$lines = file($this->_filename);;
		$data = array();
		if ($lines) {
			$indentstack = array(0);			// hold the indent size for each level of indent
			$datastack = array();
			$datalastkey = array();				// last key inserted on each level of indent
			$listnstack = array(0=>-1);			// hold the list index for each level of indent
			$indentlevel = 0;
			$datastack[$indentlevel] =& $data;
			
			$nlines = count($lines);
			$linen = 0;
			while ($linen < $nlines) {
				// skip comments
				if (substr($lines[$linen], 0, 1) == '#') {
					++$linen;
					continue;
				}
				// skip documents for now
				if (substr($lines[$linen], 0, 3) == '---') {
					++$linen;
					continue;
				}
				
				// remove indent and trailing
				$line = trim($lines[$linen]);
		
				// skip blank lines
				if ($line == '') {
					++$linen;
					continue;
				}
		
				// indent is difference between original and trimmed
				$indentsize = strlen(rtrim($lines[$linen])) - strlen($line);
		    	
				// check if line is indented
				if ($indentsize > 0) {
				    if ($indentsize > $indentstack[$indentlevel]) {
						// going to a higher level of indent
				    	$indentstack[++$indentlevel] = $indentsize;
				    	// set the list index for the new level of indent to be 0 after being incremented
				    	$listnstack[$indentlevel] = -1;
				    } elseif ($indentsize < $indentstack[$indentlevel]) {
						// going to a lower level of indent
				    	while (($indentlevel > 0) && ($indentsize > $indentstack[$indentlevel])) {
				    		--$indentlevel;
				    	}
				    }
			    } else {
			    	$indentlevel = 0;
			    }
				$node = array();
				$firstchar = substr($line, 0, 1);
				// start a [ list
				if ($firstchar == '[') {
					// scalar list
					if (substr($line, -1) == ']') {
						$items = explode(',', substr($line, 1, strlen($line)-2));
						foreach ($items as $item) {
							$node[++$listnstack[$indentlevel]] = trim($item, " \"\t");
						}
					} else {
						// starting a multi-line list
					}
				// start a { hash
				} elseif ($firstchar == '{') {
					// hash list
					if (substr($line, -1) == '}') {
						$items = explode(',', substr($line, 1, strlen($line)-2));
						foreach ($items as $item) {
							list($key, $value) = explode(':', $item);
							$node[trim($key)] = trim($value, " \"\t");
						}
					} else {
						// starting a multi-line hash
					}
				// list item
				} elseif ($firstchar == '-') {
					if ($line == '-') {
						// - alone means list items will be indented on following lines
						$datastack[$indentlevel][++$listnstack[$indentlevel]] = array();
						$datalastkey[$indentlevel] = $listnstack[$indentlevel];
						
						// set next level of data to point to empty array added above
						$datastack[$indentlevel+1] =& $datastack[$indentlevel][$listnstack[$indentlevel]];
					} else {
						$lastchar = substr($line, -1);
						switch ($lastchar) {
						case ':':
							// "- name :" means list item is key for hash
							$key = trim(substr($line, 1), " :\{[>|\"\t");
							// initialize array for elements that will follow, next lines should be indented
							$datastack[$indentlevel][++$listnstack[$indentlevel]][$key] = array();
							// save the last key for this indent level for later
							$datalastkey[$indentlevel] = $listnstack[$indentlevel];
							// assign the next level on the data stack as reference to this new array
							$datastack[$indentlevel+1] =& $datastack[$indentlevel][$datalastkey[$indentlevel]][$key];
							break;
						default:
							$datastack[$indentlevel][++$listnstack[$indentlevel]] = trim(substr($line, 1), " :\{[>|\"\t");
							$datalastkey[$indentlevel] = $listnstack[$indentlevel];
						}
					}
				} else {
					$colonpos = strpos($line, ':');
					if ($colonpos === false) {
						// no colon on line
					} elseif ($colonpos == strlen($line)-1) {
						$key = rtrim($line, " \"\t:");
						$datastack[$indentlevel][$key] = array();
						$datalastkey[$indentlevel] = $key;
						
						// colon at end of line - set next level of data to point to last element added
						$datastack[$indentlevel+1] =& $datastack[$indentlevel][$datalastkey[$indentlevel]];
					} else {
						// colon in line
						list($key, $value) = explode(':', $line);
						$node[trim($key)] = trim($value, " \"\t");
					}
				}
				if ($node) {
					// assign the data returned
					foreach ($node as $key => $value) {
						$datastack[$indentlevel][$key] = $value;
						$datalastkey[$indentlevel] = $key;
					}
				}
				++$linen;
			}
		}
		return $data;
	}
	
}