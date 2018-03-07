<?php

namespace ExampleAPI;

interface IConnection
{
	public function tryConnect( IAPI $tAPI, &$tConnection );
}

?>