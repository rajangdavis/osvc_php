<?php
namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");
require_once("QueryResults.php");

class QueryResultsSet extends Client
{

	public function query_set($client,$query_arr)
	{
		$key_and_query_maps = self::_parse_queries($query_arr);
		$keys = $key_and_query_maps[0];
		$queries = $key_and_query_maps[1];
		$joined_query = implode(";", $queries);
		$q = new QueryResults;
		$results = $q->query($client,$joined_query);
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
}

