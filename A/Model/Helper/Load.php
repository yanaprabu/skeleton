<?php
/**
 * Load.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Model_Helper_Load
 *
 * Allows easy loading of classes used by Model objects.  An instance of this object can be accessed from a model with the _load() method (inherited from A_Model).  To load an object, call it's type as a method, passing it's name as an argument.  For example, calling $loadInstance->lib('MyLib') will load libs/MyLib.php from the specified scope.  Some types' filenames/classnames must be suffixed with their respective names, as shown below:
 *
 * Event suffix: none
 * Helper suffix: Helper (e.x. helper('foo') will load myHelper)
 * Model suffix: Model (e.x. model('foo') will load myModel)
 * Lib suffix: none
 *
 * @package A_Model
 */
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
