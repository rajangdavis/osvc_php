<?php

namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");

class Connect extends Client
{

	static function get($options)
	{
		return self::_curl_generic($options,"GET");
	}	

	static function post($options)
	{
		return self::_curl_generic($options,"POST");
	}	

	static function patch($options)
	{
		return self::_curl_generic($options,"PATCH");	
	}	

	static function delete($options)
	{
		return self::_curl_generic($options,"DELETE");	
	}

	

	// In order to make a generic curl function
		// You need to initialize curl by
			// setting a URL
			// setting curl configurations
				// specifying which HTTP method to use
					// which requires a generic set of headers to modify
		// Then you run curl			


	private static function _curl_generic($options, $method = "GET")
	{
		$curl = self::_init_curl($options, $method); 
		return self::_run_curl($options, $curl);
	}

		private static function _init_curl($options, $method)
		{
			$curl = curl_init();
			$urlSetForCurl = self::_set_url($options,$curl);
			return self::_config_curl($options,$urlSetForCurl,$method);
					
		}

			private static function _set_url($options,$curl)
			{
				$resource_url = $options['url'];
				$resource_url_final = isset($resource_url) ? rawurlencode($resource_url) : "";
				$url =  $options['client']->config->base_url . $resource_url;
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				return $curl;
			}

			private static function _config_curl($options,$curl,$method)
			{
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !$options['client']->config->no_ssl_verify);
				return self::_set_method($options, $curl, $method);
			}

				private static function _set_method($options, $curl, $method)
				{
					$headers = self::_init_headers($options);
					curl_setopt($curl, CURLOPT_POST, ($method == "POST")); 
					if (($method == "POST" || "PATCH") && isset($options['json'])) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options['json']));
					if ($method == "PATCH"){
						array_push($headers,"X-HTTP-Method-Override: PATCH");
					}else if($method == "DELETE"){
						curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
					}
					curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
					return $curl;
				}

				private static function _init_headers($options)
				{
					$headers = array(
						"Content-Type: application/json",
						"Authorization: Basic " . $options['client']->config->login,
						"Connection: Keep-Alive",
						"Keep-Alive: timeout=1, max=1000"
					);

					if($options['client']->config->suppress_rules) array_push($headers,"OSvC-CREST-Suppress-All : true");
					return $headers;
				}

		private static function _run_curl($options,$curl)
		{
			
			$body = json_decode(curl_exec($curl));
			$info = curl_getinfo($curl);
			curl_close($curl);

			if(isset($options['debug']) && $options['debug'] === true){
				$final_results =  array(
					'body' => $body,
					'info'=> $info
				);
				return $final_results;
			}else{
				return $body;
			}
		}
}

