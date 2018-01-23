<?php

namespace ExampleAPI;

/**
* Component responsible for handling JSON output
*/
class OutputJSON extends Output
{
	public function encode( $tRaw )
	{
		header( "Content-Type: application/json" );
		return json_encode( $tRaw );
	}
}

?>