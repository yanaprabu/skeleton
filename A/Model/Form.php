<?php
/**
 * Form.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Model_Form
 *
 * An extension of A_Model representing request data/parameters.
 * 
 * @package A_Model
 */
class A_Model_Form extends A_Model
{

	protected $method = 'POST';
	protected $submit_param = '';
	protected $is_post = true;
	protected $is_submitted = false;
	protected $fieldClass = 'A_Model_Form_Field';
	
	/**
	 * @param string $method The allowed HTTP method, either 'POST', 'GET', or ''
	 * @return $this
	 */
	public function setMethod($method)
	{
		$method = strtoupper($method);
        if (in_array($method, array('POST','GET',''))) {
        	$this->method = $method;
        }
        return $this;
    }
	
	/**
	 * Set the field which denotes that this form has been submitted
	 * 
	 * @param string $name The field name
	 * @return $this
	 */
    public function setSubmitParameterName($name)
    {
		if ($name) {
			$this->submit_field_name = $name;
		}
		return $this;
	}
	
	/**
	 * @param $request A Request object
	 * @return bool True if successful, false otherwise
	 */
	public function isValid($request=null)
	{
		  if ((($this->method == '') || ($request->getMethod() == $this->method)) && (($this->submit_param == '') || $request->has($this->submit_param))) {
			$this->is_submitted = true;
			parent::isValid($request);
		} else {
			$this->is_submitted = false;
			$this->error = true;
		}
		
		return !$this->error;
	}
	
	/**
	 * Run the form as a Command object passed a Registry containing the Request
	 * 
	 * @param A_Locator $locator
	 * @return bool True if error occured
	 */
	public function run($locator)
	{
		$request = $locator->get('Request');
		$this->isValid($request);
		return $this->error;
	}
	
	/**
	 * @return bool True if form has been validated
	 */
	public function isSubmitted()
	{
		return $this->is_submitted;
	}

}
