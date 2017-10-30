<?php

namespace ExampleAPI;

/**
* Handles basic SQL Select by Range
*/
class ActionGetRange implements IAction
{
	/**
	* @var string Expected name of offset variable in URI query, useful for pagination
	*/
	protected $offsetVariable;
	/**
	* @var string Expected name of limit variable in URI query, useful for pagination
	*/
	protected $limitVariable;
	/**
	* @var int Default range offset
	*/
	protected $defaultOffset;
	/**
	* @var int Default range limit
	*/
	protected $defaultLimit;
	
	/**
	* Constructor
	* @param string $tOffsetVariable Expected name of offset variable in URI query
	* @param string $tLimitVariable Expected name of limit variable in URI query
	* @param int|0 $tDefaultOffset Optional default range offset
	* @param int|500 $tDefaultLimit Optional default range limit 
	*/
	public function __construct( $tOffsetVariable, $tLimitVariable, $tDefaultOffset = 0, $tDefaultLimit = 500 )
	{
		$this->offsetVariable = $tOffsetVariable;
		$this->limitVariable = $tLimitVariable;
		$this->defaultOffset = $tDefaultOffset;
		$this->defaultLimit = $tDefaultLimit;
	}
	
	/**
	* Executes an SQL query to get a range of items using any limit and offset inputs
	* @param IAPI $tAPI API that called this function
	* @param IRoute $tRoute Route that called this function
	*/
	public function execute( IAPI $tAPI, IRoute $tRoute )
	{
		$tempConnection = null;
		if ( $tAPI->getConnection()->tryConnect( $tAPI, $tempConnection ) )
		{
			$tempOffset = isset( $_GET[ $this->offsetVariable ] ) && is_numeric( $_GET[ $this->offsetVariable ] ) ? $_GET[ $this->offsetVariable ] : null;
			if ( $tempOffset == null )
			{
				$tempOffset = $this->defaultOffset;
			}
			
			$tempLimit = isset( $_GET[ $this->limitVariable ] ) && is_numeric( $_GET[ $this->limitVariable ] ) ? $_GET[ $this->limitVariable ] : null;		
			if ( $tempLimit == null )
			{
				$tempLimit = $this->defaultLimit;
			}
			
			$tempQueryResult = $tempConnection->query( "SELECT * FROM " . $tRoute->table . " LIMIT " . $tempLimit . " OFFSET " . $tempOffset );
			if ( $tempQueryResult )
			{
				$tempListLength = $tempQueryResult->num_rows;
				if ( $tempListLength > 1 )
				{
					$tempList = [];
					for ( $i = 0; $i < $tempListLength; ++$i )
					{
						$tempList[] = $tempQueryResult->fetch_assoc();
					}
					
					$tAPI->getOutput()->data( $tempList );
				}
				else if ( $tempListLength == 1 )
				{
					$tAPI->getOutput()->data( $tempQueryResult->fetch_assoc() );
				}
				else
				{
					$tAPI->getOutput()->error( 204, "no records found" );
				}
			}
			else
			{
				$tAPI->getOutput()->error( 500, $tempConnection->error );
			}
			
			$tempConnection->close();
		}
	}
}

?>