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
	* @var bool If true, attempts to fall back to default values if empty data given
	*/
	public $isDefaultFallback;
	
	/**
	* Constructor
	* @param string $tTable Table name to operate on
	* @param array $tColumns Associative array of allowed columns
	* @param bool $tIsDefaultFallback If true, attempts to fall back to default values if empty data given
	* @param array $tBinds (optional) Associative key-value array for query parameterization
	*/
	public function __construct( $tTable, $tColumns, $tIsDefaultFallback = true, $tBinds = null )
	{
		// Inheritance
		parent::__construct( $tTable, $tBinds );
		
		// Set variables
		$this->columns = $tColumns;
		$this->isDefaultFallback = $tIsDefaultFallback;
	}
	
	/**
	* Executes an query to insert an item
	* @param IAPI $tAPI API that called this function
	*/
	public function execute( IAPI $tAPI )
	{
		$tempData = null;
		if ( $tAPI->getInput()->tryGet( $tAPI, $tempData ) && $this->processData( $tAPI, $tempData ) )
		{
			$tempConnection = null;
			if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
			{
				// Prepare statement
				$tempQuery = null;
				if ( $tempData != null )
				{
					$tempKeys = null;
					$tempValues = null;
					$tempIsComma = false;
					foreach ( $tempData as $tempKey => $tempValue )
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

					if ( $tempKeys != null )
					{
						$tempQuery = " (" . $tempKeys . ") VALUES (" . $tempValues . ")";
					}
				}
				
				// Fall back to default values if empty
				if ( $tempQuery == null )
				{
					if ( $isDefaultFallback )
					{
						$tempQuery = " DEFAULT VALUES";
					}
					else
					{
						$tAPI->getOutput()->addError( "No valid input data given" );
						http_response_code( 400 );
						return;
					}
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
	
	/**
	* Processes and validates input data
	* @param IAPI $tAPI API that called this function
	* @param stdclass $tData Reference to output data that will be processed and validated
	* @return bool True if data is valid
	*/
	protected function processData( IAPI $tAPI, &$tData )
	{		
		// Clean data from unwanted columns and try to merge with binds array
		$tempIsBinds = $this->binds != null;
		if ( $tData != null )
		{
			$tempValidData = null;
			foreach ( $tData as $tempKey => $tempValue )
			{
				if ( $this->columns[ $tempKey ] )
				{
					if ( $tempValidData == null )
					{
						$tempValidData = [];
					}
					$tempValidData[ $tempKey ] = $tempValue;
				}
			}
			
			$tData = $tempValidData;
			if ( $tempIsBinds && $tData != null )
			{
				$tData = array_merge( $tempValidData, $this->binds ); // binds have priority over input data
				return true;
			}
		}
		
		if ( $tempIsBinds )
		{
			$tData = $this->binds;
		}
		
		return true;
	}
}

?>