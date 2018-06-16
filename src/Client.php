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
			if(isset($config_hash)){
				$validated_config = $this->_validate_config_hash($config_hash);
				$this->config = new Config($validated_config);
			}
		}

		private function _validate_config_hash($config_hash)
		{
			return $config_hash;
		}
	}

}

// // Convenience Methods
// namespace{

// 	// Create convenience functions
// 	if(!function_exists("dti")){
// 		function dti($date)
// 		{
// 			return $date;
// 		}
// 	}
// 	if(!function_exists('arrf')){	
// 		function arrf($filter_array)
// 		{
// 			return $filter_array;
// 		}
// 	}
// }

