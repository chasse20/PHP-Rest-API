<?php

namespace ExampleAPI;

/**
* Component responsible for validating user authorization
*/
class Authorization implements IAuthorization
{
	/**
	* Validates user authorization
	* @param IAPI $tAPI API that called this function
	* @return bool True if successful
	*/
	public function tryAuthorize( IAPI $tAPI )
	{
		return true;
	}
}

?>
