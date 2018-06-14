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

    public function shouldTakeFiltersAndLimits(): void
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

// analyticsReportResults.run
//     √ should take an options object with filters and a limit and  make a HTTP POST Request
//     √ should catch an error if there is an error
//     √ should display an error if the ID or lookupName is not set
//     √ should return a raw response object if the debug option is set to true
//     √ should return a raw error object if the debug option is set to true and a bad request is made; the promise is rejected