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
	* @param IConnection $tConnection Reference to Connection instance that will be created
	* @return bool True if successful
	*/
	public function tryAuthorize( IAPI $tAPI, \mysqli $tConnection )
	{
		return true;
	}
}

?>