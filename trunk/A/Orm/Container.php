<?php
/**
 * Container.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Cory Kaufman
 */

/**
 * A_Orm_Container
 *
 * @package A_Orm
 */
class A_Orm_Container
{
	protected $db;
	protected $instances;
	protected $handlers;
	protected $pendingHandler = '';

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function get($className, $params=array())
	{
		array_unshift($params, $this->db);
		if ($this->instances[$className]) {
			return $this->instances[$className];
		} elseif (class_exists($this->handlers[$className])) {
			$handler = $this->handlers[$className];
		} elseif (class_exists($className . 'Mapper')) {
			$handler = $className . 'Mapper';
		}
		$class = new ReflectionClass($handler);
		if ($class->getConstructor()) {
			$this->instances[$className] = $class->newInstanceArgs($params);
		} else {
			$this->instances[$className] = $class->newInstance();
		}
		return $this->instances[$className];
	}

	public function setHandler($handler)
	{
		$this->pendingHandler = $handler;
		return $this;
	}

	public function forClass($className)
	{
		if (!$this->pendingHandler) {
			throw new Exception ('must call setHandler() before calling forClass()');
		}
		$this->handlers[$className] = $this->pendingHandler;
		$this->pendingHandler = '';
	}

	public function getDb()
	{
		return $this->db;
	}

}
