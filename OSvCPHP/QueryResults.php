<?php

namespace OSvCPHP;
use OSvCPHP;

require_once("./Client.php");
require_once("./Connect.php");
require_once("./Normalize.php");

class QueryResults extends Client
{
	public function query($client,$query)
	{
		$get_response = Connect::get($client,'queryResults?query=' . rawurlencode($query));
		return Normalize::results_to_array($get_response);
	}
}