<?php

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$query = "select * from answers WHERE ID > 100 LIMIT 4";

$q = new OSvCPHP\QueryResults;

$q->query($rn_client,$query,true);
