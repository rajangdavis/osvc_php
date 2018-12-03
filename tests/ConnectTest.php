<?php

require_once(dirname(__FILE__) . '/../src/Connect.php');
require_once(dirname(__FILE__) . '/../src/Client.php');

use PHPUnit\Framework\TestCase;


final class ConnectTest extends TestCase
{	
	// This is to make changes on the same incident
	// as the tests are ran
	// public $test_incident_id;

	// public function modifyIncId($new_id)
	// {
	// 	$this->$test_incident_id = $new_id;
	// 	echo "Id has been modified;\n It is now $this->$test_incident_id";
	// }

// connect.get
	/* should take a url as a param and make a HTTP GET Request with a response code of 200 and a body of JSON */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPGETRequestWithAResponseCodeOf200AndABodyOfJSON()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
			"client" => $rn_client,
			"url" => "incidents?limit=10",
		);

		$results = OSvCPHP\Connect::get($options);

		$this->assertObjectHasAttribute("items", $results);
		$this->assertObjectHasAttribute("links", $results);
		$this->assertObjectHasAttribute("hasMore", $results);
	}

//   connect.post

	/* should take a url and debug parameters and make a HTTP POST Request with a response code of 201 and a body of JSON object */ 

	public function testShouldTakeAUrlAndDebugParametersAndMakeAHTTPPOSTRequestWithAResponseCodeOf201AndABodyOfJSONObject()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$new_product = array(
		    'names' => array(
		        array(
		            'labelText' => 'NEW_PRODUCT',
		            'language' => array('id' => 1)
		        )
		    ),
		    'displayOrder' => 4,
		    'adminVisibleInterfaces' => array(
		        array(
		            'id' => 1
		        )
		    ),
		    'endUserVisibleInterfaces' => array(
		        array(
		            'id' => 1
		        )
		    ),
		);


		$options = array(
			"client" => $rn_client,
			"url" => "serviceProducts",
			"json" => $new_product,
			"debug" => true
		);

		$post_response = OSvCPHP\Connect::post($options);

		$this->assertEquals($post_response['info']['http_code'], 201);
		$this->assertArrayHasKey("body", $post_response);
		$this->assertArrayHasKey("info", $post_response);
		return $post_response['body']->id;
	}



	/* should take a url as a param and make a HTTP POST Request and return a JSON object */ 
	public function testShouldTakeAUrlAsAParamAndMakeAHTTPPOSTRequestAndReturnAJSONObject()
	{
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$new_product = array(
		    'names' => array(
		        array(
		            'labelText' => 'NEW_PRODUCT_TEST',
		            'language' => array('id' => 1)
		        ) 
		    ),
		    'displayOrder' => 4,
		    'adminVisibleInterfaces' => array(
		        array(
		            'id' => 1
		        )
		    ),
		    'endUserVisibleInterfaces' => array(
		        array(
		            'id' => 1
		        )
		    ),
		);


		$options = array(
			"client" => $rn_client,
			"url" => "serviceProducts",
			"json" => $new_product,
		);

		$post_response = OSvCPHP\Connect::post($options);

		$this->assertObjectHasAttribute("id", $post_response);
		$this->assertObjectHasAttribute("lookupName", $post_response);
	}



//   connect.post upload functionality

	/* should upload one file */ 

	public function testShouldUploadOneFile()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => "incidents",
		    "json" =>  array(
		        "primaryContact"=>  array(
		            "id"=>  8
		        ),
		        "subject"=>  "FishPhone not working"
		    ), "files" => array(
		        "./composer.json",
		    )
		);

		$post_response = OSvCPHP\Connect::post($options);
		$this->assertObjectHasAttribute("id", $post_response);
		$options['url'] .= "/$post_response->id/fileAttachments";

		$get_response = OSvCPHP\Connect::get($options);

		$this->assertEquals(1, sizeof($get_response->items));


	}


	/* should upload multiple files */ 

	public function testShouldUploadMultipleFiles()
	{
		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => "incidents",
		    "json" =>  array(
		        "primaryContact"=>  array(
		            "id"=>  8
		        ),
		        "subject"=>  "FishPhone not working"
		    ), "files" => array(
		        "./composer.json",
		        "./License.txt",
		    )
		);

		$post_response = OSvCPHP\Connect::post($options);
		$this->assertObjectHasAttribute("id", $post_response);

		$options['url'] .= "/$post_response->id/fileAttachments";

		$get_response = OSvCPHP\Connect::get($options);
		$this->assertEquals(2, sizeof($get_response->items));
		return $post_response->id;
	}

	/* should create a tgz file if there is a "?download" query parameter and multiple files */ 
	/**
	* @depends testShouldUploadMultipleFiles
	*/
	public function testShouldCreateATgzFileIfThereIsADownloadQueryParameterAndMultipleFiles($test_incident_id)
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => "/incidents/$test_incident_id/fileAttachments?download"
		);

		$get_response = OSvCPHP\Connect::get($options);
		echo json_encode($get_response, JSON_PRETTY_PRINT);
		$this->assertEquals(1, $get_response);
		$this->assertFileExists("./downloadedAttachment.tgz");
		unlink("./downloadedAttachment.tgz");
		$this->assertFileNotExists("./downloadedAttachment.tgz");
	}

	/**
     * @expectedException Exception
     */

	/* should return an error if a file does not exist in the specified file location */ 
	public function testShouldReturnAnErrorIfAFileDoesNotExistInTheSpecifiedFileLocation()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => "incidents",
		    "json" =>  array(
		        "primaryContact"=>  array(
		            "id"=>  8
		        ),
		        "subject"=>  "FishPhone not working"
		    ), "files" => array(
		        "./NonExistentFile",
		    )
		);

		$post_response = OSvCPHP\Connect::post($options);
	}


//   connect.patch

	/* should take a url as a param and make a HTTP PATCH Request with a response code of 201 and an empty body */ 
	/**
	* @depends testShouldUploadMultipleFiles
	*/
	public function testShouldTakeAUrlAsAParamAndMakeAHTTPPATCHRequestWithAResponseCodeOf201AndAnEmptyBody($test_incident_id)
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$updated_product = array(
		    'subject' => "UPDATED SUBJECT"
		);


		$options = array(
			"client" => $rn_client,
			"url" => "incidents/$test_incident_id",
			"json" => $updated_product,
			"debug" => true
		);

		$patch_response = OSvCPHP\Connect::patch($options);
		$this->assertEquals($patch_response['info']['http_code'], 200);
		$this->assertEquals($patch_response['body'], '');


	}


//   connect.delete

	/* should take a url as a param and make a HTTP DELETE Request with a response code of 404 because the incident with ID of 0 does not exist */ 
	/**
	* @depends testShouldUploadMultipleFiles
	*/
	public function testShouldTakeAUrlAsAParamAndMakeAHTTPDELETERequest($test_incident_id)
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
			"client" => $rn_client,
			"url" => "incidents/$test_incident_id",
			"debug" => true
		);

		$delete_response = OSvCPHP\Connect::delete($options);
		$this->assertEquals($delete_response['info']['http_code'], 200);

	}

//   connect.options

	/* should be able to make an OPTIONS request and send back the headers */ 

	public function testShouldBeAbleToMakeAnOPTIONSRequestAndSendBackTheHeaders()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
			"client" => $rn_client,
			"url" => "serviceProducts",
			"debug" => true
		);

		$options_response = OSvCPHP\Connect::options($options);
		$this->assertEquals($options_response['body'], null);
	}



	/**
     * @expectedException Exception
     */

	/* should throw an error if version is set to "v1.4" and no annotation is present */ 

	public function testShouldThrowAnErrorIfVersionIsSetToV14AndNoAnnotationIsPresent()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => "OSVC_ADMIN",
			"password" => "OSVC_PASSWORD",
			"interface" => "OSVC_SITE",
			"version" => "v1.4"
		));

		$options = array(
			"client" => $rn_client,
			"url" => "serviceProducts",
			"debug" => true
		);

		$options_response = OSvCPHP\Connect::options($options);


	}


	/**
     * @expectedException Exception
     */

	/* should throw an error if annotation is greater than 40 characters */ 

	public function testShouldThrowAnErrorIfAnnotationIsGreaterThan40Characters()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE"),
			"version" => "v1.4"
		));

		$options = array(
			"client" => $rn_client,
			"url" => "serviceProducts",
			"annotation" => "a super duper long annotation that should raise an exception"
		);

		$options_response = OSvCPHP\Connect::options($options);

	}



	/* should be able to set optional headers */ 

	public function testShouldBeAbleToSetOptionalHeaders()
	{
		$rn_client = new OSvCPHP\Client(array(
			"username" => "OSVC_ADMIN",
			"password" => "OSVC_PASSWORD",
			"interface" => "OSVC_SITE",
			"version" => "v1.4"
		));

		$options = array(
			"client" => $rn_client,
			"url" => "serviceProducts",
			"exclude_null" => true,
			"next_request" => 1000,
			"schema" => true,
			"utc_time" => true,
			"annotation" => "This is an annotation"
		);

		$method = new ReflectionMethod("OSvCPHP\Connect", "_optional_headers");
		$method->setAccessible(true);

		$headers =  $method->invoke(new OSvCPHP\Connect, $options, array());

		$this->assertArraySubset($headers, array("prefer: exclude-null-properties","osvc-crest-next-request-after: 1000","Accept: application/schema+json","OSvC-CREST-Time-UTC: yes"));
	}

	public function testShouldFollowRedirectsForJsonSchema()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSVC_ADMIN"),
			"password" => getenv("OSVC_PASSWORD"),
			"interface" => getenv("OSVC_SITE")
		));

		$options = array(
			"client" => $rn_client,
			"url" => "incidents",
			"schema" => true
		);

		$results = OSvCPHP\Connect::get($options);

		$this->assertNotEquals($results,null);
	}

}