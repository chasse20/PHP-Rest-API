<?php

namespace ExampleAPI;

/**
* Handles an UPDATE query, or a general modification using data input
*/
class ActionUpdate extends Action
{
	/**
	* @var string Condition statement
	*/
	public $conditions;
	
	/**
	* Constructor
	* @param string $tTable Table name to operate on
	* @param string $tConditions (optional) Condition statement
	* @param array $tBinds (optional) Associative key-value array for query parameterization
	*/
	public function __construct( $tTable, $tConditions = null, $tBinds = null )
	{
		// Inheritance
		parent::__construct( $tTable, $tBinds );
		
		// Set variables
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
				// Prepare statement
				$tempStatement = $tempConnection->prepare( $this->query );				
				
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
				
				// Execute
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

?>