<?php

namespace ExampleAPI;

/**
* Wrapper for the componentized API
*/
class API implements IAPI
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
	* @var IRoute Component for handling the root URI route
	*/
	protected $route;
	
	/**
	* @var IData Component for handling any input data via POST
	*/
	protected $data;
	
	/**
	* @var IOutput Component that is responsible for handling response output
	*/
	protected $output;
	
	/**
	* Constructor
	* @param IConnection $tConnection Component used for connecting to the database
	* @param IAuthorizaton $tAuthorization Authorization used for connecting to the database
	* @param IRoute $tRoute Component for handling the root URI route
	* @param IData $tData Component for handling any input data via POST
	* @param IOutput $tOutput Component that is responsible for handling response output
	*/
	public function __construct( IConnection $tConnection, IAuthorization $tAuthorization, IRoute $tRoute, IData $tData, IOutput $tOutput )
	{
		$this->connection = $tConnection;
		$this->authorization = $tAuthorization;
		$this->route = $tRoute;
		$this->data = $tData;
		$this->output = $tOutput;
	}
	
	/**
	* Accessor for getting the encapsulated Connection component
	* @return IConnection Connection component
	*/
	public function getConnection()
	{
		return $this->connection;
	}
	
	/**
	* Accessor for getting the encapsulated Authorization component
	* @return IConnection Authorization component
	*/
	public function getAuthorization()
	{
		return $this->authorization;
	}
	
	/**
	* Accessor for getting the encapsulated Route component
	* @return IRoute Route component
	*/
	public function getRoute()
	{
		return $this->route;
	}
	
	/**
	* Accessor for getting the encapsulated Data component
	* @return IData Data component
	*/
	public function getData()
	{
		return $this->data;
	}
	
	/**
	* Accessor for getting the encapsulated Output component
	* @return IRoute Route component
	*/
	public function getOutput()
	{
		return $this->output;
	}
	
	/**
	* Runs the API: generates the URI, executes the route, and writes the output
	*/
	public function execute()
	{
		// Generate URI
		$tempURI = null;
		if ( isset( $_SERVER[ "PATH_INFO" ] ) )
		{
			$tempURIString = htmlspecialchars( $_SERVER[ "PATH_INFO" ] );
			if ( $tempURIString != "" )
			{
				$tempURI = explode( "/", $tempURIString );
				array_shift( $tempURI ); 
			}
		}
		
		// Execute Route
		$this->route->execute( $this, $tempURI );
		
		// Output
		$this->output->write();
	}
}

?>