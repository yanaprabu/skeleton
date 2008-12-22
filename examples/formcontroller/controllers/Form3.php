<?php
require_once('A/Controller/Action.php');
require_once('A/Html/Form.php');

class Form3 extends A_Controller_Action {
	
	function run($locator) {
		$model = $this->load()->model('Form');
		#echo '<pre>' . print_r($this, 1) . '</pre>';
		$model->run($locator);
		
		if ($model->isValid()) {
			echo 'DONE<br/><br/><a href="../">Return to Examples</a>';
		} else {
			if (! $model->isSubmitted()) {
				$model->set('field1', 15);
				$model->set('field2', 'init');
				$model->set('field3', 'init');
				$model->set('field4', 'init');
			}

			// create HTML form generator
			$form = new A_Html_Form();
			$form->setModel($model)
					->setWrapper('A_Html_Div', array('class'=>'fieldclass', 'style'=>'border:1px solid red;'))
					->select(array('name'=>'field1', 'values'=>array(5,10,15), 'labels'=>array('five','ten','fifteen'), 'id'=>'field1', 'label'=>'Field 1 (Numbers only in range 1-10)', 'after'=>$model->getField('field1')->getErrorMsg(', ')))
					->text(array('name'=>'field2', 'id'=>'field2', 'label'=>'Field 2 (Must match Field 1)', 'after'=>$model->getField('field2')->getErrorMsg(', ')))
					->text(array('name'=>'field3', 'id'=>'field3', 'label'=>'Field 3 (Letters only min length 5)', 'after'=>$model->getField('field3')->getErrorMsg(', ')))
					->text(array('name'=>'field4', 'id'=>'field4', 'label'=>'Field 4 (Convert letters to uppercase)', 'after'=>$model->getField('field4')->getErrorMsg(', ')))
					->submit('submit', 'Submit');
			echo $form->render();
		}
	}

}
