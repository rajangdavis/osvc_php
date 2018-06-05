<?php

require_once(dirname(__FILE__) . '/../src/Client.php');
require_once(dirname(__FILE__) . '/../src/AnalyticsReportResults.php');


use PHPUnit\Framework\TestCase;

final class AnalyticsReportResultsTest extends TestCase
{

    public function testIsAnInstanceOfAnalyticsReportResults(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $this->assertInstanceOf(
            OSvCPHP\AnalyticsReportResults::class,
            new OSvCPHP\AnalyticsReportResults(array("id" => 176))
        );
    }

}


// $ar = new OSvCPHP\AnalyticsReportResults(
// 	array("id" => 176)
// );

// $filters = array(
// 	"name" => "search_ex",
// 	"values" => array( "Maestro" )
// );

// $arr = $ar->run($rn_client, $filters);

// echo json_encode($arr);