<?php

namespace ExampleAPI;

require_once( "includes/interfaces/IAction.php" );
require_once( "includes/interfaces/IAPI.php" );
require_once( "includes/interfaces/IAuthorization.php" );
require_once( "includes/interfaces/IConnection.php" );
require_once( "includes/interfaces/IInput.php" );
require_once( "includes/interfaces/IOutput.php" );

require_once( "includes/Action.php" );
require_once( "includes/ActionDelete.php" );
require_once( "includes/ActionInsert.php" );
require_once( "includes/ActionSelect.php" );
require_once( "includes/ActionUpdate.php" );
require_once( "includes/API.php" );
require_once( "includes/Authorization.php" );
require_once( "includes/Connection.php" );
require_once( "includes/ConnectionMySQL.php" );
require_once( "includes/ExampleAPI.php" );
require_once( "includes/Input.php" );
require_once( "includes/InputJSON.php" );
require_once( "includes/Output.php" );
require_once( "includes/OutputJSON.php" );
require_once( "includes/Utility.php" );

// Run API
header( "Access-Control-Allow-Origin: *" ); // fix for specific server
header( "Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept" );
header( "Access-Control-Allow-Methods: POST, GET, PATCH, DELETE, OPTIONS" );

$tempAPI = new ExampleAPI();
$tempAPI->execute();

?>