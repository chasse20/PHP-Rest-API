<?php

namespace ExampleAPI;

require_once( "includes/interfaces/IAction.php" );
require_once( "includes/interfaces/IAPI.php" );
require_once( "includes/interfaces/IConnection.php" );
require_once( "includes/interfaces/IData.php" );
require_once( "includes/interfaces/IOutput.php" );
require_once( "includes/interfaces/IRoute.php" );

require_once( "includes/ActionDelete.php" );
require_once( "includes/ActionGet.php" );
require_once( "includes/ActionGetRange.php" );
require_once( "includes/ActionPatch.php" );
require_once( "includes/ActionPost.php" );
require_once( "includes/API.php" );
require_once( "includes/Connection.php" );
require_once( "includes/DataJSON.php" );
require_once( "includes/Error.php" );
require_once( "includes/Output.php" );
require_once( "includes/Route.php" );
require_once( "includes/RouteID.php" );
require_once( "includes/Utility.php" );

// Run API
$tempAPI = new API( new Connection( "URL", "username", "password", "example_api" ), new Route(), new DataJSON(), new Output() );
$tempAPI->execute();

?>