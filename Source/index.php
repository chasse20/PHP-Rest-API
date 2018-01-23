<?php

namespace ExampleAPI;

require_once( "includes/interfaces/IAction.php" );
require_once( "includes/interfaces/IAPI.php" );
require_once( "includes/interfaces/IAuthorization.php" );
require_once( "includes/interfaces/IConnection.php" );
require_once( "includes/interfaces/IInput.php" );
require_once( "includes/interfaces/IOutput.php" );

require_once( "includes/ActionDelete.php" );
require_once( "includes/ActionGet.php" );
require_once( "includes/ActionPatch.php" );
require_once( "includes/ActionPost.php" );
require_once( "includes/API.php" );
require_once( "includes/Authorization.php" );
require_once( "includes/Connection.php" );
require_once( "includes/Input.php" );
require_once( "includes/InputJSON.php" );
require_once( "includes/ExampleAPI.php" );
require_once( "includes/Output.php" );
require_once( "includes/OutputJSON.php" );
require_once( "includes/Utility.php" );

// Run API
$tempAPI = new ExampleAPI( "http://www.chassebrook.com/ExampleAPI/help/" );
$tempAPI->execute();

?>