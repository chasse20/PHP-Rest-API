<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IAPI
{
	public function getConnection();
	public function getAuthorization();
	public function getInput();
	public function getOutput();
	public function execute();
}

?>