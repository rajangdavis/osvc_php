<?php

require_once(dirname(__FILE__) . '/../src/Client.php');
require_once(dirname(__FILE__) . '/../src/AnalyticsReportResults.php');

use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
{
    public function testIsAnInstanceOfClient()
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

	/* should take "username", "password", and "interface" values and return a config hash with a base_url, and encoded auth_header*/ 

	public function testShouldTakeUsernamePasswordAndInterfaceValuesFromAndObjectAndMatchThem()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => "Admin",
			"password" => "Admin Password",
			"interface" => "interface",
		));

		// http://djsipe.com/2017/04/21/testing-protected-php-methods-and-properties/
		$reflection = new \ReflectionClass($rn_client);
		$property   = $reflection->getProperty('config');
		$property->setAccessible(true);

		$testable_config = $property->getValue($rn_client);

        $this->assertObjectHasAttribute('auth_header',$testable_config);
        $this->assertEquals($testable_config->auth_header,"Basic QWRtaW46QWRtaW4gUGFzc3dvcmQ=");

        $this->assertObjectHasAttribute('base_url', $testable_config);


	}




	/* should take a "demo_site" from an object and set a url to use the  */ 

	public function testShouldTakeADemositeFromAnObjectAndSetAUrlToUseThe()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => "Admin",
			"password" => "Admin Password",
			"interface" => "interface",
			"demo_site" => true
		));

		// http://djsipe.com/2017/04/21/testing-protected-php-methods-and-properties/
		$reflection = new \ReflectionClass($rn_client);
		$property   = $reflection->getProperty('config');
		$property->setAccessible(true);

		$testable_config = $property->getValue($rn_client);
        $this->assertEquals($testable_config->base_url,"https://interface.rightnowdemo.com/services/rest/connect/v1.3/");

	}




	/* should take "no_ssl_verify","version", and "suppress_rules" values from an object and match them */ 

	public function testShouldTakeNosslverifyversionAndSuppressrulesValuesFromAnObjectAndMatchThem()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => "Admin",
			"password" => "Admin Password",
			"interface" => "interface",
			"demo_site" => true,
			"no_ssl_verify" => true,
			"suppress_rules" => true,
		));

		// http://djsipe.com/2017/04/21/testing-protected-php-methods-and-properties/
		$reflection = new \ReflectionClass($rn_client);
		$property   = $reflection->getProperty('config');
		$property->setAccessible(true);

		$testable_config = $property->getValue($rn_client);
		
		$this->assertObjectHasAttribute('no_ssl_verify',$testable_config);
        $this->assertEquals($testable_config->no_ssl_verify,true);

        $this->assertObjectHasAttribute('suppress_rules',$testable_config);
        $this->assertEquals($testable_config->suppress_rules,true);


	}


	/**
     * @expectedException Exception
     */

	/* should raise an error if the object username is blank */ 

	public function testShouldRaiseAnErrorIfTheObjectUsernameIsBlank()
	{

		$rn_client = new OSvCPHP\Client(array(
			"password" => "Admin Password",
			"interface" => "interface",
		));


	}


	/**
     * @expectedException Exception
     */

	/* should raise an error if the object password is blank */ 

	public function testShouldRaiseAnErrorIfTheObjectPasswordIsBlank()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => "Admin Username",
			"interface" => "interface",
		));


	}


	/**
     * @expectedException Exception
     */

	/* should raise an error if the object interface is blank */ 

	public function testShouldRaiseAnErrorIfTheObjectInterfaceIsBlank()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => "Admin Username",
			"password" => "Admin Password",
		));

	}




	/* should should have version set to "v1.3" if unspecified */ 

	public function testShouldShouldHaveVersionSetToV13IfUnspecified()
	{

		$rn_client = new OSvCPHP\Client(array(
			"username" => "Admin",
			"password" => "Admin Password",
			"interface" => "interface",
		));

		// http://djsipe.com/2017/04/21/testing-protected-php-methods-and-properties/
		$reflection = new \ReflectionClass($rn_client);
		$property   = $reflection->getProperty('config');
		$property->setAccessible(true);

		$testable_config = $property->getValue($rn_client);
        $this->assertEquals($testable_config->base_url,"https://interface.custhelp.com/services/rest/connect/v1.3/");



        $rn_client_latest = new OSvCPHP\Client(array(
			"username" => "Admin",
			"password" => "Admin Password",
			"interface" => "interface",
			"version" => "v1.4"
		));

		// http://djsipe.com/2017/04/21/testing-protected-php-methods-and-properties/
		$reflection_latest = new \ReflectionClass($rn_client_latest);
		$property_latest   = $reflection_latest->getProperty('config');
		$property_latest->setAccessible(true);

		$testable_latest_config = $property_latest->getValue($rn_client_latest);
        $this->assertEquals($testable_latest_config->base_url,"https://interface.custhelp.com/services/rest/connect/v1.4/");


	}




	/* should take an access token */ 

	public function testShouldTakeAnAccessToken()
	{

		$rn_client_access_token = new OSvCPHP\Client(array(
			"username" => "Admin",
			"password" => "Admin Password",
			"interface" => "interface",
			"version" => "v1.4",
			"access_token" => "this is an access token"
		));

		// http://djsipe.com/2017/04/21/testing-protected-php-methods-and-properties/
		$reflection_access_token = new \ReflectionClass($rn_client_access_token);
		$property_access_token   = $reflection_access_token->getProperty('config');
		$property_access_token->setAccessible(true);

		$testable_access_token_config = $property_access_token->getValue($rn_client_access_token);
        $this->assertEquals($testable_access_token_config->access_token,"this is an access token");


	}




}