<?php

namespace OSvCPHP;
use OSvCPHP;

class Client
{
	public $config;

	public function __construct($config_hash)
	{
		$this->config = new Config($config_hash);
	}

}


class Config
{
	public $no_ssl_verify,$suppress_rules,$login;

	private function clientUrl($config_hash){
		$base_url = "https://" . $config_hash['interface'] . ".";

		if($config_hash['demo_site'] === true){
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

	public function __construct($config_hash){
		$this->login = base64_encode($config_hash['username'] .":". $config_hash['password'] );
		$this->no_ssl_verify = isset($config_hash['no_ssl_verify']) ? $config_hash['no_ssl_verify'] : false;
		$this->suppress_rules = isset($config_hash['suppress_rules']) ? $config_hash['suppress_rules'] : false;
		$this->base_url = self::clientUrl($config_hash);
	}
}

class Connect
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

	private static function curlGeneric($client_hash,$resource_url, $method = "GET",$data = null){
		$url = $client_hash->config->base_url . $resource_url;
		$curl = curl_init();
		$headers = array(
			"Content-Type: application\/json",
			"Authorization: Basic " . $client_hash->config->login
		);
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, !$client_hash->config->no_ssl_verify); // Must ignore SSL issues
		curl_setopt($curl, CURLOPT_POST, ($method == "POST")); 
		if (($method == "POST" || "PATCH") && !is_null($data)) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		if ($method == "PATCH"){
			array_push($headers,"X-HTTP-Method-Override: PATCH");
		}else if($method == "DELETE"){
			curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
		}
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		return array(
			'body' => json_decode(curl_exec($curl)),
			'info'=> curl_getinfo($curl)
		);
	}
}


class QueryResults
{
	public function query($client,$query)
	{


	}
}