<?php

namespace ExampleAPI;

/**
* Component responsible for retrieving input data
*/
class Input implements IInput
{
	/**
	* @var string Type of content/format expected from the user
	*/
	protected $contentType;
	
	/**
	* Constructor
	* @param string $tContentType Type of content/format expected from the user
	*/
	public function __construct( $tContentType )
	{
		$this->contentType = $tContentType;
	}
	
	/**
	* Attempts to read input
	* @param IAPI $tAPI API that called this function
	* @param stdclass $tData Reference to output data that will be filled
	* @return bool True if successful
	*/
	public function tryGet( IAPI $tAPI, &$tData )
	{
		if ( isset( $_SERVER[ "CONTENT_TYPE" ] ) && $_SERVER[ "CONTENT_TYPE" ] == $this->contentType )
		{
			$tData = $this->decode( file_get_contents( "php://input" ) );
			if ( $tData != null )
			{
				return true;
			}
		}
		
		header( "Accept: " . $this->contentType );
		http_response_code( 415 );
		
		return false;
	}
	
	protected function decode( $tRaw )
	{
		return $tRaw;
	}
}

?>