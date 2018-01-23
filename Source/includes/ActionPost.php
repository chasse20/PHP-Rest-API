<?php

namespace ExampleAPI;

/**
* Handles basic SQL Insert
*/
class ActionPost implements IAction
{
	/**
	* @var string Primary table name to insert into
	*/
	public $table;
	
	/**
	* Constructor
	* @param string $tTable Primary table name to insert into
	*/
	public function __construct( $tTable )
	{
		$this->table = $tTable;
	}
	
	/**
	* Executes an SQL query to insert either a single item or an array of items
	* @param IAPI $tAPI API that called this function
	*/
	public function execute( IAPI $tAPI )
	{
		$tempData = null;
		if ( $tAPI->getInput()->tryGet( $tAPI, $tempData ) )
		{
			$tempConnection = null;
			if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
			{
				if ( $tempConnection->query( "INSERT INTO " . $this->table . " " . Utility::SQLKVPs( $tempConnection, $tempData ) ) )
				{
					$tAPI->getOutput()->setData( $tempConnection->insert_id );
				}
				else
				{
					$tAPI->getOutput()->addError( $tempConnection->error );
					http_response_code( 500 );
				}
				
				$tempConnection->close();
			}
		}
	}
}

?>