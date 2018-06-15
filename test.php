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
		// "filters" => array(
		// 	array(
		// 		"name" => "search_ex",
		// 		"values" => array("returns")
		// 	)
  //   	),
    	"limit" => 2,
  //   	"id" => 176
		"lookupName" => "Incident Activity"
	)
);

$arr = new OSvCPHP\AnalyticsReportResults;

$arrResults = $arr->run($options);

echo json_encode($arrResults, JSON_PRETTY_PRINT);