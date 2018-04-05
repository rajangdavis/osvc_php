<?php

namespace OSvCPHP{

	use OSvCPHP;

	require_once("Config.php");

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

}

// Convenience Methods
namespace{

	// Create convenience functions
	if(!function_exists("dti")){
		function dti($date)
		{
			return $date;
		}
	}
	if(!function_exists('arrf')){	
		function arrf($filter_array)
		{
			return $filter_array;
		}
	}
}