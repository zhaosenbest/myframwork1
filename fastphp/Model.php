<?php

class Model extends Sql
{
	protected $_model;
	protected $_table;
	public static $_dbConfig = [];

	public function __construct()
	{
		$this->connect(self::$dbConfig['host'],self::$dbConfig['username'],self::$dbConfig['password'],self::$dbConfig['dbname']);
		
		//如果没有指定table，则把model名作为table
		if(!$this->_table){
			$this->_model = get_class($this);

			$this->_model = substr($this->_model,0,-5);

			$this->_table = strtolower($this->_model);
		}
	}

	public static function setDbConfig($config)
    {
        self::$_dbConfig = $config;
    }
}