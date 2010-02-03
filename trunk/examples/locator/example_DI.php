<?php
include('../config.php');
#include_once 'A/Locator.php';

// classes for use demonstrating DI functionality of A_Locator
class Config {
	public $data = array();
	public function set($name, $value) {
		$this->data[$name] = $value;
	}
	public function get($name) {
		return isset($this->data[$name]) ? $this->data[$name] : null;
	}
}

class BaseModel {
	public $db;
	public $data = array();
	public function __construct($db=null) {
		echo "CALL FUNCTION __construct()<br/>\n";
		$this->db = $db;
	}
	public function set($name, $value) {
		echo "CALL FUNCTION set($name)<br/>\n";
		$this->data[$name] = $value;
	}
	public function setDb($db=null) {
		echo "CALL FUNCTION setDb()<br/>\n";
		$this->db = $db;
	}
}

class FooModel extends BaseModel {}

class BarModel extends BaseModel {}

// create a config object to show how injected data can come from a registered container
$ConfigObj = new Config();
$ConfigObj->set('db', $config['db']);

// create Locator which is Registry + Loader + DI
$locator = new A_Locator();
$locator->set('Config', $ConfigObj);

// register dependency for database connector to inject config array into contructor
// future calls to $locator->get('', 'A_Db_Pdo') will pass array to constructor when instantiating
$locator->register(array( 
		'A_Db_Pdo' => array(
			// directly inject array of data
#			'__construct' => array($config['db']),
			// get data to inject from registered container: $locator->get('Config')->get('db') 
			'__construct' => array(array('A_Locator'=>'container', 'name'=>'Config', 'class'=>'', 'key'=>'db')), 
			), 
		)
	);

// register dependencies for classes that will have A_Db_Pdo object injected
// Note that A_Db_Pdo object is put in Registry with name 'DB' so later call will just get object from Registry
$locator->register(array( 
		'BaseModel' => array(	// constructor injection and setter injection of string 
			'__construct' => array(array('A_Locator'=>'get', 'name'=>'DB', 'class'=>'A_Db_Pdo')), 
			'set' => array('base', 'Data injected into set(base, )'),
			), 
		'FooModel' => array(	// constructor injection and setter injection of string 
			'__construct' => array(array('A_Locator'=>'get', 'name'=>'DB', 'class'=>'A_Db_Pdo')), 
			), 
		'BarModel' => array( 	// setter injection
			'set' => array('bar', 'Data injected into set(bar, )'),
			'setDb' => array(array('A_Locator'=>'get', 'name'=>'DB', 'class'=>'A_Db_Pdo')), 
			), 
		));
?>
<html>
<body>
<?php
$FooModel = $locator->get('', 'FooModel', 'BaseModel');
dump($FooModel, 'FooModel: ');

$BarModel = $locator->get('', 'BarModel');
dump($BarModel, 'BarModel: ');
?>
</body>
</html>