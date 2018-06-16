<?php

require_once(dirname(__FILE__) . '/../src/Connect.php');

use PHPUnit\Framework\TestCase;

final class ConnectTest extends TestCase
{	

// connect.get
	/* should take a url as a param and make a HTTP GET Request with a response code of 200 and a body of JSON */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPGETRequestWithAResponseCodeOf200AndABodyOfJSON()
	{




	}



//   connect.get download functionality

	/* should download a file if there is a "?download" query parameter */ 

	public function testShouldDownloadAFileIfThereIsADownloadQueryParameter()
	{




	}




	/* should create a tgz file if there is a "?download" query parameter and multiple files */ 

	public function testShouldCreateATgzFileIfThereIsADownloadQueryParameterAndMultipleFiles()
	{




	}



//   connect.post

	/* should take a url and debug parameters and make a HTTP POST Request with a response code of 201 and a body of JSON object */ 

	public function testShouldTakeAUrlAndDebugParametersAndMakeAHTTPPOSTRequestWithAResponseCodeOf201AndABodyOfJSONObject()
	{




	}




	/* should take a url as a param and make a HTTP POST Request and return a JSON object */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPPOSTRequestAndReturnAJSONObject()
	{




	}



//   connect.post upload functionality

	/* should upload one file */ 

	public function testShouldUploadOneFile()
	{




	}




	/* should upload multiple files */ 

	public function testShouldUploadMultipleFiles()
	{




	}




	/* should return an error if a file does not exist in the specified file location */ 

	public function testShouldReturnAnErrorIfAFileDoesNotExistInTheSpecifiedFileLocation()
	{




	}


//   connect.patch

	/* should take a url as a param and make a HTTP PATCH Request with a response code of 201 and an empty body */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPPATCHRequestWithAResponseCodeOf201AndAnEmptyBody()
	{




	}


//   connect.delete

	/* should take a url as a param and make a HTTP DELETE Request with a response code of 404 because the incident with ID of 0 does not exist */ 

	public function testShouldTakeAUrlAsAParamAndMakeAHTTPDELETERequestWithAResponseCodeOf404BecauseTheIncidentWithIDOf0DoesNotExist()
	{




	}

//   connect.options

	/* should be able to make an OPTIONS request and send back the headers */ 

	public function testShouldBeAbleToMakeAnOPTIONSRequestAndSendBackTheHeaders()
	{




	}


}