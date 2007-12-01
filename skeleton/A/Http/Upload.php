<?php
define('A_HTTP_UPLOAD_NOT_UPLOAD_FILE', 1001);
define('A_HTTP_UPLOAD_ERR_MAX_SIZE', 1002);
define('A_HTTP_UPLOAD_ERR_FILE_EXISTS', 1003);
define('A_HTTP_UPLOAD_ERR_FILE_UNLINK', 1004);
define('A_HTTP_UPLOAD_ERR_FILE_MOVE', 1005);
define('A_HTTP_UPLOAD_ERR_FILE_TYPE', 1006);

class A_Http_Upload {public $file_param = 'file';		// form/http parameter name for file(s)public $submit_param = 'upload';	// form/http parameter name for the submit buttonpublic $path_param = 'path';		// form/http parameter name for path relative to dirprotected $base_path = '/tmp';				// destination directory for uploaded file, or array of dirs will shows a select box, see formSelectPath()protected $file_mode = 0777;			// mode to create new directories and filesprotected $filename_regexp_pattern = array('/[^a-zA-Z0-9_\-\.]/');protected $filename_regexp_replace = array('_');
protected $replace = true;			// if destination file exists, delete and the uploadprotected $min_size = 1;			// set minimum size of files, 0 to allow zero size filesprotected $max_size = 0;			// cap size of file with this valueprotected $allowed_types = array();
public $paths = array();public $labels = array();		// text labels for form select in formSelectPath(), one matching text label for each path in dir array


public function __construct() {
	$this->setMaxFilesize(0);
}

public function isSubmitted() {
	return($_REQUEST[$this->submit_param]);
}

public function setFileParam($name) {
	$this->file_param = $name;
}

public function setPathParam($path) {
	$this->path_param = $path;
}

public function setSubmitParam($name) {
	$this->submit_param = $name;
}

public function setBasePath($base_path) {
	if ($base_path) {
		if (substr($base_path, -1) != '/') {
			$base_path .= '/';
		}
		$this->base_path = $base_path;
	}
}

public function setMinFilesize($min) {
	$this->min_size = $min;
}

public function setMaxFilesize($max)
{
	$this->max_size = $max;
	$max = $this->getMaxFilesize();
	if ($max && (($max < $this->max_size) || ($this->max_size == 0)) ) {
		$this->max_size = $max;
	}
}

public function setAllowedTypes($types=array()) {
	$this->allowed_types = $types;
}

public function setReplace($replace) {
	$this->replace = $replace;
}

public function addPath($id, $path, $label='') {
	$this->paths[$id] = $path;
	$this->labels[$id] = $label;
}

public function fileCount() {
	$n = 0;
	if (isset($_FILES[$this->file_param]['name']) && ($_FILES[$this->file_param]['name'][0] != '')) {
		$n = count($_FILES[$this->file_param]['name']);
	}
	return $n;
}

public function getMaxFilesize() {
	$max = ini_get('upload_max_filesize');
	if (is_string($max)) {
		$n = strlen($max) - 1;
		switch ($max[$n]) {
		case 'K':
			$m = 1024;
			$max[$n] = "\0";
			break;
		case 'M':
			$m = 1048576;
			$max[$n] = "\0";
			break;
		case 'G':
			$m = 1073741824;
			$max[$n] = "\0";
			break;
		default:
			$m = 1;
			break;
		}
		return($max * $m);
	} else {
		return 0;
	}
}

public function getFileOption($option, $n=0, $param='') {
	if ($param == '') {
		$param = $this->file_param;
	}
	if (isset($_FILES[$param][$option])) {
		if (is_array($_FILES[$param][$option])) {
			return $_FILES[$param][$option][$n];
		} else {
			return $_FILES[$param][$option];
		}
	} else {
		return '';
	}
}

public function setFileOption($value, $option, $n=0, $param='') {
	if ($param == '') {
		$param = $this->file_param;
	}
	if (is_array($_FILES[$param][$option])) {
		$_FILES[$param][$option][$n] = $value;
	} else {
		$_FILES[$param][$option] = $value;
	}
}

public function getFileName($n=0, $param='') {
	return preg_replace($this->filename_regexp_pattern, $this->filename_regexp_replace, $this->getFileOption('name', $n, $param));
}

public function getFileTmpName($n=0, $param='') {
	return $this->getFileOption('tmp_name', $n, $param);
}

public function getFileType($n=0, $param='') {
	return $this->getFileOption('type', $n, $param);
}

public function getFileSize($n=0, $param='') {
	return $this->getFileOption('size', $n, $param);
}

public function getImageData($n=0, $param='') {
#	$imagedata = getimagesize($this->getFileTmpName($n, $param));
#	$width = $imagedata[0];
#	$height = $imagedata[1];
#	$type = $imagedata[2];	// 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM
#	$atrs = $imagedata[3];	// 'height="yyy" width="xxx"'
#	return $imagedata;
	return getimagesize($this->getFileTmpName($n, $param));
}

public function getFileError($n=0, $param='') {
	return $this->getFileOption('error', $n, $param);
}

public function getFileErrorMsg($n=0, $param='') {
	$error = $this->getFileOption('error', $n, $param);
	$errmsg = array(
		UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the maximum size allowed. ', 
		UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the maximum size allowed in the form. ', 
		UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded. ', 
		UPLOAD_ERR_NO_FILE => 'No file was uploaded. ', 
		UPLOAD_ERR_NO_TMP_DIR => 'Error with temporary folder. ', 
		A_HTTP_UPLOAD_NOT_UPLOAD_FILE => 'Error with uploaded file. ',
		A_HTTP_UPLOAD_ERR_MAX_SIZE => 'The uploaded file exceeds the maximum allowed size. ', 
		A_HTTP_UPLOAD_ERR_FILE_EXISTS => 'A file by that name already exists. ', 
		A_HTTP_UPLOAD_ERR_FILE_UNLINK => 'Cannot replace existing file. ',
		A_HTTP_UPLOAD_ERR_FILE_MOVE => 'Permission denied. ',
		A_HTTP_UPLOAD_ERR_FILE_TYPE => 'File type not allowed. ',
		);
	return isset($errmsg[$error]) ? $errmsg[$error] : '';
}

public function isUploadedFile($n=0, $param='') {
	if (is_uploaded_file($this->getFileTmpName($n, $param))) {
		return true;
	} else {
		$this->setFileOption(A_HTTP_UPLOAD_NOT_UPLOAD_FILE, 'error', $n, $param);
		return false;
	}
}

public function isAllowedFilesize($n=0, $param='') {
	
	$size = $this->getFileOption('size', $n, $param);
	if ($this->min_size >= $size) {
		return false;
	}
	if ($this->max_size > 0) {
		if ($size <= $this->max_size) {
			return true;
		} else {
			$this->setFileOption(A_HTTP_UPLOAD_ERR_MAX_SIZE, 'error', $n, $param);
			return false;
		}
	}
	
	return true;
}

public function isAllowedType($n=0, $param='') {
	if ($this->allowed_types) {
		if (in_array($this->getFileOption('type', $n, $param), $this->allowed_types)) {
			return true;
		} else {
			$this->setFileOption(A_HTTP_UPLOAD_ERR_FILE_TYPE, 'error', $n, $param);
			return false;
		}
	}
	return true;
}

public function isAllowed($n=0, $param='') {
	return $this->isUploadedFile($n, $param) && $this->isAllowedFilesize($n, $param) && $this->isAllowedType($n, $param);
}

public function moveUploadedFile($n=0, $param='', $filename='') {
	if ($filename == '') {
		$filename = $this->getFileName($n, $param);
	}
	$path_param = $this->getPath();
	if ($path_param != '') {
		$path = $this->paths[$path_param];
	} else {
		$path = '';
	}
	$filename = $this->base_path . $path . $filename;
	if (file_exists($filename)) {
		if ($this->replace) {
			if (! @unlink($filename)) {
				$this->setFileOption(A_HTTP_UPLOAD_ERR_FILE_UNLINK, 'error', $n, $param);
				return false;
			}
		} else {
			$this->setFileOption(A_HTTP_UPLOAD_ERR_FILE_EXISTS, 'error', $n, $param);
			return false;
		}
	}
	if (! @move_uploaded_file($this->getFileTmpName($n, $param), $filename)) {
		$this->setFileOption(A_HTTP_UPLOAD_ERR_FILE_MOVE, 'error', $n, $param);
		return false;
	}
	return true;
}

public function createDir($path, $mode=0) {
	if ($path) {
		if (! $mode) {
			$mode = $this->file_mode;
		}
		if (file_exists($path)) {
			if (is_dir($path)) {
				return true;
			}
		} elseif (@mkdir($path, $mode, true)) {
			return true;
		}
	}
	return false;
}

public function deleteTmpFile($n, $param='') {
	if ($filename = $this->getFileTmpName($n, $param)) {
		return @unlink($filename);
	}
}

public function getPath() {
	if (isset($_REQUEST[$this->path_param])) {
		return preg_replace('/[^a-zA-Z0-9_\-\.]/', '', $_REQUEST[$this->path_param]);
	} else {
		return '';
	}
}

}
