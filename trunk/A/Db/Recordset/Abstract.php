<?php
/**
 * Database recordset set (abstract class)
 * 
 * This class extends A_Collection to create a set of results from a sql
 * query.  Specific databases must have result classes that extend this
 * one, creating the methods defined here.
 * 
 * @package A_Db_Recordset
 * @author Jonah Dahlquist <jonah@nucleussystems.com>
 */
abstract class A_Db_Recordset_Abstract extends A_Collection
{
	
}