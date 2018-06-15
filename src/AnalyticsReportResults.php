<?php

namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");
require_once("Connect.php");
require_once("Normalize.php");

class AnalyticsReportResults extends Client
{
	public function run(array $options) : array
	{

		if(isset($options['json'])){
			$this->_checkForIdAndLookupName($options['json']);
		}

		$options['url'] = "analyticsReportResults";

		$post_response = Connect::post($options);

		if(isset($options['debug']) && $options['debug'] === true){
			return $post_response;
		}else{
			return Normalize::results_to_array($post_response);
		}
	}

	private function _checkForIdAndLookupName($json)
	{
		if(!isset($json['id']) && !isset($json['lookupName'])){
			$err = "AnalyticsReportResults must have an 'id' or 'lookupName' set within the json data object";
			$example = ANALYTICS_REPORT_RESULTS_NO_ID_OR_LOOKUPNAME_EXAMPLE;

			return Validations::customError($err,$example);
		}
	}
}

