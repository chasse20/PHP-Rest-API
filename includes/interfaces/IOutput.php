<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IOutput
{
	public function error( $tCode, $tMessage );
	public function data( $tData );
	public function write();
	public function encode( $tRaw );
}

?>