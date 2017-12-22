<?php

namespace ExampleAPI;

/**
* Handles basic SQL Delete by either ID or input
*/
class ActionDelete implements IAction
{
	/**
	* @var string Primary table name to delete from
	*/
	public $table;
	
	/**
	* @var string Conditions for deleting
	*/
	public $conditions;

	/**
	* Constructor
	* @param string $tTable Primary table name to delete from
	* @param string $tConditions (optional) Conditions for deleting
	*/
	public function __construct( $tTable, $tConditions )
	{
		$this->table = $tTable;
		$this->conditions = $tConditions;
	}
	
	/**
	* Executes an SQL query to delete an item using a specific ID
	* @param IAPI $tAPI API that called this function
	*/
	public function execute( IAPI $tAPI )
	{
		$tempConnection = null;
		if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
		{
			// Build query
			$tempQuery = "DELETE FROM " . $this->table;
			if ( !empty( $this->conditions ) )
			{
				$tempQuery .= " WHERE " . $this->conditions;
			}
			
			// Query
			if ( !$tempConnection->query( $tempQuery ) )
			{
				$tAPI->getOutput()->addError( $tempConnection->error );
				http_response_code( 500 );
			}
			else if ( $tempConnection->affected_rows == 0 )
			{
				http_response_code( 404 );
			}
			else
			{
				http_response_code( 204 );
			}
			
			$tempConnection->close();
		}
	}
}

?>