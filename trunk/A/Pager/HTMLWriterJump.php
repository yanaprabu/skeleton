<?php
require_once 'A/Pager/HTMLWriter.php';

/**
 * Generate HTML <select> to go to specific page for A_Pager 
 * 
 * @package A_Pager 
 */

class A_Pager_HTMLWriterJump extends A_Pager_HTMLWriter {
	protected $current_page_template = 'Current Page {current_page}';
	protected $page_template = 'Page {current_page} of {last_page}';
	
	public function __construct($pager) {
		parent::__construct($pager);
	}

	public function setCurrentPageTemplate($template) {
		$this->current_page_template = $template;
		return $this;
	}

	public function setPageTemplate($template) {
		$this->page_template = $template;
		return $this;
	}

    public function render($form_attr='', $submit_attr='', $option_attr='') {

		$output = "<form action=\"\" method=\"GET\" $form_attr>\n";
		$params = $this->getParameters();
		foreach ($params as $name => $value) {
			$output .= "<input type=\"hidden\" name=\"$name\" value=\"$value\" />\n";
		} 
		$output .= '<select name="'. $this->pager->page_param . "\" onChange=\"this.form.submit()\" $submit_attr>\n";
		for ($i=1; $i<=$this->pager->last_page; ++$i) {
			if ($i == $this->pager->current_page) {
				$template = str_replace('{current_page}', $i, $this->current_page_template);
				$output .= "<option value=\"$i\" selected=\"selected\"  $option_attr>$template</option>\n";
			} else {
				$template = str_replace(array('{current_page}', '{last_page}'), array($i, $this->pager->last_page), $this->page_template);
				$output .= "<option value=\"$i\" $option_attr>$template</option>\n";
			}
		}
		$output .= "</select>\n</form>\n";
        return $output;
    }

}

