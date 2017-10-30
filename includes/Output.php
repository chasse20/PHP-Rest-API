<?php

namespace ExampleAPI;

/**
* Component responsible for handling output
*/
class Output implements IOutput
{
	/**
	* @var Error|Error[] Error object or list that gets returned if specified
	*/
	protected $error;
	/**
	* @var stdclass Data object that gets returned if specified
	*/
	protected $data;
	
	/**
	* Ingests a status code and message and either creates a new error object or adds to a list of errors
	* @param int $tCode Status code
	* @param string $tMessage Error message
	*/
	public function error( $tCode, $tMessage )
	{
		if ( $this->error == null )
		{
			$this->error = new Error( $tCode, $tMessage );
		}
		else
		{
			$tempOld = $this->error;
			$this->error = [];
			$this->error[] = $tempOld;
			$this->error[] = new Error( $tCode, $tMessage );
		}
	}
	
	/**
	* Ingests a data object
	* @param stdclass $tData Data object
	*/
	public function data( $tData )
	{
		$this->data = $tData;
	}
	
	/**
	* Writes the HTML document output if error or data objects are specified
	*/
	public function write()
	{
		$tempIsData = $this->data != null;
		$tempIsError = $this->error != null;
		if ( $tempIsData || $tempIsError )
		{
			$tempOut = [];
			
			if ( $tempIsData )
			{
				$tempOut[ "data" ] = $this->data;
			}
			
			if ( $tempIsError )
			{
				$tempOut[ "error" ] = $this->error;
			}
			
			echo $this->encode( $tempOut );
		}
	}
	
	/**
	* Converts raw PHP objects into JSON
	* @param mixed $tRaw Raw PHP object
	*/
	public function encode( $tRaw )
	{
		return json_encode( $tRaw );
	}
}

?>