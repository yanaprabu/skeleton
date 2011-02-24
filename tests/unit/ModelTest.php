<?php

class ModelTest extends UnitTestCase {
	
	function setUp() {
	}
	
	function TearDown() {
	}
	
	function testModelNoFields() {
		$model = new A_Model();
		
		$datasource = new A_DataContainer();
		
		$this->assertTrue($model->isValid($datasource));
	}
	
	function testModelFieldFilters() {
		$model = new A_Model();
		
		$datasource = new A_DataContainer();
		$datasource->set('foo', 'barBAR');
		$datasource->set('bar', 'bazBAZ');
		
		$foo = $model->newField('foo');
		$foo->addFilter(new A_Filter_Regexp('/[^a-z]/'));

		$bar = $model->newField('bar');
		$bar->addFilter(new A_Filter_Regexp('/[^A-Z]/'));
		
		$this->assertTrue($model->isValid($datasource));
		
		$values = $model->getValues();
		$this->assertEqual($values['foo'], 'bar');
		$this->assertEqual($values['bar'], 'BAZ');
		
#echo '<pre>' . print_r($values, 1) . '</pre>';
	}
	
	function testModelFilters() {
		$model = new A_Model();
		
		$datasource = new A_DataContainer();
		$datasource->set('foo', 'barBAR');
		$datasource->set('bar', 'bazBAZ');
		
		$model->addFilter(new A_Filter_Regexp('/[^a-z]/'), array('foo'));
		$model->addFilter(new A_Filter_Regexp('/[^A-Z]/'), 'bar');
		
		$this->assertTrue($model->isValid($datasource));
		
		$values = $model->getValues();
		$this->assertEqual($values['foo'], 'bar');
		$this->assertEqual($values['bar'], 'BAZ');
		
#echo '<pre>' . print_r($model->getValues(), 1) . '</pre>';
	}
	
	function testModelAddMultipleFilters() {
		$model = new A_Model();
		
		$datasource = new A_DataContainer();
		$datasource->set('foo', 'barBAR');
		$datasource->set('bar', 'bazBAZ');
		
		$model->addFilter(new A_Filter_Regexp('/[^a-z]/'), array('foo', 'bar'));
		
		$this->assertTrue($model->isValid($datasource));
		
		$values = $model->getValues();
		$this->assertEqual($values['foo'], 'bar');
		$this->assertEqual($values['bar'], 'baz');
		
#echo '<pre>' . print_r($model->getValues(), 1) . '</pre>';
#echo '<pre>' . print_r($model, 1) . '</pre>';
	}
	
	function testModelCheckRules() {
		$model = new A_Model();
		
		$datasource = new A_DataContainer();
		$datasource->set('foo', 'barBAR');
		$datasource->set('bar', 'baz');
		
		$rule = new A_Rule_Regexp('/^[a-z]*$/', '', 'not all lowercase letters. ');
		
		// add rule to check both fields
		$model->addRule($rule, array('foo', 'bar'));
#		$model->excludeRules(array('foo', 'bar'));
		$this->assertFalse($model->isValid($datasource));

		// only check bar
		$model->excludeRules(array('foo'));
		$this->assertTrue($model->isValid($datasource));

		// only check foo
		$model->excludeRules(array());
		$model->includeRules(array('foo'));
		$this->assertFalse($model->isValid($datasource));
		#dump($model, 'Model: ', 1);
#echo '<pre>' . print_r($model->getErrorMsg(), 1) . '</pre>';
#echo '<pre>' . print_r($model->isValid($datasource), 1) . '</pre>';
	}
	
	function testModelCheckExcludeRules() {
	}
	
}
