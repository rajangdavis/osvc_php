<?php

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),		# => These are interface credentials
    "password" => getenv("OSC_PASSWORD"),	# => store these in environmental
    "interface" => getenv("OSC_SITE"),		# => variables in your .bash_profile
    "demo_site" => true
));


// $patch_data = array(

// 	"subject" => "FishPhone not working UPDATED"

// );

// $patch_response = OSvCPHP\Connect::patch($rn_client,'/incidents/' . $post_response['body']->id, $patch_data);

// echo json_encode($patch_response,JSON_PRETTY_PRINT);

// echo "\n";
// echo "\n";


// ///////////////////////////////////////////////////////////////////////////////////////////////
// $delete_response = OSvCPHP\Connect::delete($rn_client,'/incidents/' . $post_response['body']->id);

// echo json_encode($delete_response,JSON_PRETTY_PRINT);

// echo "\n";
// echo "\n";
// echo "\n";