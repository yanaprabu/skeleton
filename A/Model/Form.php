<?php
include_once 'A/Model.php';
include_once 'A/Model/Form/Field.php';
/**
 * Model containing filtered and validated Request values 
 * 
 * @package A_Model 
 */

class A_Model_Form extends A_Model {
	protected $method = 'POST';
	protected $submit_param = '';
	protected $is_post = true;
	protected $is_submitted = false;
	protected $fieldClass = 'A_Model_Form_Field';
	
	public function setMethod($method) {
        $this->method = strtoupper($method);
        return $this;
    }

	public function setSubmitParameterName($name) {
		if ($name) {
			$this->submit_field_name = $name;
		}
		return $this;
	}
	
	public function processRequest($request) {
		  if ((($this->method == '') || ($request->getMethod() == $this->method)) && (($this->submit_param == '') || $request->has($this->submit_param))) {
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
