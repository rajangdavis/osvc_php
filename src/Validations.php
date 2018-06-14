<?php

namespace OSvCPHP;
use OSvCPHP;

class Validations
{
	static function customError($err, $example){
		echo "\n\033[31mError: " . $err . "\033[0m\n\n\033[33mExample:\033[0m" . $example ."\n\n\n\n";
		throw new \Exception($err);
	}
}
