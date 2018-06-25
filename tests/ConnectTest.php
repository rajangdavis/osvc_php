<?php

require_once(dirname(__FILE__) . '/../src/Connect.php');
require_once(dirname(__FILE__) . '/../src/Client.php');

use PHPUnit\Framework\TestCase;

final class ConnectTest extends TestCase
{	

// connect.get
	/* should take a url as a param and make a HTTP GET Request with a response code of 200 and a body of JSON */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPGETRequestWithAResponseCodeOf200AndABodyOfJSON()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
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



//   connect.get download functionality

	/* should download a file if there is a "?download" query parameter */ 

	public function testShouldDownloadAFileIfThereIsADownloadQueryParameter()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => '/incidents/25872/fileAttachments/417?download'
		);

		$get_response = OSvCPHP\Connect::get($options);
		$this->assertEquals(1, $get_response);
		$this->assertFileExists("./haQE7EIDQVUyzoLDha2SRVsP415IYK8_ocmxgMfyZaw.png");
		unlink("./haQE7EIDQVUyzoLDha2SRVsP415IYK8_ocmxgMfyZaw.png");
		$this->assertFileNotExists("./haQE7EIDQVUyzoLDha2SRVsP415IYK8_ocmxgMfyZaw.png");
	}




	/* should create a tgz file if there is a "?download" query parameter and multiple files */ 

	public function testShouldCreateATgzFileIfThereIsADownloadQueryParameterAndMultipleFiles()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => '/incidents/25872/fileAttachments?download'
		);

		$get_response = OSvCPHP\Connect::get($options);
		$this->assertEquals(1, $get_response);
		$this->assertFileExists("./downloadedAttachment.tgz");
		unlink("./downloadedAttachment.tgz");
		$this->assertFileNotExists("./downloadedAttachment.tgz");


	}



//   connect.post

	/* should take a url and debug parameters and make a HTTP POST Request with a response code of 201 and a body of JSON object */ 

	public function testShouldTakeAUrlAndDebugParametersAndMakeAHTTPPOSTRequestWithAResponseCodeOf201AndABodyOfJSONObject()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
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

	}




	/* should take a url as a param and make a HTTP POST Request and return a JSON object */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPPOSTRequestAndReturnAJSONObject()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
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
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => "incidents",
		    "json" =>  array(
		        "primaryContact"=>  array(
		            "id"=>  2
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
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => "incidents",
		    "json" =>  array(
		        "primaryContact"=>  array(
		            "id"=>  2
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
	}



	/**
     * @expectedException Exception
     */

	/* should return an error if a file does not exist in the specified file location */ 
	public function testShouldReturnAnErrorIfAFileDoesNotExistInTheSpecifiedFileLocation()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
		    "client" => $rn_client,
		    "url" => "incidents",
		    "json" =>  array(
		        "primaryContact"=>  array(
		            "id"=>  2
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

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPPATCHRequestWithAResponseCodeOf201AndAnEmptyBody()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$updated_product = array(
		    'names' => array(
		        array(
		            'labelText' => 'UPDATED_PRODUCT',
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
			"url" => "serviceProducts/268",
			"json" => $updated_product,
			"debug" => true
		);

		$patch_response = OSvCPHP\Connect::patch($options);
		$this->assertEquals($patch_response['info']['http_code'], 200);
		$this->assertEquals($patch_response['body'], '');


	}


//   connect.delete

	/* should take a url as a param and make a HTTP DELETE Request with a response code of 404 because the incident with ID of 0 does not exist */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPDELETERequestWithAResponseCodeOf404BecauseTheIncidentWithIDOf0DoesNotExist()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
		));

		$options = array(
			"client" => $rn_client,
			"url" => "serviceProducts/0",
			"debug" => true
		);

		$delete_response = OSvCPHP\Connect::delete($options);
		$this->assertEquals($delete_response['info']['http_code'], 404);

	}

//   connect.options

	/* should be able to make an OPTIONS request and send back the headers */ 

	public function testShouldBeAbleToMakeAnOPTIONSRequestAndSendBackTheHeaders()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
			"demo_site" => true
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
			"username" => "OSC_ADMIN",
			"password" => "OSC_PASSWORD",
			"interface" => "OSC_SITE",
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
			"username" => getenv("OSC_ADMIN"),
			"password" => getenv("OSC_PASSWORD"),
			"interface" => getenv("OSC_SITE"),
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
			"username" => "OSC_ADMIN",
			"password" => "OSC_PASSWORD",
			"interface" => "OSC_SITE",
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

}