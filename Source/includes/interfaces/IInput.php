<?php
namespace ExampleAPI;

interface IInput
{
	public function tryGet( IAPI $tAPI, &$tData );
}
?>