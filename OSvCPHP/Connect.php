<?php

namespace OSvCPHP;
use OSvCPHP;

require_once("Client.php");

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