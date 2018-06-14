<?php

require_once("./src/QueryResults.php");

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$options = array(
	"query" => "DESCRIBE CONTACTSSS",
	// "debug" => true
);

$q = new OSvCPHP\QueryResults;

$results = $q->query($rn_client, $options);

echo json_encode($results, JSON_PRETTY_PRINT);