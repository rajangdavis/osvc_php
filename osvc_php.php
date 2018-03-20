<?php

namespace OSvCPHP{

	use OSvCPHP;

	$GLOBALS["OSvCPHP_DEBUG"] = false;

	class Client
	{
		protected $config;

		public function __construct($config_hash = null)
		{
			if(isset($config_hash))
				$this->config = new Config($config_hash);
		}

	}

	class Config
	{
		public $no_ssl_verify,$suppress_rules,$login,$base_url;

		private function hidden_credentials($credential_string)
		{
			return base64_encode($credential_string);
		}

		private function client_url($config_hash)
		{
			$base_url = "https://" . $config_hash['interface'] . ".";

			if(isset($config_hash['demo_site']) && $config_hash['demo_site'] === true){
				$base_url .= "rightnowdemo.com/services/rest/connect/";
			}else{
				$base_url .= "custhelp.com/services/rest/connect/";
			}

			if(isset($config_hash['version'])){
				$base_url .= $config_hash['version'];
			}else{
				$base_url .= "v1.3";
			}

			return $base_url . "/";
		}

		public function __construct($config_hash)
		{
			$this->login = self::hidden_credentials($config_hash['username'] .":". $config_hash['password'] );
			$this->no_ssl_verify = isset($config_hash['no_ssl_verify']) ? $config_hash['no_ssl_verify'] : false;
			$this->suppress_rules = isset($config_hash['suppress_rules']) ? $config_hash['suppress_rules'] : false;
			$this->base_url = self::client_url($config_hash);
		}
	}

	class Connect extends Client
	{

		static function get($client_hash,$url)
		{
			return self::curlGeneric($client_hash,$url);
		}	

		static function post($client_hash,$url,$data)
		{
			return self::curlGeneric($client_hash,$url,"POST",$data);
		}	

		static function patch($client_hash,$url,$data)
		{
			return self::curlGeneric($client_hash,$url,"PATCH",$data);	
		}	

		static function delete($client_hash,$url)
		{
			return self::curlGeneric($client_hash,$url,"DELETE");	
		}

		private static function curlGeneric($client_hash,$resource_url, $method = "GET",$data = null)
		{
			$resource_url_final = isset($resource_url) ? rawurlencode($resource_url) : "";
			$url = $client_hash->config->base_url . $resource_url;
			$curl = curl_init();
			$headers = array(
				"Content-Type: application/json",
				"Authorization: Basic " . $client_hash->config->login,
				"Connection: Keep-Alive",
				"Keep-Alive: timeout=1, max=1000"
			);
			if($client_hash->config->suppress_rules) array_push($headers,"OSvC-CREST-Suppress-All : true");
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !$client_hash->config->no_ssl_verify);
			curl_setopt($curl, CURLOPT_POST, ($method == "POST")); 
			if (($method == "POST" || "PATCH") && !is_null($data)) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
			if ($method == "PATCH"){
				array_push($headers,"X-HTTP-Method-Override: PATCH");
			}else if($method == "DELETE"){
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
			}
			curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
			$body = json_decode(curl_exec($curl));
			$info = curl_getinfo($curl);
			curl_close($curl);
			$final_results =  array(
				'body' => $body,
				'info'=> $info
			);
			if($GLOBALS["OSvCPHP_DEBUG"] === true){
				echo json_encode($final_results, JSON_PRETTY_PRINT);
			} 
			return $final_results;
		}
	}

	class Normalize
	{
		static function results_to_array($response_object)
		{

			if(!in_array($response_object['info']['http_code'], array(200,201))){
				return $response_object;
			}else{
				if(isset($response_object['body']->items)){
					$results_array = $response_object['body']->items;
				}else if(isset($response_object['body']->columnNames)){
					$results_array = $response_object['body'];
				}
			}

			return self::check_for_items_and_rows($results_array);
		}

		private static function iterate_through_rows($item)
		{
			$results_array = array();
			foreach ($item->rows as $rowIndex => $row) {
				$result_hash = array();
				foreach ($item->columnNames as $columnIndex => $column) {
					$result_hash[$column] = $row[$columnIndex];
				};
				array_push($results_array, $result_hash);
			}
			return $results_array;
		}

		private static function results_adjustment($final_arr)
		{
			if(sizeof($final_arr) == 1 && gettype($final_arr) == "array" ){
				return $final_arr[0];
			}else{
				return $final_arr;
			}
		}

		private static function check_for_items_and_rows($results_array)
		{
			if(isset($results_array->rows)){
				return self::iterate_through_rows($results_array);
			}else if(isset($results_array) && gettype($results_array) == "array" && sizeof($results_array) > 1){
				$results = array();
				foreach ($results_array as $item) {
					array_push($results, self::iterate_through_rows($item));
				}
				return self::results_adjustment($results);
			}
		}
	}


	class QueryResults extends Client
	{
		public function query($client,$query)
		{
			$get_response = OSvCPHP\Connect::get($client,'queryResults?query=' . rawurlencode($query));
			return Normalize::results_to_array($get_response);
		}
	}

	class QueryResultsSet extends Client
	{

		public function query_set($client,$query_arr)
		{
			$key_and_query_maps = self::parse_queries($query_arr);
			$keys = $key_and_query_maps[0];
			$queries = $key_and_query_maps[1];
			$joined_query = implode(";", $queries);
			$q = new OSvCPHP\QueryResults;
			$results = $q->query($client,$joined_query);
			return self::match_results_to_keys($keys,$results);
		}

		private static function match_results_to_keys($keys,$results)
		{
			$results_object = array();
			foreach ($keys as $index => $key) {
				$results_object[$key] = $results[$index];
			}
			return (object)$results_object;
		}

		private static function parse_queries($query_arr)
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
			$post_response = OSvCPHP\Connect::post($client,'analyticsReportResults',$json_data);
			return Normalize::results_to_array($post_response);		
		}
	}	
}

// Convenience Methods

namespace{
	// Create convenience functions
	function dti($date)
	{
		return $date;
	}

	function arrf($filter_array)
	{
		return $filter_array;
	}
}