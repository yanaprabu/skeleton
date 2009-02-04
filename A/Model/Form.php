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
	
	/**
	 * @param $method The allowed HTTP method, either: 'POST', 'GET', or ''
	 * @return $this for fluent interface
	 */
	public function setMethod($method) {
        if (in_array($method, array('POST','GET',''))) {
        	$this->method = strtoupper($method);
        }
        return $this;
    }

	/**
	 * @param $name The name of a required parameter
	 * @return $this for fluent interface
	 */
    public function setSubmitParameterName($name) {
		if ($name) {
			$this->submit_field_name = $name;
		}
		return $this;
	}
	
	/**
	 * @param $request A Request object
	 * @return true if not error
	 */
	public function processRequest($request) {
		  if ((($this->method == '') || ($request->getMethod() == $this->method)) && (($this->submit_param == '') || $request->has($this->submit_param))) {
			$this->is_submitted = true;

			parent::isValid($request);
		} else {
			$this->is_submitted = false;
			$this->error = true;
		}
			
		return ! $this->error;
	}
	
	/**
	 * Run the form as a Command object passed a Registry containing the Request
	 * @param $locator Registry object
	 * @return true if error occured
	 */
	public function run($locator) {
		$request = $locator->get('Request');
	
		$this->processRequest($request);

		return $this->error;
	}

	/**
	 * @return true|false
	 */
	public function isSubmitted() {
		return $this->is_submitted;
	}

}
