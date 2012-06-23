<?php
/**
 * Onduplicatekey.php
 *
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 * @author	Jonah Dahlquist <jonah@nucleussystems.com>
 */

/**
 * A_Sql_Onduplicatekey
 *
 * Build a statement to add to an INSERT to update if a row being inserted violates a unique key
 *
 * @package A_Sql
 */
class A_Sql_Onduplicatekey extends A_Sql_Expression
{

	public function render($logic='')
	{
		if ($this->data) {
			$updates = array();
			foreach ($this->data as $column => $value) {
				if (is_numeric($column)) {
					$updates[] = '`' . $value . '` = VALUES(`' . $value . '`)';
				} else {
					$updates[] = '`' . $column . '` = ' . $this->quoteEscape($value);
				}
			}
			return 'ON DUPLICATE KEY UPDATE ' . implode(', ', $updates);
		}
	}

}
