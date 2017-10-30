<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IConnection
{
	public function tryConnect( IAPI $tAPI, &$tConnection );
}

?>