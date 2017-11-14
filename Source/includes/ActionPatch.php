<?php

namespace ExampleAPI;

/**
* Handles basic SQL Update by either ID or input
*/
class ActionPatch implements IAction
{
	/**
	* @var string Optional ID for updating a single item
	*/
	protected $id;
	
	/**
	* Constructor
	* @param string|null $tID Optional ID for updating a single item
	*/
	public function __construct( $tID = null )
	{
		$this->id = $tID;
	}
	
	/**
	* Executes an SQL query to delete an item using the ID (either specified or from the input), or an array of items
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
					$tempQueryResult = $tempConnection->multi_query( Utility::SQLUpdateArray( $tempConnection, $tempData, $tRoute->table, $tRoute->IDColumn ) );
					
					// First
					if ( !$tempQueryResult )
					{
						$tAPI->getOutput()->error( 400, "0: " . $tempConnection->error );
					}
					
					// Subsequent
					if ( $tempConnection->more_results() )
					{
						$i = 1;
						do
						{
							if ( !$tempConnection->next_result() )
							{
								$tAPI->getOutput()->error( 400, $i . ": " . $tempConnection->error );
							}
							
							++$i;
						} while ( $tempConnection->more_results() );
					}
				}
				// Single
				else
				{
					$tempIsID = $this->id != null;
					$tempIsDataID = isset( $tempData->{ $tRoute->IDColumn } );
					if ( $tempIsID || $tempIsDataID )
					{
						$tempID = $tempIsID ? $this->id : $tempData->{ $tRoute->IDColumn };
						if ( $tempIsDataID )
						{
							unset( $tempData->{ $tRoute->IDColumn } );
						}
						
						if ( !$tempConnection->query( "UPDATE " . $tRoute->table . " " . Utility::SQLSet( $tempConnection, $tempData ) . " WHERE " . $tRoute->IDColumn . "=" . $tempID ) )
						{
							$tAPI->getOutput()->error( 500, $tempConnection->error );
						}
						else if ( $tempConnection->affected_rows == 0 )
						{
							$tAPI->getOutput()->error( 204, "no record found" );
						}
					}
					else
					{
						$tAPI->getOutput()->error( 500, "missing 'id' input" );
					}
				}
				
				$tempConnection->close();
			}
		}
	}
}

?>