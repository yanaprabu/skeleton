<?php
/**
 * List.php
 *
 * @package  A_Controller
 * @license  http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	 http://skeletonframework.com/
 */

/**
 * A_Controller_List
 * 
 * was intended for pagination support -- not used
 */
class A_Controller_List extends A_Controller_Input
{

	public function __construct($db)
	{
		$this->pager = new A_Pager($db);
		
		$this->addParameter($param1 = new A_Controller_Input_Field($this->pager->parampagen));
		$parampagen->addFilter(new FilterRegexp('/[^0-9]/', ''));
		
		$this->addParameter($paramrecordcount = new A_Controller_Input_Field($this->pager->paramrecordcount));
		$paramrecordcount->addFilter(new FilterRegexp('/[^0-9]/', ''));
		
		$this->addParameter($paramorderby = new A_Controller_Input_Field($this->pager->paramorderby));
		$paramorderby->addFilter(new FilterRegexp('/[^0-9]/', ''));
	}
	
	public function run($locator)
	{
		$request = $locator->get('Request');
		$this->processRequest($request);
		
		if ($reqest->is_post) {
		}
		parent::run($locator);
	}

}
