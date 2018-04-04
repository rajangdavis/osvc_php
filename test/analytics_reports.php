<?php

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$arr = new OSvCPHP\AnalyticsReportResults(
	array("id" => 176)
);

$results = $arr->run($rn_client);
for ( $ii = sizeof($results); $ii--; ) {
    $row = $results[$ii];
    echo( "Columns: "
    .join( ',', array_keys( $row ) ) . "\n" );
    echo( "Values: "
    .join( ',', $row ) . "\n" );
}