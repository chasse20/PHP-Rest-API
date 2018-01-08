<?php

namespace ExampleAPI;

/**
* Component responsible for handling the root URI routing
*/
class Route implements IRoute
{
	/**
	* @var string URL for help reference
	*/
	public $helpLink;
	
	/**
	* Constructor
	* @param string $tHelpLink URL for help reference
	*/
	public function __construct( $tHelpLink )
	{
		$this->helpLink = $tHelpLink;
	}
	
	/**
	* Processes specific paths of the root route
	* @param IAPI $tAPI API that called this function
	* @param string|string[] $tURI URI array of paths
	*/
	public function execute( IAPI $tAPI, $tURI )
	{
		if ( $tURI == null || empty( $tURI[0] ) )
		{
			echo "No route specified, see <a href='" . $this->helpLink . "'>API help</a> for more information";
			http_response_code( 400 );
		}
		else
		{
			switch ( $tURI[0] )
			{
				case "inventory":
					array_shift( $tURI );
					( new RouteID( "inventory", "id" ) )->execute( $tAPI, $tURI );
					break;
				case "item":
					array_shift( $tURI );
					( new RouteID( "items", "id" ) )->execute( $tAPI, $tURI );
					break;
				case "player":
					array_shift( $tURI );
					( new RouteID( "players", "id" ) )->execute( $tAPI, $tURI );
					break;
				default:
					echo "Invalid route, see <a href='" . $this->helpLink . "'>API help</a> for more information";
					http_response_code( 400 );
					break;
			}
		}
	}
}

?>