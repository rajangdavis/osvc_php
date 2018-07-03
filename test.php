<?php 

include("./src/Connect.php");

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demosite" => true
));


$options = array(
	"client" => $rn_client,
	"url" => "serviceProducts",
	// "schema" => true
);

$get_response = OSvCPHP\Connect::get($options);

//echo json_encode($get_response, JSON_PRETTY_PRINT);
echo "sup";