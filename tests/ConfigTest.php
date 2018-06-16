<?php

require_once(dirname(__FILE__) . '/../src/Config.php');

use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{	

	/* should take an HTTP verb and set the method property to match that verb */ 

	public function testShouldTakeAnHTTPVerbAndSetTheMethodPropertyToMatchThatVerb()
	{




	}




	/* should take a client object and return a hash of options settings; the "suppress_rules" setting should return headers["OSvC-CREST-Suppress-All"] as true */ 

	public function testShouldTakeAClientObjectAndReturnAHashOfOptionsSettingsTheSuppressrulesSettingShouldReturnHeadersOSvCCRESTSuppressAllAsTrue()
	{




	}




	/* should take a boolean param for making PATCH requests; headers["X-HTTP-Method-Override"] as "PATCH" */ 

	public function testShouldTakeABooleanParamForMakingPATCHRequestsHeadersXHTTPMethodOverrideAsPATCH()
	{




	}




	/* should have headers set to undefined as a default */ 

	public function testShouldHaveHeadersSetToUndefinedAsADefault()
	{




	}




	/* should always use "https" and "/services/rest/connect" in the url */ 

	public function testShouldAlwaysUseHttpsAndServicesRestConnectInTheUrl()
	{




	}




	/* should take a client object and change the url to include "rightnowdemo" if the "demo_site" setting is set to true */ 

	public function testShouldTakeAClientObjectAndChangeTheUrlToIncludeRightnowdemoIfTheDemositeSettingIsSetToTrue()
	{




	}




	/* should take a username,password,and interface and change the url to include the interface */ 

	public function testShouldTakeAUsernamepasswordandInterfaceAndChangeTheUrlToIncludeTheInterface()
	{




	}




	/* should take a username,password and return an authorization header for basic auth */ 

	public function testShouldTakeAUsernamepasswordAndReturnAnAuthorizationHeaderForBasicAuth()
	{




	}




	/* should take a client object and change the url to include a different verions if the "version" setting is changed */ 

	public function testShouldTakeAClientObjectAndChangeTheUrlToIncludeADifferentVerionsIfTheVersionSettingIsChanged()
	{




	}




	/* should take a resource URL and change the url */ 

	public function testShouldTakeAResourceURLAndChangeTheUrl()
	{




	}




	/* should equal "https://interface789.rightnowdemo.com/services/rest/connect/v1.4/incidents" */ 

	public function testShouldEqualHttpsinterface789rightnowdemocomservicesrestconnectv14incidents()
	{




	}




	/* should take a not match incidents if the resource URL is not specified */ 

	public function testShouldTakeANotMatchIncidentsIfTheResourceURLIsNotSpecified()
	{




	}




	/* "version" should be "v1.3/" if not specified */ 

	public function testVersionShouldBeV13IfNotSpecified()
	{




	}




	/* "custhelp" domain will be used if not specified */ 

	public function testCusthelpDomainWillBeUsedIfNotSpecified()
	{




	}




	/* should equal "https://interface789.custhelp.com/services/rest/connect/v1.3/" */ 

	public function testShouldEqualHttpsinterface789custhelpcomservicesrestconnectv13()
	{




	}




	/* should be able to set a session id and it should work if retrieved */ 

	public function testShouldBeAbleToSetASessionIdAndItShouldWorkIfRetrieved()
	{




	}




	/* should be able to set an oauth token for authentication */ 

	public function testShouldBeAbleToSetAnOauthTokenForAuthentication()
	{




	}




	/* should be able to set an access token for authentication */ 

	public function testShouldBeAbleToSetAnAccessTokenForAuthentication()
	{




	}




	/* should be able to set optional headers */ 

	public function testShouldBeAbleToSetOptionalHeaders()
	{




	}




	/* should throw an error if version is set to "v1.4" and no annotation is present */ 

	public function testShouldThrowAnErrorIfVersionIsSetToV14AndNoAnnotationIsPresent()
	{




	}




	/* should throw an error if annotation is present but blank */ 

	public function testShouldThrowAnErrorIfAnnotationIsPresentButBlank()
	{




	}




	/* should throw an error if annotation is greater than 40 characters */ 

	public function testShouldThrowAnErrorIfAnnotationIsGreaterThan40Characters()
	{




	}




}