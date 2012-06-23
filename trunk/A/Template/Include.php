<?php
/**
 * Include.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Template_Include
 *
 * Template class that includes PHP templates. No block support.
 *
 * @package A_Template
 */
class A_Template_Include extends A_Template_Base implements A_Renderer
{

	public function partial($template, $data=null)
	{
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$this->data[$key] = $value;
			}
		}
		return $this->render(dirname($this->filename) . "/$template.php");
	}

	public function partialLoop($template, $name, $data=null)
	{
		$template = dirname($this->filename) . "/$template.php";
		$str = '';
		if ($data) {
			// $name and $data set so each element in $data set to $name
			foreach ($data as $value) {
				$this->data[$name] = $value;
				$str .= $this->render($template);
			}
		} else {
			// $name but not $data, so $name contains $data. set() to $keys in each element array
			foreach ($name as $data) {
				if (is_array($data)) {
					foreach ($data as $key => $value) {
						$this->data[$key] = $value;
					}
				}
				$str .= $this->render($template);
			}
		}
		return $str;
	}

	/**
	 * short for $this->set($name, $this->partial($template, $data))
	 *
	 * @param string $name
	 * @param string $template
	 * @param array $data
	 * @return mixed
	 */
	public function setPartial($name, $template, $data=null)
	{
		return $this->set($name, $this->partial($template, $data));
	}

	/**
	 * short for $this->set($name, $this->partialLoop($template, $data_name, $data))
	 *
	 * @param string $name
	 * @param string $template
	 * @param string $data_name
	 * @param array $data
	 * @return mixed
	 */
	public function setPartialLoop($name, $template, $data_name, $data=null)
	{
		return $this->set($name, $this->partialLoop($template, $name, $data));
	}

	public function render()
	{
	    extract($this->data);
		ob_start();
	    include(func_num_args() ? func_get_arg(0) : $this->filename);
	    return ob_get_clean();
	}

}
