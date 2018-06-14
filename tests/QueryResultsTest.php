<?php

require_once(dirname(__FILE__) . '/../src/Client.php');
require_once(dirname(__FILE__) . '/../src/QueryResults.php');

use PHPUnit\Framework\TestCase;

final class QueryResultsTest extends TestCase
{

    public function testIsAnInstanceOfQueryResults(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));
        
        $this->assertInstanceOf(
            OSvCPHP\QueryResults::class,
            new OSvCPHP\QueryResults
        );
    }

    public function testShouldBeAbleToMakeAGetRequest(): void
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
			"query" => "DESCRIBE CONTACTS"
		);

		$q = new OSvCPHP\QueryResults;

		$results = $q->query($rn_client,$options);
		$this->assertArrayHasKey("Name", $results[0]);
		$this->assertArrayHasKey("Type", $results[0]);
		$this->assertArrayHasKey("Path", $results[0]);
    }

    /**
     * @expectedException Exception
     */
    public function testShouldErrorIfNoQueryIsPresent(): void
    {
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
			"query" => "DESCRIBE CONTACTS"
		);

		$q = new OSvCPHP\QueryResults;

		$results = $q->query($rn_client);
    }

    /**
     * @expectedException Exception
     */
    public function testShouldErrorIfOptionsIsNotAnAssociativeArray(): void
    {
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$query = "DESCRIBE CONTACTS";

		$q = new OSvCPHP\QueryResults;

		$results = $q->query($rn_client,$query);
    }

   
    public function testShouldCatchAnErrorIfThereIsABadError(): void
    {
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
			"query" => "DESCRIBE CONTACTSSS"
		);

		$q = new OSvCPHP\QueryResults;

		$results = $q->query($rn_client, $options);

		$this->assertArrayHasKey("status", $results);
    }

    public function testShouldReturnARawResponseObjectIfTheDebugOptionIsSetToTrue(): void
    {
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
			"query" => "DESCRIBE CONTACTS",
			"debug" => true
		);

		$q = new OSvCPHP\QueryResults;

		$results = $q->query($rn_client, $options);

		$this->assertArrayHasKey("info", $results);
		$this->assertArrayHasKey("body", $results);
    }

    public function testShouldReturnARawResponseObjectIfTheDebugOptionIsSetToTrueAndABadRequestIsMade(): void
    {
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
			"query" => "DESCRIBE CONTACTSSSSSS",
			"debug" => true
		);

		$q = new OSvCPHP\QueryResults;

		$results = $q->query($rn_client, $options);

		$this->assertArrayHasKey("info", $results);
		$this->assertArrayHasKey("body", $results);
    }
}
