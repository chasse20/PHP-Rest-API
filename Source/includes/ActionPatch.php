<?php

namespace ExampleAPI;

/**
* Handles basic SQL Update by either ID or input
*/
class ActionPatch implements IAction
{
	/**
	* @var string ID for updating a single item
	*/
	protected $id;
	
	/**
	* Constructor
	* @param string $tID ID for updating a single item
	*/
	public function __construct( $tID )
	{
		$this->id = $tID;
	}
	
	/**
	* Executes an SQL query to delete an item using the ID
	* @param IAPI $tAPI API that called this function
	* @param IRoute $tRoute Route that called this function
	*/
	public function execute( IAPI $tAPI, IRoute $tRoute )
	{
		$tempData = null;
		if ( $tAPI->getData()->tryGet( $tAPI, $tempData ) )
		{
			$tempIsIDSet = isset( $tempData->{ $tRoute->IDColumn } );
			if ( $tempIsIDSet && $tempData->{ $tRoute->IDColumn } != $this->id )
			{
				$tAPI->getOutput()->addError( "mismatched ID given in data" );
				http_response_code( 400 );
			}
			else
			{
				$tempConnection = null;
				if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
				{
					if ( $tempIsIDSet )
					{
						unset( $tempData->{ $tRoute->IDColumn } );
					}
					
					if ( !$tempConnection->query( "UPDATE " . $tRoute->table . " " . Utility::SQLSet( $tempConnection, $tempData ) . " WHERE " . $tRoute->IDColumn . "=" . $this->id ) )
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