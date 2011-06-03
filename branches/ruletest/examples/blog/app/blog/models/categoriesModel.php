<?php

class categoriesModel extends A_Model {
	
	protected $dbh = null;
	
	public function __construct($db){
		$this->dbh = $db;
		$this->addField(new A_Model_Field('id'));
		$this->addField(new A_Model_Field('name'));
		$this->addField(new A_Model_Field('parent'));
	}
	
	public function save(){
		// if doesn't exist yet create
		if(!$this->get('id')){
			// insert new 
		} else {
			// update
		}
	}
	
	public function find($id){}
	public function findBy($someArgs){}
	public function delete($id){}
	
}