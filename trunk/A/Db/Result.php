<?php
/**
 * Result.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/**
 * A_Db_Result
 *
 * Represents the result of a query that does not have a result set (INSERT, DELETE, etc.).  Contains error data and the number of rows affected by the query.
 *
 * @package A_Db
 */
class A_Db_Result
{

	protected $numRows;
	protected $error;
	protected $errorMsg;

	public function __construct($numRows, $error, $errorMsg)
	{
		$this->numRows = $numRows;
		$this->error = $error;
		$this->errorMsg = $errorMsg;
	}

	public function numRows()
	{
		return $this->numRows;
	}

	public function isError()
	{
		return $this->error;
	}

	public function getErrorMsg()
	{
		return $this->errorMsg;
	}

	/**
	 * Alias for getErrorMsg()
	 *
	 * @deprecated
	 * @see getErrorMsg
	 */
	public function getMessage()
	{
		return $this->getErrorMsg();
	}

}
