<?php

namespace ExampleAPI;

/**
* Component responsible for connecting to an SQL database
*/
class Connection implements IConnection
{
	/**
	* @var string Server address
	*/
	protected $server;
	/**
	* @var string Database user name
	*/
	protected $user;
	/**
	* @var string Database user password
	*/
	protected $password;
	/**
	* @var string Database name
	*/
	protected $database;
	
	/**
	* Constructor
	* @param string $tServer Server address
	* @param string $tUser Database user name
	* @param string $tPassword Database user password
	* @param string $tDatabase Database name
	*/
	public function __construct( $tServer, $tUser, $tPassword, $tDatabase )
	{
		$this->server = $tServer;
		$this->user = $tUser;
		$this->password = $tPassword;
		$this->database = $tDatabase;
	}
	
	/**
	* Attempts to establish a connection to the SQL database
	* @param IAPI $tAPI API that called this function
	* @param IConnection $tConnection Reference to Connection instance that will be created
	* @return bool True if successful
	*/
	public function tryConnect( IAPI $tAPI, &$tConnection )
	{
		$tConnection = new \mysqli( $this->server, $this->user, $this->password, $this->database );
		if ( $tConnection->connect_error )
		{
			$tAPI->getOutput()->error( 500, $tempConnection->connect_error );
			return false;
		}
		
		return true;
	}
}

?>