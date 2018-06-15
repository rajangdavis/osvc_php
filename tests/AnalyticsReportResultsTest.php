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
            new OSvCPHP\AnalyticsReportResults
        );
    }

    public function testShouldTakeAnOptionsObjectWithFiltersAndLimitAndMakeAPostRequest(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $options = array(
        	"client" => $rn_client,
        	"json" => array(
        		"filters" => array(
        			array(
        				"name" => "search_ex",
        				"values" => array("returns")
        			)
	        	),
	        	"limit" => 2,
	        	"id" => 176
        	)
        );

        $arr = new OSvCPHP\AnalyticsReportResults;

        $arrResults = $arr->run($options);

        $this->assertEquals(sizeof($arrResults),2);
    }

    public function testShouldTakeAnOptionsObjectWithALookupNameAndMakeAPostRequest(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $options = array(
        	"client" => $rn_client,
        	"json" => array(
	        	"limit" => 2,
	        	"lookupName" => "Incident Activity"
        	)
        );

        $arr = new OSvCPHP\AnalyticsReportResults;

        $arrResults = $arr->run($options);

        $this->assertArrayNotHasKey("status",$arrResults);
        $this->assertEquals(sizeof($arrResults),2);
    }

    public function testShouldReturnAnErrorObjectWith400Error(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $options = array(
        	"client" => $rn_client,
        	"json" => array(
	        	"id" => 0
        	)
        );

        $arr = new OSvCPHP\AnalyticsReportResults;

        $arrResults = $arr->run($options);

        $this->assertEquals($arrResults['status'], 400);
    }

    /**
     * @expectedException Exception
     */
    public function testShouldReturnAnErrorIfNoIDorLookupNameIsSet(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $options = array(
        	"client" => $rn_client,
        	"json" => array()
        );

        $arr = new OSvCPHP\AnalyticsReportResults;

        $arrResults = $arr->run($options);
    }

    public function testShouldReturnARawResponseObjectIfDebugOptionIsSetToTrue(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $options = array(
        	"client" => $rn_client,
        	"json" => array(
	        	"limit" => 2,
	        	"lookupName" => "Incident Activity"
        	),
        	"debug" => true
        );

        $arr = new OSvCPHP\AnalyticsReportResults;

        $arrResults = $arr->run($options);

        $this->assertArrayHasKey("info", $arrResults);
		$this->assertArrayHasKey("body", $arrResults);
    }

    public function testShouldReturnARawResponseObjectIfDebugOptionIsSetToTrueAndABadRequestIsMade(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $options = array(
        	"client" => $rn_client,
        	"json" => array(
	        	"limit" => 2,
	        	"id" => 0
        	),
        	"debug" => true
        );

        $arr = new OSvCPHP\AnalyticsReportResults;

        $arrResults = $arr->run($options);

        $this->assertArrayHasKey("info", $arrResults);
		$this->assertArrayHasKey("body", $arrResults);
    }
}
