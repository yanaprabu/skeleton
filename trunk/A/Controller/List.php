<?php
#include_once 'A/Controller/Input.php';
#include_once 'A/Pager.php';
/**
 * was intended for pagination support -- not used
 *
 * @package A_Controller
 */


class A_Controller_List extends A_Controller_Input {

	public function __construct($db) {
		$this->pager = new A_Pager($db);
	
		$this->addParameter($param1 = new A_Controller_Input_Field($this->pager->parampagen));
		$parampagen->addFilter(new FilterRegexp('/[^0-9]/', ''));
	
		$this->addParameter($paramrecordcount = new A_Controller_Input_Field($this->pager->paramrecordcount));
		$paramrecordcount->addFilter(new FilterRegexp('/[^0-9]/', ''));
	
		$this->addParameter($paramorderby = new A_Controller_Input_Field($this->pager->paramorderby));
		$paramorderby->addFilter(new FilterRegexp('/[^0-9]/', ''));
	
	}
	
	public function run($locator) {
	
		$request = $locator->get('Request');
		$this->processRequest($request);
	
		if ($reqest->is_post) {
		} else {
		}
		parent::run($locator);
	}

}
