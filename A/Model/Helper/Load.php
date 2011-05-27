<?php

class A_Model_Helper_Load
{

	protected $typeDirs = array(
		'event'=>'events/', 
		'helper'=>'helpers/', 
		'model'=>'models/',  
		'lib'=>'libs/',
	);
	protected $typeSuffixes = array(
		'model'=>'Model', 
		'helper'=>'Helper', 
		'lib'=>'', 
	);
	
	protected $locator;
	protected $scope;
	protected $appPath;
	
	public function __construct($locator, $scope=null, $appPath=null)
	{
		if (!$locator) {
			throw new InvalidArgumentException('Locator cannot be null');
		}
		$this->locator = $locator;
		$this->setScope($scope);
	}
	
	public function setScope($scope=null)
	{
		$this->scope = $scope;
	}
	
	public function setClassDir($type, $dir)
	{
		$this->typeDirs[$type] = $dir ? (rtrim($dir, '/') . '/') : '';
	}
	
	public function setAppPath($path)
	{
		$this->appPath = $path;
	}
	
	public function setTypeSuffix($type, $suffix)
	{
		$this->typeSuffixes[$type] = $suffix;
	}
	
	public function __call($type, $arguments)
	{
		if (!isset($this->typeDirs[$type])) {
			throw new InvalidArgumentException('Invalid load type');
		}
		
		$path = $this->appPath . $this->typeDirs[$type];
		$className = $arguments[0] . (isset($this->typeSuffixes[$type]) ? $this->typeSuffixes[$type] : '');
		
		echo $path;
		echo '<br>';
		echo $className;
		$object = null;
		
		//$classIsLoaded = $this->locator->loadClass($class, $path);
		//if ($classIsLoaded) {
		//	$object = new $class(isset($args[1]) ? $args[1] : $this->locator);
		//}
		
		if ($object) {
			if (method_exists($object, 'setLocator')) {
				$object->setLocator($this->locator);
			}
		} else {
			throw new Exception('Could not create object');
		}
		
		return $object;
	}
	
}