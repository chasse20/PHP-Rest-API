<?php

namespace ExampleAPI;

/**
* Abstract class for performing a query and database parameterization
*/
abstract class Action implements IAction
{
	/**
	* @var string Table name to operate on
	*/
	public $table;
	
	/**
	* @var array Associative key-value array for query parameterization
	*/
	public $binds;
	
	/**
	* Constructor
	* @param string $tTable Table name to operate on
	* @param array $tBinds (optional) Associative key-value array for query parameterization
	*/
	public function __construct( $tTable, $tBinds = null )
	{
		$this->table = $tTable;
		$this->binds = $tBinds;
	}
}

?>