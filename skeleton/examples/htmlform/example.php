<?php
include_once 'config.php';
include_once 'A/Html/Form.php';

echo '<br/>Object usage:';
$form = new A_Html_Form();
$form->text('username', 'Enter Username:');
$form->password('passwd', 'Enter Password:');
$form->submit('submit', 'login');
echo $form->render();

$form->reset();
echo '<br/>Fluent usage:';
echo $form->text(array('name'=>'username', 'label'=>'Enter Username:'))
		->password('passwd', 'Enter Password:')
		->submit('submit', 'login')
		->render();

echo '<br/>Static usage';
echo A_Html_Form::render(array('action'=>'foo', 'class'=>'bar'), 
'Form inputs go here'
	);

echo '<br/>Fieldset usage:';
$fields = new A_Html_Form();
$fields->text('username', 'Enter Username:');
$fields->password('passwd', 'Enter Password:');
$form = new A_Html_Form();
$form->fieldset('set1', $fields->partial());
$form->submit('submit', 'login');
echo $form->render();
	