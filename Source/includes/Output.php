<?php

namespace ExampleAPI;

/**
* Component responsible for handling output
*/
class Output implements IOutput
{
	/**
	* @var string[] Error messages that get returned in the response if specified
	*/
	protected $errors;
	/**
	* @var mixed Data object that gets returned in the response if specified
	*/
	protected $data;
	
	/**
	* Constructor
	* @param string $tServer Server address
	* @param string[] $tErrors (optional) Error messages that get returned in the response if specified, defaults to null
	* @param mixed $tData (optional) Data object that gets returned in the response if specified, defaults to null
	*/
	public function __construct( $tErrors = null, $tData = null )
	{
		if ( !$this->setData( $tData ) )
		{
			$this->effectsData( $this->data );
		}
		
		if ( $tErrors != null )
		{
			$tempListLength = count( $tErrors );
			for ( $i = 0; $i < $tempListLength; ++$i )
			{
				$this->addError( $tErrors[$i] );
			}
		}
	}

	/**
	* Adds an error message
	* @param string|string[] $tError Error message to add
	* @return bool True if successful
	*/
	public function addError( $tError )
	{
		if ( $this->errors == null )
		{
			$this->errors = [];
		}
		
		$this->errors[] = $tError;
		$this->effectsMessageAdded( count( $this->errors ) - 1 );
		
		return true;
	}
	
	/**
	* Handles individual error messages
	* @param int $tIndex Index of added
	*/
	protected function effectsMessageAdded( $tIndex )
	{
	}
	
	/**
	* Setter for response data
	* @param mixed $tData Response data
	* @return bool True if successful
	*/
	public function setData( $tData )
	{
		if ( $this->data != $tData )
		{
			$tempOld = $this->data;
			$this->data = $tData;
			$this->effectsData( $tempOld );
			
			return true;
		}
		
		return false;
	}
	
	/**
	* Handles data
	* @param mixed $tOld Previous response data
	*/
	protected function effectsData( $tOld )
	{
	}
	
	/**
	* Writes the HTML document output if error or data objects are specified
	*/
	public function write()
	{
		$tempIsData = $this->data != null;
		$tempIsError = $this->errors != null;
		if ( $tempIsData && $tempIsError ) // combined data and errors
		{
			$tempOutput = [];
			$tempOutput[ "data" ] = $this->data; 
			$tempOutput[ "errors" ] = $this->errors;
			
			echo $this->encode( $tempOutput );
		}
		else if ( $tempIsData )
		{
			echo $this->encode( $this->data );
		}
		else if ( $tempIsError )
		{
			echo $this->encode( count( $this->errors ) == 1 ? $this->errors[0] : $this->errors ); // don't bother with array notation if just one element
		}
	}
	
	/**
	* Converts raw PHP objects into an encoded format
	* @param mixed $tRaw Raw PHP object
	* @return string Encoded output
	*/
	public function encode( $tRaw )
	{
		return json_encode( $tRaw );
	}
}

?>