# OSvCPHP

An (under development) PHP library for using the [Oracle Service Cloud REST API](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/) influenced by the [ConnectPHP API](http://documentation.custhelp.com/euf/assets/devdocs/november2016/Connect_PHP/Default.htm)

## Installing PHP (for Windows)
[Here is a Youtube video with instructions for Windows 10](https://www.youtube.com/watch?v=D-wFWUMHcUA). I would highly recommend installing PHP 7.  

If you get SSL Errors (you probably will), follow [this link for instructions on resolving SSL things that I know nothing about](https://stackoverflow.com/a/18972719/2548452).
   
## Installation

Install with composer:

    $ composer require rajangdavis/osvc_php --dev
   
## Compatibility

This PHP library tested against Oracle Service Cloud May 2017 using PHP 7.2.1. I _might_ set up Travis CI and Code Climate, it just depends if I find it worth my time.

All of the HTTP methods should work on any version of Oracle Service Cloud since version May 2015; however, there maybe some issues with querying items on any version before May 2016. This is because ROQL queries were not exposed via the REST API until May 2016.

## Use Cases
You can use this PHP Library for basic scripting and microservices. The main features that work to date are as follows:

1. [Simple configuration](#client-configuration)
2. Running ROQL queries [1 at a time](#osvcphpqueryresults-example)
or [multiple queries in a set](#osvcphpqueryresultsset-example) 
3. [Running Reports](#osvcphpanalyticsreportsresults)
4. Basic CRUD Operations via HTTP Methods
	1. [Create => Post](#create)
	2. [Read => Get](#read)
	3. [Update => Patch](#update)
	4. [Destroy => Delete](#delete)

<!--
## Installation

 Add this line to your application's Gemfile:

```php
gem 'osc_ruby'
```

And then execute:

    $ bundle

Or install it yourself as:

    $ gem install osc_ruby -->


## Client Configuration

An OSvCPHP\Client object lets the library know which credentials and interface to use for interacting with the Oracle Service Cloud REST API.
This is helpful if you need to interact with multiple interfaces or set different headers for different objects.

```php

// Configuration is as simple as requiring the library
// and passing in an associative array

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),			# => These are interface credentials
	"password" => getenv("OSC_PASSWORD"),			# => store these in environmental
	"interface" => getenv("OSC_SITE"),			# => variables in your .bash_profile

	### optional configuration
	# Turns off SSL verification; don't use in production
	"no_ssl_verify" => true,				# => Defaults to false. 
	
	# Sets the version of the REST API to use
	"version" => 'v1.4',					# => Defaults to 'v1.3'. 
	
	# Let's you supress business rules
	"suppress_rules" => true,				# => Defaults to false. 
	
	# Use 'rightnowdemo' namespace instead of 'custhelp'
	"demo_site" => true					# => Defaults to false. 
));
```


## OSvCPHP\QueryResults example

This is for running one ROQL query. Whatever is allowed by the REST API (limits and sorting) is allowed with this library.

OSvCPHP\QueryResults only has one function: 'query', which takes an OSvCPHP\Client string and string query (example below).

```php
# NOTE: Make sure to put your queries WRAPPED in doublequotes("")
# this is because when Ruby converts the queries into a URI
# the REST API does not like it when the queries are WRAPPED in single quotes ('')

# For example
# "parent is null and lookupName!='Unsure'" => great!
# 'parent is null and lookupName!="Unsure"' => don't do this
# it will spit back an error from the REST API!

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),
    "password" => getenv("OSC_PASSWORD"),
    "interface" => getenv("OSC_SITE")
));

$query = "select * from answers where ID = 1557";

$q = new OSvCPHP\QueryResults;

$q->query($rn_client,$query,true); # => "[{'id':1557,'name':...}]"

```










## OSvCPHP\QueryResultsSet example

This is for running multiple queries and assigning the results of each query to a key for further manipulation.

OSvCPHP\QueryResultsSet only has one function: 'query_set', which takes an OSvCPHP\Client object and multiple query associative arrays (example below).

```php
# NOTE: Make sure to put your queries WRAPPED in doublequotes("")
# Pass in each query into a hash
    # set query: to the query you want to execute
    # set key: to the value you want the results to of the query to be referenced to

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),
    "password" => getenv("OSC_PASSWORD"),
    "interface" => getenv("OSC_SITE"),
    "demo_site" => true
));

$query_arr = array(
    array(
        "key" => "incidents",
        "query" => "SELECT * FROM incidents LIMIT 3"
    ),
    array(
        "key" => "answers",
        "query" => "SELECT * FROM answers LIMIT 3"
    )
);

$mq = new OSvCPHP\QueryResultsSet;

$results_object = $mq->query_set($rn_client,$query_arr);

echo json_encode($results_object->incidents,JSON_PRETTY_PRINT);

#[
#    {
#        "id": "21",
#        "lookupName": "160311-000001",
#        "createdTime": "2016-02-23T19:00:00Z",
#        "updatedTime": "2016-06-14T20:31:40Z",
#        "asset": null,
#        "category": "3",
#        "channel": "3",
#        "chatQueue": null,
#        "closedTime": null,
#        "createdByAccount": "6",
#        "disposition": "13",
#        "initialResponseDueTime": "2016-02-23T19:00:00Z",
#        "initialSolutionTime": "2016-02-23T19:00:00Z",
#        "interface": "1",
#        "language": "1",
#        "lastResponseTime": "2016-02-23T19:00:00Z",
#        "lastSurveyScore": null,
#        "mailbox": null,
#        "mailing": null,
#        "organization": null,
#        "product": "8",
#        "queue": null,
#        "referenceNumber": "160311-000001",
#        "resolutionInterval": "-1440",
#        "responseEmailAddressType": "0",
#        "responseInterval": null,
#        "severity": null,
#        "smartSenseCustomer": null,
#        "smartSenseStaff": null,
#        "source": "8001",
#        "subject": "How long until I receive my refund on my credit card?"
#    },
#    {
#        "id": "22",
#        "lookupName": "160311-000002",
#        "createdTime": "2016-02-23T19:00:00Z",
#        "updatedTime": "2016-06-14T20:31:40Z",
#        "asset": null,
#        "category": "3",
#        "channel": "3",
#        "chatQueue": null,
#        "closedTime": null,
#        "createdByAccount": "6",
#        "disposition": "13",
#        "initialResponseDueTime": "2016-02-23T19:00:00Z",
#        "initialSolutionTime": "2016-02-23T19:00:00Z",
#        "interface": "1",
#        "language": "1",
#        "lastResponseTime": "2016-02-23T19:00:00Z",
#        "lastSurveyScore": null,
#        "mailbox": null,
#        "mailing": null,
#        "organization": null,
#        "product": "8",
#        "queue": null,
#        "referenceNumber": "160311-000002",
#        "resolutionInterval": "-1440",
#        "responseEmailAddressType": "0",
#        "responseInterval": null,
#        "severity": null,
#        "smartSenseCustomer": null,
#        "smartSenseStaff": null,
#        "source": "8001",
#        "subject": "Do you ship outside the US?"
#    },
#    {
#        "id": "23",
#        "lookupName": "160311-000003",
#        "createdTime": "2016-02-23T19:00:00Z",
#        "updatedTime": "2016-06-14T20:31:40Z",
#        "asset": null,
#        "category": "3",
#        "channel": "3",
#        "chatQueue": null,
#        "closedTime": null,
#        "createdByAccount": "6",
#        "disposition": "13",
#        "initialResponseDueTime": "2016-02-23T19:00:00Z",
#        "initialSolutionTime": "2016-02-23T19:00:00Z",
#        "interface": "1",
#        "language": "1",
#        "lastResponseTime": "2016-02-23T19:00:00Z",
#        "lastSurveyScore": null,
#        "mailbox": null,
#        "mailing": null,
#        "organization": null,
#        "product": "8",
#        "queue": null,
#        "referenceNumber": "160311-000003",
#        "resolutionInterval": "-1440",
#        "responseEmailAddressType": "0",
#        "responseInterval": null,
#        "severity": null,
#        "smartSenseCustomer": null,
#        "smartSenseStaff": null,
#        "source": "8001",
#        "subject": "How can I order another product manual?"
#    }
#]

echo json_encode($results_object->answers,JSON_PRETTY_PRINT);

#[
#    {
#        "id": "1",
#        "lookupName": "1",
#        "createdTime": "2016-03-04T18:25:50Z",
#        "updatedTime": "2016-09-12T17:12:14Z",
#        "accessLevels": "0000000001",
#        "adminLastAccessTime": "2016-03-04T18:25:50Z",
#        "answerType": "1",
#        "expiresDate": null,
#        "guidedAssistance": null,
#        "keywords": null,
#        "language": "1",
#        "lastAccessTime": "2016-03-04T18:25:50Z",
#        "lastNotificationTime": null,
#        "name": "1",
#        "nextNotificationTime": null,
#        "originalReferenceNumber": null,
#        "positionInList": "1",
#        "publishOnDate": null,
#        "question": null,
#        "solution": "<span style=\"WHITE-SPACE: normal; WORD-SPACING: 0px; TEXT-TRANSFORM: none; FLOAT: none; COLOR: rgb(119,119,119); FONT: 14px\/20px RobotoDraft, 'Helvetica Neue', Calibri, Helvetica, Arial, sans-serif; WIDOWS: 1; DISPLAY: inline !important; LETTER-SPACING: normal; BACKGROUND-COLOR: rgb(255,255,255); TEXT-INDENT: 0px; -webkit-text-stroke-width: 0px\">Check out our new&#160;2017&#160;products.<\/span><br style=\"WHITE-SPACE: normal; WORD-SPACING: 0px; TEXT-TRANSFORM: none; COLOR: rgb(119,119,119); PADDING-BOTTOM: 0px; PADDING-TOP: 0px; FONT: 14px\/20px RobotoDraft, 'Helvetica Neue', Calibri, Helvetica, Arial, sans-serif; PADDING-LEFT: 0px; WIDOWS: 1; MARGIN: 0px; LETTER-SPACING: normal; PADDING-RIGHT: 0px; BACKGROUND-COLOR: rgb(255,255,255); TEXT-INDENT: 0px; -webkit-text-stroke-width: 0px\" \/>\n<br style=\"WHITE-SPACE: normal; WORD-SPACING: 0px; TEXT-TRANSFORM: none; COLOR: rgb(119,119,119); PADDING-BOTTOM: 0px; PADDING-TOP: 0px; FONT: 14px\/20px RobotoDraft, 'Helvetica Neue', Calibri, Helvetica, Arial, sans-serif; PADDING-LEFT: 0px; WIDOWS: 1; MARGIN: 0px; LETTER-SPACING: normal; PADDING-RIGHT: 0px; BACKGROUND-COLOR: rgb(255,255,255); TEXT-INDENT: 0px; -webkit-text-stroke-width: 0px\" \/>\n<a style=\"TEXT-DECORATION: none; WHITE-SPACE: normal; WORD-SPACING: 0px; TEXT-TRANSFORM: none; COLOR: rgb(231,76,60); PADDING-BOTTOM: 0px; PADDING-TOP: 0px; FONT: 14px\/20px RobotoDraft, 'Helvetica Neue', Calibri, Helvetica, Arial, sans-serif; PADDING-LEFT: 0px; WIDOWS: 1; MARGIN: 0px; LETTER-SPACING: normal; PADDING-RIGHT: 0px; BACKGROUND-COLOR: rgb(255,255,255); TEXT-INDENT: 0px; -webkit-text-stroke-width: 0px\" href=\"http:\/\/oow2016.rightnowdemo.com\/euf\/assets\/html\/smartly\/product-template.html\">Go to the Smartly Store<\/a>\n",
#        "summary": "SPRING IS ALMOST HERE!",
#        "updatedByAccount": "16",
#        "uRL": null
#    },
#    {
#        "id": "2",
#        "lookupName": "2",
#        "createdTime": "2016-03-08T19:07:01Z",
#        "updatedTime": "2016-05-13T14:00:35Z",
#        "accessLevels": "0000000001",
#        "adminLastAccessTime": "2016-03-08T19:07:01Z",
#        "answerType": "3",
#        "expiresDate": null,
#        "guidedAssistance": null,
#        "keywords": null,
#        "language": "1",
#        "lastAccessTime": "2017-11-29T21:04:24Z",
#        "lastNotificationTime": null,
#        "name": "2",
#        "nextNotificationTime": null,
#        "originalReferenceNumber": null,
#        "positionInList": "1",
#        "publishOnDate": null,
#        "question": null,
#        "solution": null,
#        "summary": "Maestro Smart Thermostat Installation Guide",
#        "updatedByAccount": "4",
#        "uRL": "M2509LW Installation Guide.pdf"
#    },
#    {
#        "id": "3",
#        "lookupName": "3",
#        "createdTime": "2016-03-08T19:43:33Z",
#        "updatedTime": "2016-05-17T16:39:17Z",
#        "accessLevels": "0000000001",
#        "adminLastAccessTime": "2016-03-08T19:43:33Z",
#        "answerType": "1",
#        "expiresDate": null,
#        "guidedAssistance": null,
#        "keywords": null,
#        "language": "1",
#        "lastAccessTime": "2016-10-03T14:17:40Z",
#        "lastNotificationTime": null,
#        "name": "3",
#        "nextNotificationTime": null,
#        "originalReferenceNumber": null,
#        "positionInList": "1",
#        "publishOnDate": null,
#        "question": "<p>Maestro Smart Thermostat App<\/p>\n\n",
#        "solution": "<p>You will find the Maestro Smart Thermostat App in the Apple App Store or the Google Play store.<\/p>\n<p>Click on the icon to download the app.<\/p>\n<p>&#160;<img border=\"0\" alt=\"Image\" src=\"http:\/\/smartly.rightnowdemo.com\/euf\/assets\/images\/smartlyapp.png\" width=\"49\" height=\"47\" \/><\/p>\n\n",
#        "summary": "Maestro Smart Thermostat App",
#        "updatedByAccount": "4",
#        "uRL": null
#    }
#]

```


## OSvCPHP\AnalyticsReportsResults

You can create a new instance either by the report 'id' or 'lookupName'.

OSvCPHP\AnalyticsReportsResults only has one function: 'run', which takes an OSvCPHP\Client object.

OSvCPHP\AnalyticsReportsResults have the following properties: 'id', 'lookupName', and 'filters'. More on filters and supported datetime methods are below this OSvCPHP\AnalyticsReportsResults example script.

```php
require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
	"username" => getenv("OSC_ADMIN"),
	"password" => getenv("OSC_PASSWORD"),
	"interface" => getenv("OSC_SITE"),
	"demo_site" => true
));

$last_updated = new OSvCPHP\AnalyticsReportResults(
	array("lookupName" => "Last Updated By Status")
);

$results = $last_updated->run($rn_client);
echo json_encode($results,JSON_PRETTY_PRINT);

#[
#    {
#        "Status": "Unresolved",
#        "Incidents": "793",
#        "Average Time Since Last Response": "57417609.617582"
#    },
#    {
#        "Status": "Updated",
#        "Incidents": "462",
#        "Average Time Since Last Response": "57542462.911111"
#    }
#]

```


<!-- 
## Convenience Methods

### 'arrf' => analytics report results filter

'arrf' lets you set filters for an OSvCPHP\AnalyticsReportsResults Object.

You can set the following keys:
1. name => The filter name
2. prompt => The prompt for this filter

These are under development, but these should work if you treat them like the the data-type they are as mentioned in the REST API:

3. [attributes](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-definitions-namedIDs-analyticsReports-filters-attributes)
4. [dataType](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-definitions-namedIDs-analyticsReports-filters-dataType)
5. [operator](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-definitions-namedIDs-analyticsReports-filters-operator)
6. [values](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-namedIDs-definitions-analyticsReports-filters-values)

```php
require 'osc_ruby'

rn_client = OSCRuby::Client.new do |c|
	c.username = ENV['OSC_ADMIN']
	c.password = ENV['OSC_PASSWORD']
	c.interface = ENV['OSC_SITE']	
end

answers_search = OSCRuby::AnalyticsReportResults.new(id: 176)

keywords = arrf(name: "search_ex", values: "Maestro")
answers_search.filters << keywords

# To add more filters, create another 
# "arrf" filter structure
# and "shovel" it into 
# the OSCRuby::AnalyticsReportResults
# "filters" property
#
# date_created = arrf(name: "date_created", values: dti("August 7th, 2017"))
# answers_search.filters << date_created


answers = answers_search.run(rn_client)		

answers.each do |answer|
	puts answer['Summary']
end			

# =>

# How do I get started with the Maestro Smart Thermostat App?

# Is my Wi-Fi router compatible with the Maestro Smart Thermostat?

# Will the Maestro Smart Thermostat work with my HVAC system?

# Maestro Smart Thermostat App

# Maestro Smart Thermostat Installation Guide

# Maestro Product Warranty

# ... and so on and so forth
```







### 'dti' => date to iso8601

dti lets you type in a date and get it in ISO8601 format. Explicit date formatting is best.

```php

dti("January 1st, 2014") # => 2014-01-01T00:00:00-08:00  # => 1200 AM, January First of 2014

dti("January 1st, 2014 11:59PM MDT") # => 2014-01-01T23:59:00-06:00 # => 11:59 PM Mountain Time, January First of 2014

dti("January 1st, 2014 23:59 PDT") # => 2014-01-01T23:59:00-07:00 # => 11:59 PM Pacific Time, January First of 2014

dti("January 1st") # => 2017-01-01T00:00:00-08:00 # => 12:00 AM, January First of this Year

```


Be careful! Sometimes the dates will not be what you expect; try to write dates as explicitly/predictably when possible.


```php

# EXAMPLES OF DATES NOT BEING WHAT YOU MIGHT EXPECT

#Full dates should be formatted as 
# %d/%m/%y %h:%m tt

dti("01/02/14") # => 2001-02-14T00:00:00-08:00 # => 12:00 AM, February 14th, 2001

dti("01/02/2014") # => 2014-02-01T00:00:00-08:00 # => 12:00 AM, February 14th, 2014

dti("11:59PM January 1st, 2014 GMT") #=> 2017-08-01T23:59:00-07:00 #=> 11:59 PM, August 1st, 2017 Pacific Time (?)

```
-->








## Basic CRUD operations

### CREATE
```php
#### OSvCPHP\Connect::post( <client>, <url>, <json_data> )
#### returns an associative array

require_once('./osvc_php.php');

# Here's how you could create a new ServiceProduct object
# using PHP variables and arrays to set field information

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),	
    "password" => getenv("OSC_PASSWORD"),
    "interface" => getenv("OSC_SITE")	
));

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

$post_response = OSvCPHP\Connect::post($rn_client,'/serviceProducts',$new_product);

echo json_encode($post_response['body'],JSON_PRETTY_PRINT); # => JSON body
echo json_encode($post_response['info'],JSON_PRETTY_PRINT); # => cURL info

```


### READ
```php
#### OSvCPHP\Connect::get( <client>, optional (<url>/<id>/...<params>) )
#### returns an associative array
# Here's how you could get a list of ServiceProducts

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),	
    "password" => getenv("OSC_PASSWORD"),
    "interface" => getenv("OSC_SITE")	
));

$get_response = OSvCPHP\Connect::get($rn_client,'/serviceProducts?limit=3');
echo json_encode($get_response['body'],JSON_PRETTY_PRINT);

#{
#    "items": [
#        {
#            "id": 2,
#            "lookupName": "Maestro Smart Thermostat",
#            "links": [
#                {
#                    "rel": "canonical",
#                    "href": "https:\/\/<OSC_SITE>.rightnowdemo.com\/services\/rest\/connect\/v1.3\/serviceProducts\/2"
#                }
#            ]
#        },
#        {
#            "id": 6,
#            "lookupName": "Home Security",
#            "links": [
#                {
#                    "rel": "canonical",
#                    "href": "https:\/\/<OSC_SITE>.rightnowdemo.com\/services\/rest\/connect\/v1.3\/serviceProducts\/6"
#                }
#            ]
#        },
#        {
#            "id": 7,
#            "lookupName": "Hubs",
#            "links": [
#                {
#                    "rel": "canonical",
#                    "href": "https:\/\/<OSC_SITE>.rightnowdemo.com\/services\/rest\/connect\/v1.3\/serviceProducts\/7"
#                }
#            ]
#        }
#    ],
#    "hasMore": true,
#
#	 ... and everything else ... 
#	
#}


```






### UPDATE
```php
#### OSvCPHP\Connect::patch( <client>, <url>, <json_data> )
#### returns an associative array
# Here's how you could update the previously created ServiceProduct object
# using PHP variables and arrays
# to set field information

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),	
    "password" => getenv("OSC_PASSWORD"),
    "interface" => getenv("OSC_SITE")	
));

$prod_info_to_change = array(
    "names" => array(
        "labelText" => "PRODUCT-TEST-updated",
            "language" => array(
            "id" => 1
        )
    ),
    "displayOrder" =>  4,
    "adminVisibleInterfaces" => array(
    	array(
    		"id" => 1
    	)
    ),
    "endUserVisibleInterfaces" => array(
    	array(
    		"id" => 1
    	)
    ),
);

$updated_product = OSvCPHP\Connect::patch($rn_client,"serviceProducts/56",$prod_info_to_change); 

echo json_encode($updated_product['info'],JSON_PRETTY_PRINT); # => cURL info
echo json_encode($updated_product['body'],JSON_PRETTY_PRINT); # => null if successful

```


### DELETE
```php
#### OSvCPHP\Connect::delete( <client>, <url> )
#### returns an associative array
# Here's how you could delete the previously updated ServiceProduct object
# and OSvCPHP\Connect classes

require_once('./osvc_php.php');

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),	
    "password" => getenv("OSC_PASSWORD"),
    "interface" => getenv("OSC_SITE")
));

$delete_response = OSvCPHP\Connect::delete($rn_client,'/serviceProducts/233');

echo json_encode($updated_product['info'],JSON_PRETTY_PRINT); # => cURL info
echo json_encode($updated_product['body'],JSON_PRETTY_PRINT); # => null if successful

```