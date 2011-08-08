<?php
/**
 * Uploadform.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Http_Uploadform
 *
 * Support for file upload forms
 * 
 * @package A_Http
 */
class A_Http_Uploadform
{

	protected $upload;
	protected $hidden = array();
	
	public function __construct($upload)
	{
		$this->upload = $upload;
	}
	
	public function addHidden($name, $value)
	{
		$this->hidden[$name] = $value;
	}
	
	public function addJavascript($code='')
	{
		return "<script lanugage=\"javascript\"><!--
public function a_http_uploadform_check(obj, regexpstr) {
	$code
}
// --></script>";
	}
	
	public function formOpen($action='', $method='', $attr=array())
	{
		if ($method == '') {
			$method = 'post';
		}
		$attr_str = '';
		foreach ($attr as $name => $value) {
			$attr_str .= " $name=\"$value\"";
		}
		$str = "<form enctype=\"multipart/form-data\" action=\"$action\" method=\"$method\"$attr_str>\n";
		if (is_array($this->hidden) ) {
			foreach ($this->hidden as $name => $value) {
				$str .= "<input type=\"hidden\" name=\"$name\" value=\"$value\"/>\n";
			}
		}
		return $str;
	}
	
	public function formSelectPath()
	{
		$str = '';
		if ($this->upload->paths && is_array($this->upload->paths) ) {
			$n = 0;
			$str = "<select name=\"{$this->upload->path_param}\">";
			foreach ($this->upload->labels as $id => $label) {
				$str .= "<option value=\"$id\">$label</option>";
			}
			$str .= '</select>';
		}
		
		return $str;
	}
	
	public function formInput($size=0, $max_file_size=null, $filename_regexp='')
	{
		if ($size == 0) {
			$size = 20;
		}
		if ($max_file_size == null) {
			$upload_max_filesize = ini_get('upload_max_filesize');
			$pot = strtolower(substr($upload_max_filesize, -1, 1));
			$max_file_size = intval($upload_max_filesize);
			switch($pot) {
				case 'g':
					$max_file_size *= 1073741824;
					break;
				case 'm':
					$max_file_size *= 1048576;
					break;
				case 'k':
					$max_file_size *= 1024;
					break;
			}
		}
		$max_file_size = " MAX_FILE_SIZE=\"$max_file_size\"";
		if ($filename_regexp) {
			$this->addJavascript();
			$filename_regexp = " onSelect=\"a_http_uploadform_check(this, '$filename_regexp');\"";
		}
		return "<input type=\"file\" name=\"{$this->upload->file_param}[]\" size=\"$size\"$max_file_size$filename_regexp/>";
	}
	
	public function formSubmit($value='')
	{
		if ($value == '') {
			$value = 'Upload';
		}
		return "<input type=\"submit\" name=\"{$this->upload->submit_param}\" value=\"$value\"/>";
	}
	
	public function formClose()
	{
		return "</form>";
	}
	
	public function form($action, $value='', $method='', $size=0)
	{
		$str = $this->formOpen($action, $method) . "\n";
		$str .= $this->formSelectPath() . "\n";
		$str .= $this->formInput($size) . "\n";
		$str .= $this->formSubmit($value) . "\n";
		$str .= $this->formClose() . "\n";
		return $str;
	}

}
