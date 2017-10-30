<?php

namespace ExampleAPI;

/**
* Helper struct to represent an error message
*/
class Error
{
	/**
	* @var int Status code
	*/
	public $code;
	/**
	* @var string Error message
	*/
	public $message;
	
	/**
	* Constructor
	* @param int $tCode Status code
	* @param string $tMessage Error message
	*/
	public function __construct( $tCode, $tMessage )
	{
		$this->code = $tCode;
		$this->message = $tMessage;
	}
}

?>