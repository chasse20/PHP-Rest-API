<?php

namespace ExampleAPI;

/**
* Component responsible for retrieving JSON input data
*/
class DataJSON implements IData
{
	/**
	* Attempts to read JSON input
	* @param IAPI $tAPI API that called this function
	* @param stdclass $tData Reference to output JSON data that will be filled
	* @return bool True if successful
	*/
	public function tryGet( IAPI $tAPI, &$tData )
	{
		if ( !isset( $_SERVER[ "CONTENT_TYPE" ] ) || $_SERVER[ "CONTENT_TYPE" ] != "application/json" )
		{
			$tAPI->getOutput()->error( 406, "not 'application/json' header type" );
			return false;
		}
	
		$tData = json_decode( file_get_contents( "php://input" ) );
		if ( $tData == null )
		{
			$tAPI->getOutput()->error( 400, "missing 'data' input" );
			return false;
		}
		
		return true;
	}
}

?>