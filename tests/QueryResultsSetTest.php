<?php

require_once(dirname(__FILE__) . '/../src/Client.php');
require_once(dirname(__FILE__) . '/../src/QueryResultsSet.php');

use PHPUnit\Framework\TestCase;

final class QueryResultsSetTest extends TestCase
{
    public function testIsAnInstanceOfQueryResultsSet()
    {

        $this->assertInstanceOf(
            OSvCPHP\QueryResultsSet::class,
            new OSvCPHP\QueryResultsSet
        );
    }

	/* should take an options object with multiple queries and make a HTTP GET Request */ 

	public function testShouldTakeAnOptionsObjectWithMultipleQueriesAndMakeAHTTPGETRequest()
	{

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


		$this->assertInternalType('object',$results);
		$this->assertInternalType('array',$results->incidents);
		$this->assertInternalType('array',$results->serviceProducts);


	}


	/* should catch an error if there is an error */ 
	public function testShouldCatchAnErrorIfThereIsAnError()
	{

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
		        "query" => "DESCRIBE SERVICEPRODUCTSS",
		        "key" => "serviceProducts"
		    ),
		);


		$options = array(
		    "client" => $rn_client,
		    "queries" => $queries
		);

		$mq = new OSvCPHP\QueryResultsSet;

		$results = $mq->query_set($options);

		$this->assertEquals($results['status'],400);

	}

	/**
     * @expectedException Exception
     */

	/* should return an error if the queries are not defined */ 
	public function testShouldReturnAnErrorIfTheQueriesAreNotDefined()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));


		$options = array(
		    "client" => $rn_client,
		);

		$mq = new OSvCPHP\QueryResultsSet;

		$results = $mq->query_set($options);

	}


	/**
     * @expectedException Exception
     */

	/* should return an error if there is only one query */ 
	public function testShouldReturnAnErrorIfThereIsOnlyOneQuery()
	{


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
		);

		$options = array(
		    "client" => $rn_client,
		    "queries" => $queries
		);

		$mq = new OSvCPHP\QueryResultsSet;

		$results = $mq->query_set($options);

	}




	/* should return a raw response object if the debug option is set to true */ 

	public function testShouldReturnARawResponseObjectIfTheDebugOptionIsSetToTrue()
	{

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
		    "queries" => $queries, 
		    "debug" => true
		);

		$mq = new OSvCPHP\QueryResultsSet;

		$results = $mq->query_set($options);

		$this->assertEquals($results['info']['http_code'],200);

	}




	/* should return a raw error object if the debug option is set to true and a bad request is made */ 

	public function testShouldReturnARawErrorObjectIfTheDebugOptionIsSetToTrueAndABadRequestIsMade()
	{


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
		        "query" => "DESCRIBE SERVICEPRODUCTSSSSS",
		        "key" => "serviceProducts"
		    ),
		);

		$options = array(
		    "client" => $rn_client,
		    "queries" => $queries, 
		    "debug" => true
		);

		$mq = new OSvCPHP\QueryResultsSet;

		$results = $mq->query_set($options);

		$this->assertEquals($results['info']['http_code'],400);

	}

	public function testShouldTakeAnOptionsObjectWithMultipleQueriesAndAParallelOptionSetToTrueAndMakeAHTTPGETRequest()
	{

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
		    "queries" => $queries,
		    "concurrent" => true
		);

		$mq = new OSvCPHP\QueryResultsSet;

		$results = $mq->query_set($options);


		$this->assertInternalType('object',$results);
		$this->assertInternalType('array',$results->incidents);
		$this->assertInternalType('array',$results->serviceProducts);


	}

}