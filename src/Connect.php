<?php

namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");

class Connect extends Client
{

	static function get($options)
	{
		$check_for_download = self::_download_check($options);
		return self::_curl_generic($check_for_download,"GET");
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

	static function options($options)
	{
		return self::_curl_generic($options,"OPTIONS");	
	}

	// In order to make a generic curl function
		// You need to initialize curl by
			// setting a URL
			// setting curl configurations
				// specifying which HTTP method to use
					// which requires a generic set of headers to modify
				// and setting any json if there is any
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

		if ($method == "OPTIONS"){
			curl_setopt($curl, CURLOPT_HEADER,true);
			curl_setopt($curl, CURLOPT_NOBODY,true);
    		curl_setopt($curl, CURLOPT_VERBOSE,true);
		}

		if ($method == "PATCH"){
			array_push($headers,"X-HTTP-Method-Override: PATCH");
		}
		else{
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		}
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		return self::_set_json_to_fields($options,$curl, $method);
	}

	private static function _set_json_to_fields($options,$curl, $method)
	{
		if (($method == "POST" || "PATCH") && isset($options['json'])) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($options['json']));
		if(isset($options['file_name']) && $options['file_reference']) curl_setopt($curl, CURLOPT_FILE, $options['file_reference']);
		return $curl;
	}

	private static function _init_headers($options)
	{
		$headers = array(
			"Content-Type: application/json",
			"Authorization: " . $options['client']->config->auth_header,
			"Connection: Keep-Alive",
			"Keep-Alive: timeout=1, max=1000"
		);

		if(isset($options['client']->config->access_token)){
			array_push($headers,"osvc-crest-api-access-token: " . $options['client']->config->access_token);
		} 

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


	private static function	_download_check($options)
	{
		if(strrpos($options["url"],"?download") == true){
			$original_url = $options["url"];
			$fetch_name_url = str_replace("?download", "", $options["url"]);
			// $revised_options = new \ArrayObject($options);
			// $copied_options = $revised_options->getArrayCopy();
			$options['url'] = $fetch_name_url;

			// this is so we can get the fileName
			$file_info = self::get($options);
			
			// revert back
			$options['url'] = $original_url;

			if(isset($file_info->fileName)){
				$file_name = $file_info->fileName;
			}else if(!isset($file_info->status)){
				$file_name = "downloadedAttachment.tgz";
			}else{
				return $options;
			}
			
			// modified from http://thisinterestsme.com/downloading-files-curl-php/
			//Open file handler.
			$fp = fopen($file_name, 'w+');

			//If $fp is FALSE, something went wrong.
			if($fp === false){
			    throw new Exception('Could not open: ' . $file_name);
			}

			$options['file_name'] = $file_name;
			$options['file_reference'] = $fp;

			return $options;

		}else{
			return $options;
		}
	}
}

