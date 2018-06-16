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

	/* should take "username", "password", and "interface" values and return a config hash with a base_url, and encoded login*/ 

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

		$testableConfig = $property->getValue($rn_client);

        $this->assertObjectHasAttribute('login',$testableConfig);
        $this->assertEquals($testableConfig->login,"QWRtaW46QWRtaW4gUGFzc3dvcmQ=");

        $this->assertObjectHasAttribute('base_url', $testableConfig);


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

		$testableConfig = $property->getValue($rn_client);
        $this->assertEquals($testableConfig->base_url,"https://interface.rightnowdemo.com/services/rest/connect/v1.3/");

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

		$testableConfig = $property->getValue($rn_client);
		
		$this->assertObjectHasAttribute('no_ssl_verify',$testableConfig);
        $this->assertEquals($testableConfig->no_ssl_verify,true);

        $this->assertObjectHasAttribute('suppress_rules',$testableConfig);
        $this->assertEquals($testableConfig->suppress_rules,true);


	}




	/* should raise an error if the object username is blank */ 

	public function testShouldRaiseAnErrorIfTheObjectUsernameIsBlank()
	{




	}




	/* should raise an error if the object password is blank */ 

	public function testShouldRaiseAnErrorIfTheObjectPasswordIsBlank()
	{




	}




	/* should raise an error if the object interface is blank */ 

	public function testShouldRaiseAnErrorIfTheObjectInterfaceIsBlank()
	{




	}




	/* should should have version set to "v1.3" if unspecified */ 

	public function testShouldShouldHaveVersionSetToV13IfUnspecified()
	{




	}




	/* should take an access token */ 

	public function testShouldTakeAnAccessToken()
	{




	}




}