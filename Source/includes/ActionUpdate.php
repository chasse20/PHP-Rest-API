<?php

namespace ExampleAPI;

/**
* Handles an UPDATE query, or a general modification using data input
*/
class ActionUpdate extends Action
{
	/**
	* @var string Associative array of allowed columns
	*/
	public $columns;
	
	/**
	* @var string Condition statement
	*/
	public $conditions;
	
	/**
	* Constructor
	* @param string $tTable Table name to operate on
	* @param array $tColumns Associative array of allowed columns
	* @param string $tConditions (optional) Condition statement
	* @param array $tBinds (optional) Associative key-value array for query parameterization
	*/
	public function __construct( $tTable, $tColumns, $tConditions = null, $tBinds = null )
	{
		// Inheritance
		parent::__construct( $tTable, $tBinds );
		
		// Set variables
		$this->columns = $tColumns;
		$this->conditions = $tConditions;
	}
	
	/**
	* Executes an query to update an item
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
				// Combine data to binds
				$tempIsBinds = $this->binds != null;
				if ( $tempData != null && $tempIsBinds )
				{
					$tempData = array_merge( $tempData, $this->binds ); // binds have priority over input data
				}
				else if ( $tempIsBinds )
				{
					$tempData = $this->binds;
				}
				
				// Prepare statement
				$tempQuery = null;
				if ( $tempData != null )
				{
					$tempIsComma = false;
					foreach ( $tempData as $tempKey => $tempValue )
					{
						if ( $this->columns[ $tempKey ] )
						{
							if ( $tempQuery == null )
							{
								$tempQuery = " SET ";
							}
							
							if ( $tempIsComma )
							{
								$tempQuery .= ",";
							}
							else
							{
								$tempIsComma = true;
							}

							$tempQuery .= $tempKey . "=:" . $tempKey;
						}
					}
				}
				
				// Attempt to execute
				if ( $tempQuery == null )
				{
					$tAPI->getOutput()->addError( "No valid input data given" );
					http_response_code( 400 );
				}
				else
				{
					$tempQuery = "UPDATE " . $this->table . $tempQuery;
					if ( $this->conditions != null )
					{
						$tempQuery .= " " . $this->conditions;
					}
					
					$tempStatement = $tempConnection->prepare( $tempQuery );
					if ( !$tempStatement->execute( $tempData ) )
					{
						$tAPI->getOutput()->addError( $tempStatement->errorInfo() );
						http_response_code( 500 );
					}
					else if ( $tempStatement->rowCount() == 0 )
					{
						http_response_code( 404 );
					}
					else
					{
						http_response_code( 204 );
					}
				}
			}
		}
	}
}

?>