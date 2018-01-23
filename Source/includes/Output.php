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
		$this->setData( $tData );
		
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
		
		return true;
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
			
			return true;
		}
		
		return false;
	}
	
	/**
	* Writes the HTML document output if error or data objects are specified
	* @return string Outputs any data or errors
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
			
			return $this->encode( $tempOutput );
		}
		else if ( $tempIsData )
		{
			return $this->encode( $this->data );
		}
		else if ( $tempIsError )
		{
			return $this->encode( count( $this->errors ) == 1 ? $this->errors[0] : $this->errors ); // don't bother with array notation if just one element
		}
		
		return null;
	}
	
	/**
	* Converts raw PHP objects into an encoded format
	* @param mixed $tRaw Raw PHP object
	* @return string Encoded output
	*/
	public function encode( $tRaw )
	{
		return $tRaw;
	}
}

?>