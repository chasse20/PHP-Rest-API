<?php

namespace ExampleAPI;

/**
* Component responsible for handling the root URI routing
*/
class Route implements IRoute
{
	/**
	* Processes specific paths of the root route
	* @param IAPI $tAPI API that called this function
	* @param string|string[] $tURI URI array of paths
	*/
	public function execute( IAPI $tAPI, $tURI )
	{
		if ( $tURI == null || empty( $tURI[0] ) )
		{
			$tAPI->getOutput()->error( 400, "missing URI... available routes are: 'inventory' or 'item' or 'player'" );
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
					$tAPI->getOutput()->error( 400, "route not recognized... available routes are: 'inventory' or 'item' or 'player'" );
					break;
			}
		}
	}
}

?>