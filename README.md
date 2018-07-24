# OSvCPHP
[![Maintainability](https://api.codeclimate.com/v1/badges/985aec5962103587634f/maintainability)](https://codeclimate.com/github/rajangdavis/osvc_php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/985aec5962103587634f/test_coverage)](https://codeclimate.com/github/rajangdavis/osvc_php/test_coverage)
[![Build Status](https://travis-ci.org/rajangdavis/osvc_php.svg?branch=master)](https://travis-ci.org/rajangdavis/osvc_php)
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Frajangdavis%2Fosvc_php.svg?type=shield)](https://app.fossa.io/projects/git%2Bgithub.com%2Frajangdavis%2Fosvc_php?ref=badge_shield)


An (under development) PHP library for using the [Oracle Service Cloud REST API](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/) influenced by the [ConnectPHP API](http://documentation.custhelp.com/euf/assets/devdocs/november2016/Connect_PHP/Default.htm)

## Installing PHP (for Windows)
[Here is a Youtube video with instructions for Windows 10](https://www.youtube.com/watch?v=D-wFWUMHcUA). I would highly recommend installing PHP 7.  
If you get SSL Errors (you probably will), follow [this link for instructions on resolving SSL things that I know nothing about](https://stackoverflow.com/a/18972719/2548452).
   
## Installation

Install with composer:

    $ composer require rajangdavis/osvc_php --dev
   
## Compatibility

This PHP library tested against Oracle Service Cloud May 2017 using PHP 7.2.1. 

It is tested against versions 7.2.1 and 5.6.2 on Travis CI.

All of the HTTP methods should work on any version of Oracle Service Cloud since version May 2015; however, there maybe some issues with querying items on any version before May 2016. This is because ROQL queries were not exposed via the REST API until May 2016.

## Basic Usage
The features that work to date are as follows:

1. [HTTP Methods](#http-methods)
	1. For creating objects and [uploading one or more file attachments](#uploading-file-attachments), make a [POST request with the OSvCPHP\Connect Object](#post)
	2. For reading objects and [downloading one or more file attachments](#downloading-file-attachments), make a [GET request with the OSvCPHP\Connect Object](#get)
	3. For updating objects, make a [PATCH request with the OSvCPHP\Connect Object](#patch)
	4. For deleting objects, make a [DELETE request with the OSvCPHP\Connect Object](#delete)
	5. For looking up options for a given URL, make an [OPTIONS request with the OSvCPHP\Connect Object](#options)
2. Running ROQL queries [either 1 at a time](#osvcphpqueryresults-example) or [multiple queries in a set](#osvcphpqueryresultsset-example)
3. [Running Reports](#osvcphpanalyticsreportsresults)
4. [Optional Settings](#optional-settings)

Here are the _spicier_ (more advanced) features:

1. [Bulk Delete](#bulk-delete)
2. [Running multiple ROQL Queries concurrently](#running-multiple-roql-queries-concurrently)
3. [Performing Session Authentication](#performing-session-authentication)

## Authentication

An OSvCPHP\Client class lets the library know which credentials and interface to use for interacting with the Oracle Service Cloud REST API.
This is helpful if you need to interact with multiple interfaces or set different headers for different objects.

```php

// Configuration is as simple as requiring the library
// and passing in an associative array

require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),			# => These are interface credentials
	"password" => getenv("OSC_PASSWORD"),			# => store these in environmental
	"interface" => getenv("OSC_SITE"),			# => variables in your .bash_profile

	// Session Authentication
	// "session" => <session ID>,

	// OAuth Token Authentication
	// "oauth" => <oauth token>,

	### optional configuration
	# Use 'rightnowdemo' namespace instead of 'custhelp'
	"demo_site" => true					# => Defaults to false. 

	# Sets the version of the REST API to use
	"version" => 'v1.4',					# => Defaults to 'v1.3'. 
	
	# Turns off SSL verification; don't use in production
	"no_ssl_verify" => true,				# => Defaults to false. 
	
	# Lets you supress business rules
	"suppress_rules" => true,				# => Defaults to false. 

	# Lets you supress external events
	"suppress_events" => true,				# => Defaults to false. 

	# Lets you supress external events and business rules
	"suppress_all" => true,				# => Defaults to false. 
	
	# Adds an access token to ensure quality of service
	"access_token" =>  "My access token" 		
));


```
## Optional Settings

In addition to a client to specify which credentials, interface, and CCOM version to use, you will need to create an options object to pass in the client as well as specify any additional parameters that you may wish to use.

Here is an example using the client object created in the previous section:
```php

require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),	
    "password" => getenv("OSC_PASSWORD"),
    "interface" => getenv("OSC_SITE"),
    "suppress_rules" => true	
));

$options = array(
	
	// set the client for the request
	"client" => $rn_client,

	// Adds a custom header that adds an annotation (CCOM version must be set to "v1.4" or "latest"); limited to 40 characters
	"annotation" => "Custom annotation",

	// Prints request headers for debugging  
	"debug" => true,

	// Adds a custom header to excludes null from results; for use with GET requests only                 	 
	"exclude_null" => true,

	// Number of milliseconds before another HTTP request can be made; this is an anti-DDoS measure
	"next_request" => 500,

	// Sets 'Accept' header to 'application/schema+json'
	"schema" => true,

	// Adds a custom header to return results using Coordinated Universal Time (UTC) format for time (Supported on November 2016+
	"utc_time" => true              	 
);

```


## HTTP Methods

To use various HTTP Methods to return raw response objects, use the "Connect" object

### POST
```php
//// OSvCPHP\Connect::post(options)
//// returns an object

// Here's how you could create a new ServiceProduct object
// using PHP variables and associative arrays (sort of like JSON)

require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

// JSON object
// containing data
// for creating
// a new product 

$new_product = array(
    'names' => array(
        array(
            'labelText' => 'NEW_PRODUCT',
            'language' => array('id' => 1)
        )
    ),
    'displayOrder' => 4,
    'adminVisibleInterfaces' => array(
        array(
            'id' => 1
        )
    ),
    'endUserVisibleInterfaces' => array(
        array(
            'id' => 1
        )
    ),
);


$options = array(
	"client" => $rn_client,
	"url" => "serviceProducts",
	"json" => $new_product,
	"debug" => true
);

$post_response = OSvCPHP\Connect::post($options);

```

### GET
```php
//// OSvCPHP\Connect::get(options)
//// returns an object
// Here's how you could get an instance of ServiceProducts

require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));


$options = array(
	"client" => $rn_client,
	"url" => "serviceProducts/56",
);

$post_response = OSvCPHP\Connect::get($options);
```

### PATCH
```php
//// OSvCPHP\Connect::patch(options)
//// returns an object
// Here's how you could update a Service Product object
// using JSON objects
// to set field information
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$updated_product = array(
    'names' => array(
        array(
            'labelText' => 'UPDATED_PRODUCT',
            'language' => array('id' => 1)
        )
    ),
    'displayOrder' => 4,
    'adminVisibleInterfaces' => array(
        array(
            'id' => 1
        )
    ),
    'endUserVisibleInterfaces' => array(
        array(
            'id' => 1
        )
    ),
);


$options = array(
	"client" => $rn_client,
	"url" => "serviceProducts/268",
	"json" => $updated_product,
);

$patch_response = OSvCPHP\Connect::patch($options);
```

### DELETE
```php
//// OSvCPHP\Connect::delete(options)
//// returns an object
// Here's how you could delete a serviceProduct object
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$options = array(
	"client" => $rn_client,
	"url" => "serviceProducts/56",
);

$delete_response = OSvCPHP\Connect::delete($options);

```
### OPTIONS
```php
//// OSvCPHP\Connect::options(options)
//// returns headers object or a raw Response object on error
// Here's how you can fetch options for incidents
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$options = array(
	"client" => $rn_client,
	"url" => "incidents",
);

$options_response = OSvCPHP\Connect::options($options);

```

## Uploading File Attachments
In order to upload a file attachment, add a "files" property to your options object with an array as it's value. In that array, input the file locations of the files that you wish to upload relative to where the script is ran.

```php
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$options = array(
    "client" => $rn_client,
    "url" => "incidents",
    "json" =>  array(
        "primaryContact"=>  array(
            "id"=>  2
        ),
        "subject"=>  "FishPhone not working"
    ), "files" => array(
        "./test.php",
    )
);

$post_response = OSvCPHP\Connect::post($options);

```

## Downloading File Attachments
In order to download a file attachment, add a "?download" query parameter to the file attachment URL and send a get request using the OSvCPHP\Connect.get method. The file will be downloaded to the same location that the script is ran.

```php
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$options = array(
    "client" => $rn_client,
    "url" => '/incidents/25872/fileAttachments/417?download'
);

$get_response = OSvCPHP\Connect::get($options); // returns 1 on success

```

In order to download multiple attachments for a given object, add a "?download" query parameter to the file attachments URL and send a get request using the OSvCPHP\Connect.get method. 

All of the files for the specified object will be downloaded and archived in a .tgz file.

```php
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$options = array(
    "client" => $rn_client,
    "url" => '/incidents/25872/fileAttachments?download'
);

$get_response = OSvCPHP\Connect::get($options); // returns 1 on success

```

You can extract the file using [tar](https://askubuntu.com/questions/499807/how-to-unzip-tgz-file-using-the-terminal/499809#499809)
    
	$ tar -xvzf ./downloadedAttachment.tgz

## OSvCPHP\QueryResults example

This is for running one ROQL query. Whatever is allowed by the REST API (limits and sorting) is allowed with this library.

OSvCPHP\QueryResults only has one function: 'query', which takes an OSvCPHP\Client object and string query (example below).

```php
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$options = array(
	"query" => "DESCRIBE CONTACTS",
	"client" => $rn_client
);

$q = new OSvCPHP\QueryResults;

$results = $q->query($options);


```
## OSvCPHP\QueryResultsSet example

This is for running multiple queries and assigning the results of each query to a key for further manipulation.

OSvCPHP\QueryResultsSet only has one function: 'query_set', which takes an OSvCPHP\Client object and multiple query hashes (example below).

```php
// Pass in each query into a hash
// set query: to the query you want to execute
// set key: to the value you want the results to of the query to be referenced to
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$queries = array(
	array(
		"query" => "DESCRIBE ANSWERS",
		"key" => "answerSchema"
	),
 	array(
 		"query" => "SELECT * FROM ANSWERS LIMIT 1",
 		"key" => "answers"
 	),
 	array(
 		"query" => "DESCRIBE SERVICECATEGORIES",
 		"key" => "categoriesSchema"
 	),
 	array(
 		"query" => "SELECT * FROM SERVICECATEGORIES",
 		"key" =>"categories"
 	),
 	array(
 		"query" => "DESCRIBE SERVICEPRODUCTS",
 		"key" => "productsSchema"
 	),
 	array(
 		"query" => "SELECT * FROM SERVICEPRODUCTS",
 		"key" =>"products"
 	)
);


$options = array(
    "client" => $rn_client,
    "queries" => $queries
);

$mq = new OSvCPHP\QueryResultsSet;

$results = $mq->query_set($options);


//  Results for "DESCRIBE ANSWERS"
// 
//  [
//   {
//     "Name": "id",
//     "Type": "Integer",
//     "Path": ""
//   },
//   {
//     "Name": "lookupName",
//     "Type": "String",
//     "Path": ""
//   },
//   {
//     "Name": "createdTime",
//     "Type": "String",
//     "Path": ""
//   }
//   ... everything else including customfields and objects...
// ]

//  Results for "SELECT * FROM ANSWERS LIMIT 1"
// 
//  [
//   {
//     "id": 1,
//     "lookupName": 1,
//     "createdTime": "2016-03-04T18:25:50Z",
//     "updatedTime": "2016-09-12T17:12:14Z",
//     "accessLevels": 1,
//     "adminLastAccessTime": "2016-03-04T18:25:50Z",
//     "answerType": 1,
//     "expiresDate": null,
//     "guidedAssistance": null,
//     "keywords": null,
//     "language": 1,
//     "lastAccessTime": "2016-03-04T18:25:50Z",
//     "lastNotificationTime": null,
//     "name": 1,
//     "nextNotificationTime": null,
//     "originalReferenceNumber": null,
//     "positionInList": 1,
//     "publishOnDate": null,
//     "question": null,
//     "solution": "<HTML SOLUTION WITH INLINE CSS>",
//     "summary": "SPRING IS ALMOST HERE!",
//     "updatedByAccount": 16,
//     "uRL": null
//   }
// ]

//  Results for "DESCRIBE SERVICECATEGORIES"
//  
// [
// ... skipping the first few ... 
//  {
//     "Name": "adminVisibleInterfaces",
//     "Type": "SubTable",
//     "Path": "serviceCategories.adminVisibleInterfaces"
//   },
//   {
//     "Name": "descriptions",
//     "Type": "SubTable",
//     "Path": "serviceCategories.descriptions"
//   },
//   {
//     "Name": "displayOrder",
//     "Type": "Integer",
//     "Path": ""
//   },
//   {
//     "Name": "endUserVisibleInterfaces",
//     "Type": "SubTable",
//     "Path": "serviceCategories.endUserVisibleInterfaces"
//   },
//   ... everything else include parents and children ...
// ]


//  Results for "SELECT * FROM SERVICECATEGORIES"
// 
//  [
//   {
//     "id": 3,
//     "lookupName": "Manuals",
//     "createdTime": null,
//     "updatedTime": null,
//     "displayOrder": 3,
//     "name": "Manuals",
//     "parent": 60
//   },
//   {
//     "id": 4,
//     "lookupName": "Installations",
//     "createdTime": null,
//     "updatedTime": null,
//     "displayOrder": 4,
//     "name": "Installations",
//     "parent": 60
//   },
//   {
//     "id": 5,
//     "lookupName": "Downloads",
//     "createdTime": null,
//     "updatedTime": null,
//     "displayOrder": 2,
//     "name": "Downloads",
//     "parent": 60
//   },
//   ... you should get the idea by now ...
// ]

```
## OSvCPHP\AnalyticsReportsResults

You can create a new instance either by the report 'id' or 'lookupName'.

OSvCPHP\AnalyticsReportsResults only has one function: 'run', which takes an OSvCPHP\Client object.

Pass in the 'id', 'lookupName', and 'filters' in the options data object to set the report and any filters. 
```php
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$options = array(
	"client" => $rn_client,
	"json" => array(
		"filters" => array(
			array(
				"name" => "search_ex",
				"values" => array("returns")
			)
    	),
    	"limit" => 2,
    	"id" => 176
	)
);

$arr = new OSvCPHP\AnalyticsReportResults;

$arrResults = $arr->run($options);

```

## Bulk Delete
This library makes it easy to use the Bulk Delete feature within the latest versions of the REST API. 

You can either use a QueryResults or QueryResultsSet object in order to run bulk delete queries.

Before you can use this feature, make sure that you have the [correct permissions set up for your profile](https://docs.oracle.com/en/cloud/saas/service/18b/cxsvc/c_osvc_bulk_delete.html#BulkDelete-10689704__concept-212-37785F91).

Here is an example of the how to use the Bulk Delete feature: 
```php
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"version" => "latest"
));

$options = array(
	"client" => $rn_client,
	"query" => "DELETE FROM INCIDENTS LIMIT 10",
	"annotation" => "Delete example"
);

$q = new OSvCPHP\QueryResults;

$results = $q->query($options);
```

## Running multiple ROQL Queries concurrently
Instead of running multiple queries with 1 GET request, you can run multiple GET requests and combine the results by adding a "concurrent" property to the options object

```php
require __DIR__ . '/vendor/autoload.php';

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
));

$queries = array(
    array(
        "query" => "DESCRIBE INCIDENTS",
        "key" => "incidents"
    ),
    array(
        "query" => "DESCRIBE SERVICEPRODUCTS",
        "key" => "serviceProducts"
    ),
);

$options = array(
    "client" => $rn_client,
    "queries" => $queries,
    "concurrent" => true
);

$mq = new OSvCPHP\QueryResultsSet;

$results = $mq->query_set($options);
```


## Performing Session Authentication

1. Create a custom script with the following code and place in the "Custom Scripts" folder in the File Manager:

```php
<?php

// Find our position in the file tree
if (!defined('DOCROOT')) {
$docroot = get_cfg_var('doc_root');
define('DOCROOT', $docroot);
}
 
/************* Agent Authentication ***************/
 
// Set up and call the AgentAuthenticator
require_once (DOCROOT . '/include/services/AgentAuthenticator.phph');

// get username and password
$username = $_GET['username'];
$password = $_GET['password'];
 
// On failure, this includes the Access Denied page and then exits,
// preventing the rest of the page from running.
echo json_encode(AgentAuthenticator::authenticateCredentials($username,$password));

```
2. Create a php script similar to the following and it should connect:

```php
require __DIR__ . '/vendor/autoload.php';

// initialize a curl request
$ch = curl_init(); 
// set the base of the url
$url = "https://". getenv('OSC_SITE') .".custhelp.com/cgi-bin/"
// add the location of the above file
$url .= getenv('OSC_CONFIG') .".cfg/php/custom/login_test.php"
// add the credentials for getting a session ID
$url .= "?username=". getenv('OSC_ADMIN') ."&password=". getenv('OSC_PASSWORD');
// set the URL
curl_setopt($ch, CURLOPT_URL, "$url"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
// execute
$output = curl_exec($ch); 
// close curl
curl_close($ch);      

$session_id = json_decode($output)->session_id;

$rn_client = new OSvCPHP\Client(array(
    "session" => $session_id,
	"interface" => getenv("OSC_SITE"),
));

$options = array(
    "client" => $rn_client,
    "query" => "SELECT * FROM INCIDENTS LIMIT 10"
);

$mq = new OSvCPHP\QueryResults();

$results = $mq->query($options);

echo json_encode($results, JSON_PRETTY_PRINT);
```

## License
[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Frajangdavis%2Fosvc_php.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Frajangdavis%2Fosvc_php?ref=badge_large)