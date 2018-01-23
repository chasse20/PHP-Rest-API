<?php

namespace ExampleAPI;

class ExampleAPI extends API
{
	/**
	* @var string URL for help reference
	*/
	public $helpLink = "http://www.chassebrook.com/ExampleAPI/help/";
	
	protected function createConnection()
	{
		return new Connection( "localhost", "username", "password", "example_api" );
	}
	
	protected function createAuthorization()
	{
		return new Authorization();
	}
	
	protected function createInput()
	{
		return new InputJSON();
	}
	
	protected function createOutput()
	{
		return new OutputJSON();
	}
	
	protected function executeRoute( $tURI )
	{
		// Invalid route
		if ( $tURI == null || empty( $tURI[0] ) )
		{
			echo "No route specified, see <a href='" . $this->helpLink . "'>API help</a> for more information";
			http_response_code( 400 );
		}
		// Valid routes
		else
		{
			switch ( $tURI[0] )
			{
				case "inventory":
					$this->executeSubRoute( $tURI, "inventory", "id" );
					break;
				case "item":
					$this->executeSubRoute( $tURI, "items", "id" );
					break;
				case "player":
					$this->executeSubRoute( $tURI, "players", "id" );
					break;
				default:
					echo "Invalid route, see <a href='" . $this->helpLink . "'>API help</a> for more information";
					http_response_code( 400 );
					break;
			}
		}
	}
	
	/**
	* Processes RESTful actions using the request method and possible path
	* @param string[] $tURI URI array of paths
	* @param string $tTable Primary table name to operate on
	* @param string $tIDColumn Name of the table's ID column
	*/
	protected function executeSubRoute( $tURI, $tTable, $tIDColumn )
	{
		// No /{id}
		if ( empty( $tURI[1] ) )
		{
			switch ( $_SERVER[ "REQUEST_METHOD" ] )
			{
				case "GET":
					( new ActionGet( $tTable, "*", null, "offset", "limit" ) )->execute( $this );
					break;
				case "POST":
					( new ActionPost( $tTable ) )->execute( $this );
					break;
				default:
					header( "Allow: GET, POST" );
					http_response_code( 405 );
					break;
			}
		}
		// {id} given
		else
		{
			switch ( $_SERVER[ "REQUEST_METHOD" ] )
			{
				case "GET":
					( new ActionGet( $tTable, "*", $tIDColumn . "=" . $tURI[1] ) )->execute( $this );
					break;
				case "DELETE":
					( new ActionDelete( $tTable, $tIDColumn . "=" . $tURI[1] ) )->execute( $this );
					break;
				case "PATCH":
					( new ActionPatch( $tTable, $tIDColumn, $tURI[1], $tIDColumn . "=" . $tURI[1] ) )->execute( $this );
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