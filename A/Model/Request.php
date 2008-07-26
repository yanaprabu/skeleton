<?php
include_once 'A/Model.php';
include_once 'A/Model/Request/Field.php';
/**
 * Model containing filtered and validated Request values 
 * 
 * @package A_Model 
 */

class A_Model_Request extends A_Model {
	protected $submit_field_name = 'submit';
	protected $is_post = true;
	protected $is_submitted = false;
	
	public function setSubmitParameterName($name) {
		if ($name) {
			$this->submit_field_name = $name;
		}
		return $this;
	}
	
	public function processRequest($request) {
		if ($request->has($this->submit_field_name) && ($request->isPost() == $this->is_post)) {
			$this->is_submitted = true;

			$this->process($request);
		} else {
			$this->is_submitted = false;
			$this->error = true;
		}
			
		return ! $this->error;
	}
	
	public function run($locator) {
		$request = $locator->get('Request');
	
		$this->processRequest($request);

		return $this->error;
	}
	
	public function isSubmitted() {
		return $this->is_submitted;
	}

}
