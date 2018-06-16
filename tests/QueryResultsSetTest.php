<?php

require_once(dirname(__FILE__) . '/../src/Client.php');
require_once(dirname(__FILE__) . '/../src/QueryResultsSet.php');

use PHPUnit\Framework\TestCase;

final class QueryResultsSetTest extends TestCase
{
    public function testIsAnInstanceOfQueryResultsSet()
    {
		
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

        $this->assertInstanceOf(
            OSvCPHP\Client::class,
            $rn_client
        );
    }

	/* should take an options object with multiple queries and make a HTTP GET Request */ 

	public function testShouldTakeAnOptionsObjectWithMultipleQueriesAndMakeAHTTPGETRequest()
	{




	}




	/* should catch an error if there is an error */ 

	public function testShouldCatchAnErrorIfThereIsAnError()
	{




	}




	/* should return an error if the queries are not defined or if there is only one */ 

	public function testShouldReturnAnErrorIfTheQueriesAreNotDefinedOrIfThereIsOnlyOne()
	{




	}




	/* should return an error if there is only one query */ 

	public function testShouldReturnAnErrorIfThereIsOnlyOneQuery()
	{




	}




	/* should return a raw response object if the debug option is set to true */ 

	public function testShouldReturnARawResponseObjectIfTheDebugOptionIsSetToTrue()
	{




	}




	/* should return a raw error object if the debug option is set to true and a bad request is made */ 

	public function testShouldReturnARawErrorObjectIfTheDebugOptionIsSetToTrueAndABadRequestIsMade()
	{




	}


}