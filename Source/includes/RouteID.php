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
					( new ActionGet( $this->table, "*", null, "offset", "limit" ) )->execute( $tAPI );
					break;
				case "DELETE":
					( new ActionDelete( $this->table ) )->execute( $tAPI );
					break;
				case "POST":
					( new ActionPost( $this->table ) )->execute( $tAPI );
					break;
				default:
					header( "Allow: GET, DELETE, POST" );
					http_response_code( 405 );
					break;
			}
		}
		else
		{
			switch ( $_SERVER[ "REQUEST_METHOD" ] )
			{
				case "GET":
					( new ActionGet( $this->table, "*", $this->IDColumn . "=" . $tURI[0] ) )->execute( $tAPI );
					break;
				case "DELETE":
					( new ActionDelete( $this->table, $this->IDColumn . "=" . $tURI[0] ) )->execute( $tAPI );
					break;
				case "PATCH":
					( new ActionPatch( $this->table, $this->IDColumn, $tURI[0], $this->IDColumn . "=" . $tURI[0] ) )->execute( $tAPI );
					break;
				default:
					header( "Allow: GET, DELETE, PATCH" );
					http_response_code( 405 );
					break;
			}
		}
	}
}

?>