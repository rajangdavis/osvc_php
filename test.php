<?php

require_once("./src/Connect.php");

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true,
    "version" => "latest"
));

$options = array(
    "debug" => true,
    "client" => $rn_client,
    "url" => "incidents",
    "json" =>  array(
        "primaryContact"=>  array(
            "id"=>  2
        ),
        "subject"=>  "FishPhone not working"
    ),
     "files" => array(
        "./License.txt",
    ),
    "annotation" => "shorter annotation"
);

$post_response = OSvCPHP\Connect::options($options);
echo json_encode($post_response,JSON_PRETTY_PRINT);