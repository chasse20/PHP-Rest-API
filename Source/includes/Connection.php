<?php

namespace ExampleAPI;

/**
* Component responsible for connecting to a database
*/
class Connection implements IConnection
{
	/**
	* @var string Database Source Name info
	*/
	protected $DSN;
	
	/**
	* @var string Database user name
	*/
	protected $user;
	
	/**
	* @var string Database user password
	*/
	protected $password;
	
	/**
	* Constructor
	* @param string $tDSN Database Source Name info
	* @param string $tUser Database user name
	* @param string $tPassword Database user password
	*/
	public function __construct( $tDSN, $tUser, $tPassword )
	{
		$this->DSN = $tDSN;
		$this->user = $tUser;
		$this->password = $tPassword;
	}
	
	/**
	* Attempts to establish a connection to the database
	* @param IAPI $tAPI API that called this function
	* @param PDO $tConnection Reference to Connection instance that will be created
	* @return bool True if successful
	*/
	public function tryConnect( IAPI $tAPI, &$tConnection )
	{
		try
		{
			$tConnection = new \PDO( $this->DSN, $this->user, $this->password );
		}
		catch ( PDOException $tException )
		{
			$tAPI->getOutput()->addError( $tException->getMessage() );
			http_response_code( 503 );
			
			return false;
		}
		
		return true;
	}
}

?>