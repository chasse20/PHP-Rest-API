<?php

namespace ExampleAPI;

//##########################
// Interface Declaration
//##########################
interface IAPI
{
	public function getConnection();
	public function getRoute();
	public function getOutput();
	public function getData();
	public function execute();
}

?>