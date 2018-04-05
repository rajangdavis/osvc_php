<?php

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$ar = new OSvCPHP\AnalyticsReportResults(
	array("id" => 176)
);

$filters = array(
	"name" => "search_ex",
	"values" => array( "Maestro" )
);

$arr = $ar->run($rn_client, $filters);