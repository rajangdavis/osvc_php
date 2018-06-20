<?php

require_once("./src/Connect.php");
require_once("./src/QueryResultsSet.php");

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$queries = array(
    array(
        "query" => "DESCRIBE INCIDENTS",
        "key" => "incidents"
    ),
    array(
        "query" => "DESCRIBE SERVICEPRODUCTS",
        "key" => "serviceProducts"
    ),
);


$options = array(
    "client" => $rn_client,
    "queries" => $queries
);

$mq = new OSvCPHP\QueryResultsSet;

$results = $mq->query_set($options);


echo json_encode($results->incidents, JSON_PRETTY_PRINT);
echo json_encode($results->serviceProducts, JSON_PRETTY_PRINT);