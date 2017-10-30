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
				// Multi
				if ( is_array( $tempData ) )
				{
					$tempQueryResult = $tempConnection->multi_query( Utility::SQLInsertArray( $tempConnection, $tempData, $tRoute->table ) );
					
					// First
					$tempResults = null;
					if ( $tempQueryResult )
					{
						$tempResults = new \stdclass();
						$tempResults->{ 0 } = $tempConnection->insert_id;
					}
					else
					{
						$tAPI->getOutput()->error( 400, "0: " . $tempConnection->error );
					}
					
					// Subsequent
					if ( $tempConnection->more_results() )
					{
						if ( $tempResults == null )
						{
							$tempResults = new \stdclass();
						}
						
						$i = 1;
						do
						{
							if ( $tempConnection->next_result() )
							{
								$tempResults->{ $i } = $tempConnection->insert_id;
							}
							else
							{
								$tAPI->getOutput()->error( 400, $i . ": " . $tempConnection->error );
							}
							
							++$i;
						} while ( $tempConnection->more_results() );
					}
					
					if ( $tempResults != null )
					{
						$tAPI->getOutput()->data( $tempResults );
					}
				}
				// Single
				else if ( $tempConnection->query( "INSERT INTO " . $tRoute->table . " " . Utility::SQLKVPs( $tempConnection, $tempData ) ) )
				{
					$tAPI->getOutput()->data( $tempConnection->insert_id );
				}
				else
				{
					$tAPI->getOutput()->error( 500, $tempConnection->error );
				}
				
				$tempConnection->close();
			}
		}
	}
}

?>