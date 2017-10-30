<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IData
{
	public function tryGet( IAPI $tAPI, &$tData );
}

?>