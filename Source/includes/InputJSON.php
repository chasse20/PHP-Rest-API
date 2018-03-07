<?php

namespace ExampleAPI;

/**
* Component responsible for retrieving input JSON data
*/
class InputJSON extends Input
{
	public function __construct()
	{
		parent::__construct( "application/json" );
	}
	
	protected function decode( $tRaw )
	{
		return json_decode( $tRaw, true );
	}
}
?>