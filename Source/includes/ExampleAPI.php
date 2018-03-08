<?php

namespace ExampleAPI;

class ExampleAPI extends API
{	
	protected function createConnection()
	{
		return new ConnectionMySQL( "localhost", "example_database", "example_user", "example_password" );
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
	
	/**
	* Create link for help website relative to the API
	* @return string Direct link of the API help
	*/
	protected function getHelpLink()
	{
		return "http://" . $_SERVER[ "HTTP_HOST" ] . $_SERVER[ "SCRIPT_NAME" ] . "/../help/";
	}
	
	protected function executeRoute( $tURI )
	{
		// Invalid route
		if ( $tURI == null || empty( $tURI[0] ) )
		{
			echo "No route specified, see <a href='" . $this->getHelpLink() . "'>API help</a> for more information";
			http_response_code( 400 );
		}
		// Valid routes
		else
		{
			switch ( $tURI[0] )
			{
				case "inventory":
					$this->executeSubRoute( $tURI, "inventory", [ "item" => true, "owner" => true, "amount" => true ] );
					break;
				case "item":
					$this->executeSubRoute( $tURI, "items", [ "name" => true ] );
					break;
				case "player":
					$this->executeSubRoute( $tURI, "players", [ "name" => true ] );
					break;
				default:
					echo "Invalid route, see <a href='" . $this->getHelpLink() . "'>API help</a> for more information";
					http_response_code( 400 );
					break;
			}
		}
	}
	
	/**
	* Processes RESTful actions using the request method and possible path
	* @param string[] $tURI URI array of paths
	* @param string $tTable Primary table name to operate on
	* @param array $tColumns Associative array of valid column names to modify
	*/
	protected function executeSubRoute( $tURI, $tTable, $tColumns )
	{
		// No /{id}
		if ( !isset( $tURI[1] ) )
		{
			switch ( $_SERVER[ "REQUEST_METHOD" ] )
			{
				case "GET":
					( new ActionSelect( $tTable, "*", null, Utility::BuildLimitOffset() ) )->execute( $this );
					break;
				case "POST":
					( new ActionInsert( $tTable, $tColumns ) )->execute( $this );
					break;
				case "OPTIONS":
					header( "Allow: GET, POST, OPTIONS" );
					http_response_code( 204 );
					break;
				default:
					header( "Allow: GET, POST" );
					http_response_code( 405 );
					break;
			}
		}
		// Proper {id}
		else if ( is_numeric( $tURI[1] ) )
		{
			switch ( $_SERVER[ "REQUEST_METHOD" ] )
			{
				case "GET":
					( new ActionSelect( $tTable, "*", "WHERE id=" . (int)$tURI[1] ) )->execute( $this );
					break;
				case "DELETE":
					( new ActionDelete( $tTable, "WHERE id=" . (int)$tURI[1] ) )->execute( $this );
					break;
				case "PATCH":
					( new ActionUpdate( $tTable, $tColumns, "WHERE id=" . (int)$tURI[1] ) )->execute( $this );
					break;
				case "OPTIONS":
					header( "Allow: GET, DELETE, PATCH, OPTIONS" );
					http_response_code( 204 );
					break;
				default:
					header( "Allow: GET, DELETE, PATCH, OPTIONS" );
					http_response_code( 405 );
					break;
			}
		}
		// Invalid
		else
		{
			http_response_code( 400 );
			$this->getOutput()->addError( "Must provide id as an integer." );
		}
	}
}

?>