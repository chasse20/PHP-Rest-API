<?php

namespace ExampleAPI;

/**
* Handles basic SQL Delete by either ID or input
*/
class ActionDelete implements IAction
{
	/**
	* @var string Optional ID for deleting a single item
	*/
	protected $id;
	
	/**
	* Constructor
	* @param string|null $tID Optional ID for deleting a single item
	*/
	public function __construct( $tID = null )
	{
		$this->id = $tID;
	}
	
	/**
	* Executes an SQL query to delete an item using a specific ID or array of item indices passed in as POST data
	* @param IAPI $tAPI API that called this function
	* @param IRoute $tRoute Route that called this function
	*/
	public function execute( IAPI $tAPI, IRoute $tRoute )
	{		
		// JSON
		if ( $this->id == null )
		{
			$tempData = null;
			if ( $tAPI->getData()->tryGet( $tAPI, $tempData ) )
			{
				$tempConnection = null;
				if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
				{
					// Multi
					$tempQuery = null;
					if ( is_array( $tempData ) )
					{
						$tempQuery = "DELETE FROM " . $tRoute->table . " WHERE " . $tRoute->IDColumn . " IN " . Utility::SQLArray( $tempConnection, $tempData );
					}
					// Single
					else
					{
						$tempQuery = "DELETE FROM " . $tRoute->table . " WHERE " . $tRoute->IDColumn . "=" . $tempData->{ $tRoute->IDColumn };
					}
					
					if ( !$tempConnection->query( $tempQuery ) )
					{
						$tAPI->getOutput()->error( 500, $tempConnection->error );
					}
					else if ( $tempConnection->affected_rows == 0 )
					{
						$tAPI->getOutput()->error( 204, "no records found" );
					}
					
					$tempConnection->close();
				}
			}
		}
		// Single
		else
		{
			$tempConnection = null;
			if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
			{
				if ( !$tempConnection->query( "DELETE FROM " . $tRoute->table . " WHERE " . $tRoute->IDColumn . "=" . $this->id ) )
				{
					$tAPI->getOutput()->error( 500, $tempConnection->error );
				}
				else if ( $tempConnection->affected_rows == 0 )
				{
					$tAPI->getOutput()->error( 204, "no records found" );
				}
				
				$tempConnection->close();
			}
		}
	}
}

?>