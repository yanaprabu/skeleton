<?php
/**
 * Db.php
 * 
 * @license	http://www.opensource.org/licenses/bsd-license.php BSD
 * @link	http://skeletonframework.com/
 */

/*

Code based on http://phpsec.org/projects/guide/5.html Copyright PHP Security Consortium

This requires an existing table named sessions, whose format is as follows:

mysql> DESCRIBE sessions;
+--------+------------------+------+-----+---------+-------+
| Field  | Type             | Null | Key | Default | Extra |
+--------+------------------+------+-----+---------+-------+
| id     | varchar(32)      |      | PRI |         |       |
| access | int(10) unsigned | YES  |     | NULL    |       |
| data   | text             | YES  |     | NULL    |       |
+--------+------------------+------+-----+---------+-------+

This database can be created in MySQL with the following syntax:

CREATE TABLE sessions
(
    id varchar(32) NOT NULL,
    access int(10) unsigned,
    data text,
    PRIMARY KEY (id)
);

MEMORY TABLES
Memory is not reclaimed if you delete individual rows from a MEMORY table. 
Memory is reclaimed only when the entire table is deleted. 
Memory that was previously used for rows that have been deleted will be re-used for new rows only within the same table. 
To free up the memory used by rows that have been deleted, use ALTER TABLE ENGINE=MEMORY to force a table rebuild. 

*/

/**
 * A_Session_Db
 * 
 * @package A_Session
 */
class A_Session_Db
{

	function _construct($db)
	{
		$this->db = $db;
	}
		
	function init()
	{
		session_set_save_handler(
			array($this, 'open'),
			array($this, 'close'),
			array($this, 'read'),
			array($this, 'write'),
			array($this, 'destroy'),
			array($this, 'clean')
		);
	}
	
	function open()
	{
		$this->db->connect();
		return false;
	}
	
	function close()
	{
		return $this->db->close();
	}
	
	function read($id)
	{
		$id = $this->db->escape($id);
		$sql = "SELECT data
		        FROM   sessions
		        WHERE  id = '$id'";
		if ($result = $this->db->query($sql)) {
			if ($this->db->num_rows($result)) {
				$record = $this->db->fetch($result);
				return $record['data'];
			}
		}
		
		return '';
	}
	
	function write($id, $data)
	{   
		$access = time();
		
		$id = $this->db->escape($id);
		$access = $this->db->escape($access);
		$data = $this->db->escape($data);
		
		$sql = "REPLACE 
		        INTO    sessions
		        VALUES  ('$id', '$access', '$data')";
		
		return $this->db->query($sql);
	}
	
	function destroy($id)
	{
		$id = $this->db->escape($id);
		
		$sql = "DELETE
		        FROM   sessions
		        WHERE id = '$id'";
		
		return $this->db->query($sql);
	}
	
	function clean($age)
	{
		$time = $this->db->escape(time() - $age);
		
		$sql = "DELETE
		        FROM   sessions
		        WHERE  access < '$time'";
		
		return $this->db->query($sql);
	}

}
