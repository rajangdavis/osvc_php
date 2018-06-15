<?php

use PHPUnit\Framework\TestCase;
 // queryResultsSet.query_set
 //    √ should take an options object with multiple queries and make a HTTP GET Request (1036ms)
 //    √ should catch an error if there is an error (751ms)
 //    √ should return an error if the queries are not defined or if there is only one
 //    √ should return an error if there is only one query
 //    √ should return a raw response object if the debug option is set to true (1052ms)
 //    √ should return a raw error object if the debug option is set to true and a bad request is made (750ms)

// $rn_client = new OSvCPHP\Client(array(
// 	"username" => getenv("OSC_ADMIN"),
// 	"password" => getenv("OSC_PASSWORD"),
// 	"interface" => getenv("OSC_SITE"),
// 	"demo_site" => true
// ));

// $query_arr = array(
// 	array(
// 		"key" => "incidents",
// 		"query" => "SELECT * FROM incidents LIMIT 3"
// 	),
// 	array(
// 		"key" => "answers",
// 		"query" => "SELECT * FROM answers LIMIT 3"
// 	)
// );

// $mq = new OSvCPHP\QueryResultsSet;

// $results_object = $mq->query_set($rn_client,$query_arr);

// // echo json_encode($results_object->answers,JSON_PRETTY_PRINT);
// // echo json_encode($results_object->incidents,JSON_PRETTY_PRINT);