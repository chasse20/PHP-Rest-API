<?php

namespace ExampleAPI;

/**
* Wrapper for the componentized API
*/
abstract class API implements IAPI
{
	/**
	* @var IConnection Component used for connecting to the database
	*/
	protected $connection;
	
	/**
	* @var IConnection Authorization used for connecting to the database
	*/
	protected $authorization;
	
	/**
	* @var IInput Component for handling any input data via POST
	*/
	protected $input;
	
	/**
	* @var IOutput Component that is responsible for handling response output
	*/
	protected $output;
	
	/**
	* Accessor for getting the encapsulated Connection component, will lazy instantiate if null
	* @return IConnection Connection component
	*/
	public function getConnection()
	{
		if ( $this->connection == null )
		{
			$this->connection = $this->createConnection();
		}
		
		return $this->connection;
	}
	
	/**
	* Factory method for creating instance of a Connection component
	* @return IConnection Connection component
	*/
	abstract protected function createConnection();
	
	/**
	* Accessor for getting the encapsulated Authorization component, will lazy instantiate if null
	* @return IAuthorization Authorization component
	*/
	public function getAuthorization()
	{
		if ( $this->authorization == null )
		{
			$this->authorization = $this->createAuthorization();
		}
		
		return $this->authorization;
	}
	
	/**
	* Factory method for creating instance of an Authorization component
	* @return IAuthorization Authorization component
	*/
	abstract protected function createAuthorization();

	/**
	* Accessor for getting the encapsulated Input component, will lazy instantiate if null
	* @return IInput Input component
	*/
	public function getInput()
	{
		if ( $this->input == null )
		{
			$this->input = $this->createInput();
		}
		
		return $this->input;
	}
	
	/**
	* Factory method for creating instance of an Input component
	* @return IInput Input component
	*/
	abstract protected function createInput();
	
	/**
	* Accessor for getting the encapsulated Output component
	* @return IOutput Output component
	*/
	public function getOutput()
	{
		if ( $this->output == null )
		{
			$this->output = $this->createOutput();
		}
		
		return $this->output;
	}
	
	/**
	* Factory method for creating instance of an Output component
	* @return IOutput Output component
	*/
	abstract protected function createOutput();
	
	/**
	* Runs the API: generates the URI, executes the route, and writes the output
	*/
	public function execute()
	{
		// Generate URI
		$tempURI = null;
		$tempURIString = htmlspecialchars( substr( $_SERVER[ "PHP_SELF" ], strlen( $_SERVER[ "SCRIPT_NAME" ] ) + 1, strlen( $_SERVER[ "PHP_SELF" ] ) ) );
		if ( $tempURIString != "" )
		{
			$tempURI = explode( "/", $tempURIString );
		}
		
		// Execute Route
		$this->executeRoute( $tempURI );
		
		// Output
		if ( $this->output != null )
		{
			$this->output->write();
		}
	}
	
	/**
	* Route handler
	* @param string[] $tURI URI array of paths
	*/
	abstract protected function executeRoute( $tURI );
}

?>
