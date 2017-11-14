<?php

namespace ExampleAPI;

/**
* Handles generic RESTful routing
*/
class RouteID implements IRoute
{
	/**
	* @var string Primary table name to operate on
	*/
	public $table;
	/**
	* @var string Name of the table's ID column
	*/
	public $IDColumn;
	
	/**
	* Constructor
	* @param string $tTable Primary table name to operate on
	* @param string $tIDColumn Name of the table's ID column
	*/
	public function __construct( $tTable, $tIDColumn )
	{
		$this->table = $tTable;
		$this->IDColumn = $tIDColumn;
	}
	
	/**
	* Processes RESTful actions using the request method and possible path
	* @param IAPI $tAPI API that called this function
	* @param string|string[] $tURI URI array of paths
	*/
	public function execute( IAPI $tAPI, $tURI )
	{
		if ( $tURI == null || empty( $tURI[0] ) )
		{
			switch ( $_SERVER[ "REQUEST_METHOD" ] )
			{
				case "GET":
					( new ActionGetRange( "offset", "limit" ) )->execute( $tAPI, $this );
					break;
				case "POST":
					( new ActionPost() )->execute( $tAPI, $this );
					break;
				case "PATCH":
					( new ActionPatch() )->execute( $tAPI, $this );
					break;
				case "DELETE":
					( new ActionDelete() )->execute( $tAPI, $this );
					break;
				default:
					$tAPI->getOutput()->error( 405, "unsupported request type" );
					break;
			}
		}
		else
		{
			switch ( $_SERVER[ "REQUEST_METHOD" ] )
			{
				case "GET":
					( new ActionGet( $tURI[0] ) )->execute( $tAPI, $this );
					break;
				case "DELETE":
					( new ActionDelete( $tURI[0] ) )->execute( $tAPI, $this );
					break;
				case "PATCH":
					( new ActionPatch( $tURI[0] ) )->execute( $tAPI, $this );
					break;
				default:
					$tAPI->getOutput()->error( 405, "unsupported request type" );
					break;
			}
		}
	}
}

?>