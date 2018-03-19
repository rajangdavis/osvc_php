<?php

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));



///////////////////////////////////////////////////////////////////////////////////////////////

$data = array(

	"primaryContact" => array(
    	"id" => 2
	),
	"subject" => "FishPhone not working"

);
$post_response = OSvCPHP\Connect::post($rn_client,'/incidents',$data);

echo json_encode($post_response,JSON_PRETTY_PRINT);


///////////////////////////////////////////////////////////////////////////////////////////////
$get_response = OSvCPHP\Connect::get($rn_client,'/incidents/' . $post_response['body']->id);

echo json_encode($get_response,JSON_PRETTY_PRINT);

echo "\n";
echo "\n";
echo "\n";

///////////////////////////////////////////////////////////////////////////////////////////////
$patch_data = array(

	"subject" => "FishPhone not working UPDATED"

);

$patch_response = OSvCPHP\Connect::patch($rn_client,'/incidents/' . $post_response['body']->id, $patch_data);

echo json_encode($patch_response,JSON_PRETTY_PRINT);

echo "\n";
echo "\n";
echo "\n";


///////////////////////////////////////////////////////////////////////////////////////////////
$delete_response = OSvCPHP\Connect::delete($rn_client,'/incidents/' . $post_response['body']->id);

echo json_encode($delete_response,JSON_PRETTY_PRINT);

echo "\n";
echo "\n";
echo "\n";