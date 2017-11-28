<?php

namespace ExampleAPI;

/**
* Handles basic SQL Insert
*/
class ActionPost implements IAction
{
	/**
	* Executes an SQL query to insert either a single item or an array of items
	* @param IAPI $tAPI API that called this function
	* @param IRoute $tRoute Route that called this function
	*/
	public function execute( IAPI $tAPI, IRoute $tRoute )
	{
		$tempData = null;
		if ( $tAPI->getData()->tryGet( $tAPI, $tempData ) )
		{
			$tempConnection = null;
			if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
			{
				if ( $tempConnection->query( "INSERT INTO " . $tRoute->table . " " . Utility::SQLKVPs( $tempConnection, $tempData ) ) )
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