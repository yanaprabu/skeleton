<?php
/**
 * Support for downloading data with different MIME types and settings
 * 
 * @package A_Http 
 */

class A_Http_Download
{protected $mime_type = 'text/none';protected $encoding = 'none';protected $content_length = 0;protected $source_file = '';protected $target_file = '';protected $errorMsg = '';

/*
 * Set the mime type of the file to be downloaded to be specified in the header
 */
public function setMimeType ($type)
{
	if ($type) {
		$this->mime_type = $type;
	}
	return $this;
}

/*
 * Set the Transfer Encoding of the file to be downloaded to be specified in the header
 */
public function setEncoding ($encoding)
{
	if ($encoding) {
		$this->encoding = $encoding;
	}
	return $this;
}

/*
 * Set the path to a file on the server. 
 * The contents will be dumped following outputing the header and content length will be set
 */
public function setSourceFilePath ($path)
{
	if ($path) {
		$this->source_file = $path;
	}
	return $this;
}

/*
 * Set the filename to be used on the client. 
 * Use if no source file or if you want a different name than the source file. 
 */
public function setTargetFileName ($name)
{
	if ($name) {
		$this->target_file = $name;
	}
	return $this;
}

/*
 * Optional - if no source filename specified then use this to set length. 
 */
public function setContentLength ($length)
{
	if ($length) {
		$this->content_length = $length;
	}
	return $this;
}

protected function _header ($name, $value)
{
	if (is_array($value)) {
		foreach ($value as $val) {
			header("$name: $val");
		}
	} else {
		header("$name: $value");
	}
}

/*
 * Send headers, followed by the contents of the source file if specified.
 * Output will be included in the file
 */
public function doDownload ()
{
	if (! headers_sent()) {
		if ($this->mime_type) {
// set the mime type of the data to be downloaded
			$this->_header('Content-type', $this->mime_type);

			if ($this->encoding) {
				$this->_header('Content-Transfer-Encoding', $this->encoding);
			}
/*
// maybe implement some support for these
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Content-Transfer-Encoding: none');
header('Content-Type: application/octetstream');    //    IE and Opera
header('Content-Type: application/octet-stream');    //    All other browsers
header('Content-Transfer-Encoding: Binary');
header('Content-Disposition: attachment; filename="' . $name . '"');
header("Pragma: public");    //    Stop old IEs saving the download script by mistake
*/
// if target file name is supplied add it to header
			if ($this->target_file) {
				$this->_header('Content-Disposition', 'attachment; filename=' . $this->target_file);
			}
	
// if source file path is specified then dump the file following the header
			if ($this->source_file) {
				header('Content-Length: ' . @filesize($this->source_file));
				if (@readfile($this->source_file) === false) {
					$this->errorMsg = 'Error reading file ' . $this->source_file . '. ';
				}
			} elseif ($this->content_length > 0){
				header('Content-Length: ' . $this->content_length);
			}
		} else {
			$this->errorMsg = 'No MIME type. ';
		}
	} else {
		$this->errorMsg = 'Headers sent. ';
	}
	return $this->errorMsg;
}

public function isError ()
{
	return $this->errorMsg;
}


}
