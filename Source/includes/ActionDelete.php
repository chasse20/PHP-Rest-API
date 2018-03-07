<?php

namespace ExampleAPI;

/**
* Handles a DELETE query, or a general modification without data input
*/
class ActionDelete extends Action
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
	* Executes an query to delete an item(s)
	* @param IAPI $tAPI API that called this function
	*/
	public function execute( IAPI $tAPI )
	{
		$tempConnection = null;
		if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
		{
			// Prepare statement
			$tempQuery = "DELETE FROM " . $tTable;
			if ( $this->conditions != null )
			{
				$tempQuery .= " " . $this->conditions;
			}
			
			$tempStatement = $tempConnection->prepare( $tempQuery );
			
			// Execute
			if ( !$tempStatement->execute( $this->binds ) )
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

?>