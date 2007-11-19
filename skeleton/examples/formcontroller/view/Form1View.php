<?php
include 'Template.php';
include 'URL.php';

class Form1View {

function Form1View () {
}

	function init($locator) {
		echo 'Form1View: InitHandler: STATE INIT<br/>';
		$controller = $locator->get('Controller');
		
		$param1 = $controller->getParameter('field1');
		$param2 = $controller->getParameter('field2');
		$param3 = $controller->getParameter('field3');
		$param4 = $controller->getParameter('field4');
		$param1->setValue('15');
		$param2->value = 'init';
		$param3->value = 'init';
		$param4->value = 'init';
		include 'template/example_form.php';
	}
	
	function submit($locator) {
		echo 'Form1View: SubmitHandler: STATE SUBMITTED<br/>';
		$controller = $locator->get('Controller');
	
		$param1 = $controller->getParameter('field1');
		$param2 = $controller->getParameter('field2');
		$param3 = $controller->getParameter('field3');
		$param4 = $controller->getParameter('field4');
		include 'template/example_form.php';
	}
	
	function done($locator) {
		$response = $locator->get('Response');
		$url = new URL('action');
		$response->setRedirect($url->getURL('foo'));
#		echo 'Form1View: DoneHandler: STATE DONE<br/>';
	}
	
}

