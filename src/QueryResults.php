<?php

namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");
require_once("Connect.php");
require_once("Normalize.php");
include "Validations.php";
include "Examples.php";

class QueryResults extends Client
{
	public function query($options = array())
	{
		if(gettype($options) != "array"){
			$err = "Options must be an associative array";
			$example = QUERY_RESULTS_BAD_OPTIONS_EXAMPLE;

			return Validations::custom_error($err,$example);
		
		}else if(!isset($options['query']) || $options['query'] === ""){
			$err = "QueryResults must have a query set within the options.";
			$example = QUERY_RESULTS_NO_QUERY_EXAMPLE;

			return Validations::custom_error($err,$example);
		}
		else{
			$query = $options['query'];
		}

		$options['url'] = 'queryResults?query=' . rawurlencode($query);

		$get_response = Connect::get($options);

		if(isset($options['debug']) && $options['debug'] === true){
			return $get_response;
		}else{
			return Normalize::results_to_array($get_response);
		}
	}
}

