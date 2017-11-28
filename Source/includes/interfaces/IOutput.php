<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IOutput
{
	public function addError( $tError );
	public function setData( $tData );
	public function write();
	public function encode( $tRaw );
}

?>