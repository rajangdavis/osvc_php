<?php

require_once("./src/AnalyticsReportResults.php");

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$options = array(
	"client" => $rn_client,
	"json" => array(
    	"limit" => 2,
    	"lookupName" => "Incident Activity"
	),
	"debug" => true
);

$arr = new OSvCPHP\AnalyticsReportResults;

$arrResults = $arr->run($options);

echo json_encode($arrResults, JSON_PRETTY_PRINT);