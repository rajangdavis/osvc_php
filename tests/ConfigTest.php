<?php

require_once(dirname(__FILE__) . '/../src/Config.php');
require_once(dirname(__FILE__) . '/../src/Client.php');

use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{	

	/* should take a client object and return a hash of options settings; the "suppress_rules" setting should return a config object with "suppress_rules" as true */ 

	public function testShouldTakeAClientObjectAndReturnAHashOfOptionsSettingsTheSuppressrulesSettingShouldReturnHeadersOSvCCRESTSuppressAllAsTrue()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interfaceName",
			"suppress_rules" => true
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals(true,$config->suppress_rules);


	}



	/* should always use "https" and "/services/rest/connect" in the url */ 

	public function testShouldAlwaysUseHttpsAndServicesRestConnectInTheUrl()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interfaceName",
			"suppress_rules" => true
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("https://interfaceName.custhelp.com/services/rest/connect/v1.3/",$config->base_url);


	}




	/* should take a client object and change the url to include "rightnowdemo" if the "demo_site" setting is set to true */ 

	public function testShouldTakeAClientObjectAndChangeTheUrlToIncludeRightnowdemoIfTheDemositeSettingIsSetToTrue()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interfaceName",
			"demo_site" => true
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("https://interfaceName.rightnowdemo.com/services/rest/connect/v1.3/",$config->base_url);


	}


	/* should take a username,password and return an authorization header for basic auth */ 

	public function testShouldTakeAUsernamepasswordAndReturnAnAuthorizationHeaderForBasicAuth()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interfaceName",
			"demo_site" => true
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals('Basic YWRtaW46cGFzc3dvcmQ=',$config->auth_header);


	}




	/* should take a client object and change the url to include a different verions if the "version" setting is changed */ 

	public function testShouldTakeAClientObjectAndChangeTheUrlToIncludeADifferentVerionsIfTheVersionSettingIsChanged()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interfaceName",
			"version" => "latest"
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("https://interfaceName.custhelp.com/services/rest/connect/latest/",$config->base_url);


	}



	/* should equal "https://interface789.rightnowdemo.com/services/rest/connect/v1.4" */ 

	public function testShouldEqualHttpsinterface789rightnowdemocomservicesrestconnectv14()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interface789",
			"version" => "v1.4"
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("https://interface789.custhelp.com/services/rest/connect/v1.4/",$config->base_url);


	}




	/* "version" should be "v1.3/" if not specified */ 

	public function testVersionShouldBeV13IfNotSpecified()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interface789",
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("https://interface789.custhelp.com/services/rest/connect/v1.3/",$config->base_url);


	}


	/* should be able to set a session id and it should work if retrieved */ 

	public function testShouldBeAbleToSetASessionIdAndItShouldWorkIfRetrieved()
	{

		$options = array(
			"session" => "session_for_authentication",
			"interface" => "interface789",
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("Session session_for_authentication",$config->auth_header);


	}




	/* should be able to set an oauth token for authentication */ 

	public function testShouldBeAbleToSetAnOauthTokenForAuthentication()
	{

		$options = array(
			"oauth" => "oauth_for_authentication",
			"interface" => "interface789",
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("Bearer oauth_for_authentication",$config->auth_header);


	}




	/* should be able to set an access token for authentication */ 

	public function testShouldBeAbleToSetAnAccessTokenForAuthentication()
	{

		$options = array(
			"username" => "admin",
			"password" => "password",
			"interface" => "interface789",
			"access_token" => "access_token_for_quality_of_service"
		);

		$config = new OSvCPHP\Config($options);


		$this->assertEquals("access_token_for_quality_of_service",$config->access_token);


	}


}