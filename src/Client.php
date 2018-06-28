<?php

namespace OSvCPHP{

	use OSvCPHP;

	require_once("Config.php");
	include "Validations.php";
	include "Examples.php";
	

	class Client
	{
		// public $config;
		protected $config;

		public function __construct($config_hash = null)
		{
			if(isset($config_hash)){
				$validated_config = $this->_validate_config_hash($config_hash);
				$this->config = new Config($validated_config);
			}
		}

		private function _validate_config_hash($config_hash)
		{
			
			if(!isset($config_hash['interface'])){

				$err = "Client interface cannot be undefined.";
				$example = CLIENT_NO_INTERFACE_SET_EXAMPLE;
				return Validations::custom_error($err,$example);
			}

			if(!isset($config_hash['username']) && isset($config_hash['password'])){

				$err = "Password is set but username is not.";
				$example = CLIENT_NO_USERNAME_SET_EXAMPLE;
				return Validations::custom_error($err,$example);
			}

			if(!isset($config_hash['password']) && isset($config_hash['username'])){

				$err = "Username is set but password is not.";
				$example = CLIENT_NO_PASSWORD_SET_EXAMPLE;
				return Validations::custom_error($err,$example);
			}

			return $config_hash;
		}
	}

}
