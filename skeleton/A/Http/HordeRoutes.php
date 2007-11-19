<?php
require_once 'Horde/Routes/Mapper.php';
require_once 'Horde/Routes/Exception.php';
require_once 'Horde/Routes/Route.php';
require_once 'Horde/Routes/Util.php';

/*
*/
class A_Http_HordeRoutes {\
	protected $path;
	protected $mapper;
	
    public function __construct() {
  		$this->mapper = new Horde_Routes_Mapper();

        if (isset($_SERVER['PATH_INFO'])) {
        	$path = $_SERVER['PATH_INFO'];
        } else {
	        $path = $_SERVER['REQUEST_URI'];
	        if (strpos($path, $this->script_extension) !== FALSE) {
				$base = $_SERVER['SCRIPT_NAME'];			// using script name
	        } else {
	            $base = dirname($_SERVER['SCRIPT_NAME']);		// using rewrite rules
	        }
	        if ($base != '/') {
	        	$len = strlen($base) + 1;
	        	$path = substr($path, $len);
	        }
	        if (strstr($path, '?')) {
	            $path = substr($path, 0, strpos($path, '?'));
	        }
        }
        $this->path = trim($path, '/');
	}
	
	public function createRegs($reg) {
		return $this->mapper->createRegs($reg);
	}

	public function connnect($a, $b=null, $c=null, $d=null) {
		return $this->mapper->connnect($a, $b, $c, $d);
	}

	public function setPath($path) {
		$this->path = $path;
	}

	public function run($request) {
		$map = $this->mapper->match($this->path);
		foreach ($map as $param => $value) {
			$request->set($param, $value);
		}
	}
}
