<?php

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$query_arr = array(
	array(
		"key" => "incidents",
		"query" => "SELECT * FROM incidents LIMIT 3"
	),
	array(
		"key" => "answers",
		"query" => "SELECT * FROM answers LIMIT 3"
	)
);

$mq = new OSvCPHP\QueryResultsSet;

$results_object = $mq->query_set($rn_client,$query_arr);

echo json_encode($results_object->incidents,JSON_PRETTY_PRINT);
echo json_encode($results_object->answers,JSON_PRETTY_PRINT);
