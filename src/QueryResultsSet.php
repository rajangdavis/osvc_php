<?php
namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");
require_once("QueryResults.php");
require_once("Connect.php");
require_once("Normalize.php");

class QueryResultsSet extends Client
{

	public function query_set($options)
	{
		$query_arr = self::_options_and_queries_exist($options);
		$key_and_query_maps = self::_parse_queries($query_arr);

		$options = self::_concurrent_check($options, $key_and_query_maps);

		if(isset($options["returned"])){
			return $options["returned"];
		}

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

	private static function _concurrent_check($options, $key_and_query_maps){


		if(isset($options['concurrent']) && $options['concurrent'] === true){
			
			$keys = $key_and_query_maps[0];
			
			$queries = $key_and_query_maps[1];
			
			// Borrowed from
			// https://stackoverflow.com/questions/9308779/php-concurrent-curl-requests

			$master = curl_multi_init();
			$curl_arr = array();

			for ($i=0; $i < sizeof($queries); $i++) { 
					
				$new_options = $options;
				unset($new_options["queries"]);
				unset($new_options["concurrent"]);
				$new_options["url"] = "queryResults?query=" . rawurlencode($queries[$i]);
				$curl_arr[$i] = OSvCPHP\Connect::_init_curl($new_options, "GET");

				curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
				curl_multi_add_handle($master, $curl_arr[$i]);
			}

			do {
			    curl_multi_exec($master,$running);
			} while($running > 0);

			$results = array();

			for ($i=0; $i < sizeof($queries); $i++) { 
				
				$get_content = curl_multi_getcontent($curl_arr[$i]);
				$info = curl_getinfo($curl_arr[$i]);
				
				$content_decoded = json_decode($get_content);

				$content_normalized = Normalize::results_to_array($content_decoded);

				array_push($results, $content_normalized);
			}

			$options["returned"] = self::_match_results_to_keys($keys,$results);;
		}


		return $options;
	}

	private static function _match_results_to_keys($keys,$results)
	{
		$results_object = array();
		foreach ($keys as $index => $key) {
			if(array_key_exists($key, $results_object)){
				$results_object[$key] = self::_accumulate($results_object[$key],$results[$index]);
			}else{
				$results_object[$key] = $results[$index];
				
			}
		}
		return (object)$results_object;
	}


	private static function _accumulate($results_object,$objects_to_add)
	{
		foreach ($objects_to_add as $object) {
			array_push($results_object, $object);
		}

		return $results_object;
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

