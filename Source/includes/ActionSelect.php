<?php

namespace ExampleAPI;

/**
* Handles a SELECT query with limit and offset
*/
class ActionSelect extends Action
{
	/**
	* @var string Selection range
	*/
	public $selection;
	
	/**
	* @var string Condition statement
	*/
	public $conditions;
	
	/**
	* @var string Pagination statement with LIMIT and OFFSET
	*/
	public $pagination;
	
	/**
	* Constructor
	* @param string $tTable Table name to operate on
	* @param string $tSelection (optional) Selection range, defaults to all
	* @param string $tConditions (optional) Condition statement
	* @param string $tPagination (optional) Pagination statement with LIMIT and OFFSET
	* @param array $tBinds (optional) Associative key-value array for query parameterization
	*/
	public function __construct( $tTable, $tSelection = "*", $tConditions = null, $tPagination = null, $tBinds = null )
	{
		// Inheritance
		parent::__construct( $tTable, $tBinds );
		
		// Set variables
		$this->selection = $tSelection;
		$this->conditions = $tConditions;
		$this->pagination = $tPagination;
	}
	
	/**
	* Executes a query to select an item(s)
	* @param IAPI $tAPI API that called this function
	*/
	public function execute( IAPI $tAPI )
	{
		$tempConnection = null;
		if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
		{
			// Prepare statement
			$tempQuery = "SELECT " . $this->selection . " FROM " . $this->table;
			if ( $this->conditions != null )
			{
				$tempQuery .= " " . $this->conditions;
			}
			
			if ( $this->pagination != null )
			{
				$tempQuery .= " " . $this->pagination;
			}
			
			$tempStatement = $tempConnection->prepare( $tempQuery );
			
			// Execute
			if ( $tempStatement->execute( $this->binds ) )
			{
				$tempList = $tempStatement->fetchAll( \PDO::FETCH_ASSOC );
				if ( $tempList === false || count( $tempList ) == 0 )
				{
					http_response_code( 404 );
				}
				else
				{
					$tAPI->getOutput()->setData( $tempList );
				}
			}
			else
			{
				$tAPI->getOutput()->addError( $tempStatement->errorInfo() );
				http_response_code( 500 );
			}
		}
	}
}

?>