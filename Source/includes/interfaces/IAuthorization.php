<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IAuthorization
{
	public function tryAuthorize( IAPI $tAPI, \mysqli $tConnection );
}

?>