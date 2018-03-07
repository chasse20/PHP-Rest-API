<?php

namespace ExampleAPI;

/**
* Handles a single INSERT query that will return an id value if successful
*/
class ActionInsert extends Action
{
	/**
	* @var string Associative array of allowed columns
	*/
	public $columns;
	
	/**
	* Constructor
	* @param string $tTable Table name to operate on
	* @param array $tColumns Associative array of allowed columns
	* @param array $tBinds (optional) Associative key-value array for query parameterization
	*/
	public function __construct( $tTable, $tColumns, $tBinds = null )
	{
		// Inheritance
		parent::__construct( $tTable, $tBinds );
		
		// Set variables
		$this->columns = $tColumns;
	}
	
	/**
	* Executes an query to insert an item
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
				if ( $tempData == null )
				{
					$tempKeys = null;
					$tempValues = null;
					$tempIsComma = false;
					foreach ( $tempData as $tempKey => $tempValue )
					{
						if ( $this->columns[ $tempKey ] )
						{
							if ( $tempKeys == null )
							{
								$tempKeys = "";
								$tempValues = "";
							}
							
							if ( $tempIsComma )
							{
								$tempKeys .= ",";
								$tempValues .= ",";
							}
							else
							{
								$tempIsComma = true;
							}

							$tempKeys .= $tempKey;
							$tempValues .= ":" . $tempKey;
						}
					}

					if ( $tempKeys != null )
					{
						$tempQuery = " (" . $tempKeys . ") VALUES (" . $tempValues . ")";
					}
				}
				
				// Fall back to default values if empty
				if ( $tempQuery == null )
				{
					$tempQuery = " DEFAULT VALUES";
				}
				
				$tempStatement = $tempConnection->prepare( "INSERT INTO " . $this->table . $tempQuery );
				
				// Execute
				if ( $tempStatement->execute( $tempData ) )
				{
					$tAPI->getOutput()->setData( $tempConnection->lastInsertId() );
				}
				else
				{
					$tAPI->getOutput()->addError( $tempStatement->errorInfo() );
					http_response_code( 500 );
				}
			}
		}
	}
}

?>