<?php

namespace ExampleAPI;

interface IAuthorization
{
	public function tryAuthorize( IAPI $tAPI );
}

?>