<?php
require_once 'A/Db/MySQL';
require_once 'A/Db/Wrapper/Abstract';

/**
 * Class for connecting to MySQL databases and performing common database operations.
 *
 * @package A_Db
 */
class A_Db_Wrapper_Mysql extends A_Db_Wrapper_Abstract
{

    /**
     * Constructor.
     *
     * $config is an array of key/value pairs or an instance of A_DataContainer
     * containing configuration options.  These options are common to most adapters:
     *
     * dbname         => (string) The name of the database to user
     * username       => (string) Connect to the database as this username.
     * password       => (string) Password associated with the username.
     * host           => (string) What host to connect to, defaults to localhost
     *
     * @param  array|A_Config $config
     * @throws A_Db_Exception
     */
    public function __construct($config, $exceptions=false) {
        $this->setConfig($config, $exceptions);
        if (! $this->error) {
        	$this->db = new A_Db_MySQL($config); 
        }
    }

}