<?php

namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");
require_once("Connect.php");
require_once("Normalize.php");

class AnalyticsReportResults extends Client
{
	public $id,$lookupName,$filters;

	public function __construct($name_or_id)
	{
		if(array_key_exists("id", $name_or_id)){
			$this->id = $name_or_id["id"];
		}else if(array_key_exists("lookupName", $name_or_id)){
			$this->lookupName = $name_or_id["lookupName"];
		}else{
			// error
		}
	}

	public function run($client,$filters = array())
	{
		$this->filters = $filters;
		$json_data = array();
		foreach ($this as $property => $value) {
			if($value != null){
				$json_data[$property] = $value;
			}
		}
		$post_response = Connect::post($client,'analyticsReportResults',$json_data);
		return (array)Normalize::results_to_array($post_response);		
	}
}

