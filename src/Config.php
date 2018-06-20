<?php

namespace OSvCPHP;

use OSvCPHP;

class Config
{
	public $no_ssl_verify,$suppress_rules,$login,$base_url,$session_id,$oauth,$access_token;

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
		if(isset($config_hash['username']) || isset($config_hash['password'])){
			$this->login = self::_hidden_credentials($config_hash['username'] .":". $config_hash['password'] );
		}else if(isset($config_hash['session_id'])){
			$this->session_id = $config_hash['session_id'];
		}else if(isset($config_hash['oauth'])){
			$this->oauth = $config_hash['oauth'];
		}
	}

	public function __construct($config_hash)
	{
		$this->_set_auth($config_hash);
		$this->no_ssl_verify = isset($config_hash['no_ssl_verify']) ? $config_hash['no_ssl_verify'] : false;
		$this->suppress_rules = isset($config_hash['suppress_rules']) ? $config_hash['suppress_rules'] : false;
		$this->base_url = self::_client_url($config_hash);
		$this->access_token = isset($config_hash['access_token']) ? $config_hash['access_token'] : false;
	}
}

