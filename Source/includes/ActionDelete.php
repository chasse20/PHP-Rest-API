<?php

namespace ExampleAPI;

/**
* Handles basic SQL Delete by either ID or input
*/
class ActionDelete implements IAction
{
	/**
	* @var string ID for deleting a single item
	*/
	protected $id;
	
	/**
	* Constructor
	* @param string $tID ID for deleting a single item
	*/
	public function __construct( $tID )
	{
		$this->id = $tID;
	}
	
	/**
	* Executes an SQL query to delete an item using a specific ID
	* @param IAPI $tAPI API that called this function
	* @param IRoute $tRoute Route that called this function
	*/
	public function execute( IAPI $tAPI, IRoute $tRoute )
	{
		$tempConnection = null;
		if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
		{
			if ( !$tempConnection->query( "DELETE FROM " . $tRoute->table . " WHERE " . $tRoute->IDColumn . "=" . $this->id ) )
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