<?php
require_once 'A/Controller/Action/Dispatch.php';
include_once 'A/Model/Form.php';
require_once 'A/Rule/Set.php';
require_once 'A/Filter/Set.php';
require_once 'A/Html/Form.php';
require_once 'A/Filter/Alnum.php';
require_once 'A/Filter/Digit.php';
require_once 'A/Filter/Trim.php';
require_once 'A/Rule/Digit.php';
require_once 'A/Rule/Inarray.php';
require_once 'A/Rule/Length.php';
require_once 'A/Rule/Match.php';


class MyRules extends A_Rule_Set {
    public function init() {
        $this->addRule(new A_Rule_Length(5, 9, '', 'Please provide a five or nine digit zip code'), array('zip_home', 'zip_work'));
        $this->addRule(new A_Rule_Digit('', 'Please provide a valid phone number'), array('zip_home', 'zip_work'));
        $this->addRule(new A_Rule_Inarray(array('male', 'female'), '', 'You have selected an invalid choice'), array('gender'));
    }
}
 
// same goes for filter sets
class MyFilters extends A_Filter_Set {
    public function init() {
        $this->addFilter(new A_Filter_Trim()); // apply to all 
        $this->addFilter(new A_Filter_Digit(), array('zip_home', 'zip_work', 'phone_home', 'phone_work')); // apply to only the elements in the array
        $this->addFilter(new A_Filter_Alnum(true), array('', 'zip_work')); // atomic rules / filters like arborint argues are superior (I happen to agree) - passing true means allowWhiteSpace
    }
}

class Form4 extends A_Controller_Action_Dispatch {
	
	function index($locator) {
		
		$model = $this->load()->model('Users');

		$form = new A_Model_Form();
		$form->addField($model->getFields());  
#		$input->setRequired($model->getRequired()); // get required fields from model
#		$input->addRule(new AddressRules); // modularity!
#		$input->addRule(new PhoneRules); // modularity!
		$form->addRule($model->getRules()); // modularity!
		
		// Now add an additional field, the second password field. Which must match the first password field. 
		// The $form get the Rules for the first password field from $usersmodel 
		$form->addField($passwordfield = new A_Model_Form_Field('password2'));
		// now we add an additional rule, specific for the form we are dealing with.
		$form->addRule(new A_Rule_Match('password', 'password2', 'Password 2 must match Password 1'));
		
	//	$form->addRule(new MyRules);
	//	$form->addFilter(new MyFilters);dump($form);
		
		$view = $this->load()->view('Form4');
		if ($this->getRequest()->isPost()) {
		
			if ($form->isValid($this->getRequest())) {
		        try {
		            $model->save($form);
		            // redirect to user detail page or whatever
		        } catch (A_Model_Exception $e) {
		            // bummer!
		        }
		        exit;
		    } else {
		        $view->setErrorMsg($form->getErrorMsg());
		    }
		    $view->setValues($form->getValues());
		}
		echo $view->render();
	}

}
