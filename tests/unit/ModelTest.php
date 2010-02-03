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
		$this->assertTrue($values['foo'], 'bar');
		$this->assertTrue($values['bar'], 'BAZ');
		
		echo '<pre>' . print_r($model->getValues(), 1) . '</pre>';
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
		$this->assertTrue($values['foo'], 'bar');
		$this->assertTrue($values['bar'], 'BAZ');
		
		echo '<pre>' . print_r($model->getValues(), 1) . '</pre>';
	}
	
	function testModelAddMultipleFilters() {
		$model = new A_Model();
		
		$datasource = new A_DataContainer();
		$datasource->set('foo', 'barBAR');
		$datasource->set('bar', 'bazBAZ');
		
 		$model->addFilter(new A_Filter_Regexp('/[^a-z]/'), array('foo', 'bar'));
		
		$this->assertTrue($model->isValid($datasource));
		
		$values = $model->getValues();
		$this->assertTrue($values['foo'], 'bar');
		$this->assertTrue($values['bar'], 'baz');
		
		echo '<pre>' . print_r($model->getValues(), 1) . '</pre>';
		echo '<pre>' . print_r($model, 1) . '</pre>';
	}
	
}
