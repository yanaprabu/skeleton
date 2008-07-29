<?php
require_once 'A/Controller/Action/Dispatch.php';
include_once 'A/Model/Request.php';
require_once 'A/Validator.php';
require_once 'A/FilterChain.php';
require_once 'A/Html/Form.php';
require_once 'A/Filter/Alnum.php';
require_once 'A/Filter/Digit.php';
require_once 'A/Filter/Trim.php';
require_once 'A/Rule/Digit.php';
require_once 'A/Rule/Inarray.php';
require_once 'A/Rule/Length.php';

class MyRules extends A_Validator {
    public function init() {
        $this->addRule(new A_Rule_Length(5, 9, 'Please provide a five or nine digit zip code'), array('zip_home', 'zip_work'));
        $this->addRule(new A_Rule_Digit('Please provide a valid phone number'), array('zip_home', 'zip_work'));
        $this->addRule(new A_Rule_Inarray(array('male', 'female'), 'You have selected an invalid choice'), array('gender'));
    }
}
 
// same goes for filter sets
class MyFilters extends A_FilterChain {
    public function init() {
        $this->addFilter(new A_Filter_Trim()); // apply to all 
        $this->addFilter(new A_Filter_Digit(), array('zip_home', 'zip_work', 'phone_home', 'phone_work')); // apply to only the elements in the array
        $this->addFilter(new A_Filter_Alnum(true), array('', 'zip_work')); // atomic rules / filters like arborint argues are superior (I happen to agree) - passing true means allowWhiteSpace
    }
}

class Form4 extends A_Controller_Action_Dispatch {
	
	function run($locator) {
		$model = $this->load()->model('Users');
#echo '<pre>' . print_r($this, 1) . '</pre>';
#		$model->run($locator);
		
		$input = new A_Model_Request(); 
#		$input->setRequired($model->getRequired()); // get required fields from model
#		$input->addRule(new AddressRules); // modularity!
#		$input->addRule(new PhoneRules); // modularity!
		$input->addRule($model->getRules()); // modularity!
		$input->addRule(new MyRules);
		$input->addFilter(new MyFilters);
		 
		$view = $this->load()->view('Form4');
		if ($this->getRequest()->isPost()) {
		    if ($input->isValid()) {
		        try {
		            $model->save($input);
		            // redirect to user detail page or whatever
		        } catch (A_Model_Exception $e) {
		            // bummer!
		        }
		        exit;
		    } else {
		        $view->setErrorMsg($input->getErrorMsg());
		    }
		    $view->setValues($input->getValues());
		}
		echo $view->render();
	}

}
