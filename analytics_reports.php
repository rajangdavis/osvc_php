<?php

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$last_updated = new OSvCPHP\AnalyticsReportResults(
	array("lookupName" => "Last Updated By Status")
);

$results = $last_updated->run($rn_client);
echo json_encode($results,JSON_PRETTY_PRINT);
