<?php
namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");
require_once("QueryResults.php");

class QueryResultsSet extends Client
{

	public function query_set($options)
	{
		$query_arr = self::_options_and_queries_exist($options);
		$key_and_query_maps = self::_parse_queries($query_arr);
		$keys = $key_and_query_maps[0];
		$queries = $key_and_query_maps[1];
		$joined_query = implode(";", $queries);
		$options['query'] = $joined_query;
		$q = new QueryResults;
		$results = $q->query($options);

		if((isset($results['status']) && $results['status'] == 400) || (isset($options['debug']) && $options['debug'] == true)){
			return $results;
		}

		return self::_match_results_to_keys($keys,$results);
	}

	private static function _match_results_to_keys($keys,$results)
	{
		$results_object = array();
		foreach ($keys as $index => $key) {
			$results_object[$key] = $results[$index];
		}
		return (object)$results_object;
	}

	private static function _parse_queries($query_arr)
	{
		$queries_arr = [];
		$key_map = [];

		foreach ($query_arr as $value) {
			array_push($queries_arr, $value['query']);
			array_push($key_map, $value['key']);
		}
		return array($key_map,$queries_arr);
	}

	private static function _options_and_queries_exist($options)
	{
		if(gettype($options) != "array"){
			$err = "Options must be an associative array";
			$example = QUERY_RESULTS_SET_BAD_OPTIONS_EXAMPLE;

			return Validations::custom_error($err,$example);
		
		}else if(!isset($options['queries']) || sizeof($options['queries']) <= 1){
			$err = "QueryResultsSet must have at least 2 queries in a queries properties set within the options object.";
			$example = QUERY_RESULTS_SET_NO_QUERIES_EXAMPLE;

			return Validations::custom_error($err,$example);
		}

		return $options['queries'];
	}
}

