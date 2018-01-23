<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IInput
{
	public function tryGet( IAPI $tAPI, &$tData );
}

?>