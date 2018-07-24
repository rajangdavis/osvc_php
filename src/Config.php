<?php

namespace OSvCPHP;

use OSvCPHP;

class Config
{
	public $no_ssl_verify,$suppress_rules,$suppress_events,$suppress_all,$base_url,$auth_header,$access_token;

	private function _hidden_credentials($credential_string)
	{
		return base64_encode($credential_string);
	}

	private function _client_url($config_hash)
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

	private function _set_auth($config_hash)
	{
		if(isset($config_hash['username'])){
			return "Basic " . self::_hidden_credentials($config_hash['username'] .":". $config_hash['password'] );
		}

		if(isset($config_hash['session'])){
			return "Session " . $config_hash['session'];
		}

		if(isset($config_hash['oauth'])){
			return "Bearer " . $config_hash['oauth'];
		}
	}

	public function __construct($config_hash)
	{
		$this->auth_header = $this->_set_auth($config_hash);
		$this->no_ssl_verify = isset($config_hash['no_ssl_verify']) ? $config_hash['no_ssl_verify'] : false;
		$this->suppress_rules = isset($config_hash['suppress_rules']) ? $config_hash['suppress_rules'] : false;
		$this->suppress_events = isset($config_hash['suppress_events']) ? $config_hash['suppress_events'] : false;
		$this->suppress_all = isset($config_hash['suppress_all']) ? $config_hash['suppress_all'] : false;
		$this->base_url = self::_client_url($config_hash);
		
		if(isset($config_hash['access_token'])){
			$this->access_token = $config_hash['access_token'];
		}
	}
}

