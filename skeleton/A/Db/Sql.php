<?php

class A_Db_Sql {	protected $select = null;	protected $insert = null;
	protected $update = null;
	protected $delete = null;
		
	public function __construct() {
	}
		
	function __call($name, $param) {
	    switch ($name) {
	    case 'select':
	        if (! $this->select) {
	            include_once 'A/Db/Sql/Select.php';
	            $this->select = new A_Db_Sql_Select();
	        }
	        return $this->select->columns($param);
	        break;
	    case 'insert':
	        if (! $this->insert) {
	            include_once 'A/Db/Sql/Insert.php';
	            $this->insert = new A_Db_Sql_Insert();
	        }
	        return $this->insert->table($param);
	    	break;
	    case 'update':
	        if (! $this->update) {
	            include_once 'A/Db/Sql/Update.php';
	            $this->update = new A_Db_Sql_Update();
	        }
	        return $this->update->table($param);
	    	break;
	    case 'delete':
	        if (! $this->delete) {
	            include_once 'A/Db/Sql/Delete.php';
	            $this->delete = new A_Db_Sql_Delete();
	        }
	        return $this->delete->table($param);
	    	break;
	    }
	}

}
