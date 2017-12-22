<?php

namespace ExampleAPI;

/**
* Handles basic SQL Update by either ID or input
*/
class ActionPatch implements IAction
{
	/**
	* @var string Primary table name to patch to
	*/
	public $table;
	
	/**
	* @var string Name of the table's ID column
	*/
	public $IDColumn;
	
	/**
	* @var string ID for patching a single item
	*/
	public $id;
	
	/**
	* @var string Conditions for patching
	*/
	public $conditions;
	
	/**
	* Constructor
	* @param string $tTable Primary table name to select from
	* @param string $tIDColumn Name of the table's ID column
	* @param string $tID ID for patching a single item
	* @param string $tConditions Conditions for patching
	*/
	public function __construct( $tTable, $tIDColumn, $tID, $tConditions )
	{
		$this->table = $tTable;
		$this->IDColumn = $tIDColumn;
		$this->id = $tID;
		$this->conditions = $tConditions;
	}
	
	/**
	* Executes an SQL query to delete an item using the ID
	* @param IAPI $tAPI API that called this function
	*/
	public function execute( IAPI $tAPI )
	{
		$tempData = null;
		if ( $tAPI->getData()->tryGet( $tAPI, $tempData ) )
		{
			// Check to see if ID of data mismatches ID given in URL
			$tempIsIDSet = isset( $tempData->{ $this->IDColumn } );
			if ( $tempIsIDSet && $tempData->{ $this->IDColumn } != $this->id )
			{
				$tAPI->getOutput()->addError( "mismatched ID given in data" );
				http_response_code( 400 );
			}
			else
			{
				$tempConnection = null;
				if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
				{
					// Remove ID in data if exists
					if ( $tempIsIDSet )
					{
						unset( $tempData->{ $this->IDColumn } );
					}
					
					// Build query
					$tempQuery = "UPDATE " . $this->table . " " . Utility::SQLSet( $tempConnection, $tempData );
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
	}
}

?>