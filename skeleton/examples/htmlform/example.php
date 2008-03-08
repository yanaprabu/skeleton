<?php
include_once 'config.php';
include_once 'A/Html/Form.php';

echo '<br/>Object usage:';
$form = new A_Html_Form();
$form->setWrapper('A_Html_Div', array('class'=>'fieldclass', 'style'=>'border:1px solid red;'));
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
$fields1 = new A_Html_Form();
$fields1->text('username', 'Enter Username:');
$fields1->password('passwd', 'Enter Password:');
$fields2 = new A_Html_Form();
$fields2->text('email', 'Enter Email:');
$fields2->password('phone', 'Enter Phone:');
$form = new A_Html_Form();
$form->fieldset('set1', $fields1->partial());
$form->fieldset('set2', $fields2->partial());
$form->submit('submit', 'login');
echo $form->render();
	