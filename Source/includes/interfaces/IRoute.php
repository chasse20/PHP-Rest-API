<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IRoute
{
	public function execute( IAPI $tAPI, $tURI );
}

?>