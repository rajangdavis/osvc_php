<?php

require_once("./src/QueryResultsSet.php");

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$options = array(
    "client" => $rn_client,
    "queries" => array(
        array(
            "key" => "incidents_1_10",
            "query" => "select * from incidents LIMIT 10"
        ),
        array(
            "key" => "incidents_11_20",
            "query" => "select * from incidents LIMIT 10 offset 10"
        ),
        array(
            "key" => "incidents_21_30",
            "query" => "select * from incidentsss LIMIT 10 offset 20"
        )
    ),
    "parallel" => true
);

$mq = new OSvCPHP\QueryResultsSet();

$results = $mq->query_set($options);

echo json_encode($results->incidents_1_10, JSON_PRETTY_PRINT);
echo json_encode($results->incidents_11_20, JSON_PRETTY_PRINT);
echo json_encode($results->incidents_21_30, JSON_PRETTY_PRINT);