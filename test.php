<?php

require_once("./src/Connect.php");

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$options = array(
    "client" => $rn_client,
    "url" => '/incidents/25871/fileAttachments?download'
);

$get_response = OSvCPHP\Connect::get($options);
echo json_encode($get_response,JSON_PRETTY_PRINT);