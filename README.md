# OSvCPHP

An (under development) PHP library for using the [Oracle Service Cloud REST API](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/) influenced by the [ConnectPHP API](http://documentation.custhelp.com/euf/assets/devdocs/november2016/Connect_PHP/Default.htm)

## Installing PHP (for Windows)
[Here is a Youtube video with instructions for Windows 10](https://www.youtube.com/watch?v=D-wFWUMHcUA). I would highly recommend installing PHP 7.

If you get SSL Errors (you probably will), follow [this link for instructions on resolving SSL things that I know nothing about](https://stackoverflow.com/a/18972719/2548452).
   
## Compatibility

This PHP library tested against Oracle Service Cloud May 2017 using PHP 7.2.1; Travis CI and Code Climate to be set up soon.

All of the HTTP methods should work on any version of Oracle Service Cloud since version May 2015; however, there maybe some issues with querying items on any version before May 2016. This is because ROQL queries were not exposed via the REST API until May 2016.


## Use Cases
You can use this PHP Library for basic scripting and microservices. The main features that work to date are as follows:

1. [Simple configuration](#client-configuration)
<!-- 2. Running ROQL queries [either 1 at a time](#oscrubyqueryresults-example) or [multiple queries in a set](#oscrubyqueryresultsset-example) 
3. [Running Reports with filters](#oscrubyanalyticsreportsresults)
4. Convenience methods for Analytics filters and setting dates
	1. ['arrf', an analytics report results filter](#arrf--analytics-report-results-filter)
	2. ['dti', converts a date string to ISO8601 format](#dti--date-to-iso8601) -->
2. Basic CRUD Operations via HTTP Methods
	1. [Create => Post](#create)
<!-- 	2. [Read => Get](#read)
	3. [Update => Patch](#update)
	4. [Destroy => Delete](#delete) -->

<!--
## Installation

 Add this line to your application's Gemfile:

```ruby
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
<!-- 




## OSCRuby::QueryResults example

This is for running one ROQL query. Whatever is allowed by the REST API (limits and sorting) is allowed with this library.

OSCRuby::QueryResults only has one function: 'query', which takes an OSCRuby::Client object and string query (example below).

```ruby
# NOTE: Make sure to put your queries WRAPPED in doublequotes("")
# this is because when Ruby converts the queries into a URI
# the REST API does not like it when the queries are WRAPPED in single quotes ('')

# For example
# "parent is null and lookupName!='Unsure'" => great!
# 'parent is null and lookupName!="Unsure"' => don't do this
# it will spit back an error from the REST API!

require 'osc_ruby'

rn_client = OSCRuby::Client.new do |c|
	c.username = ENV['OSC_ADMIN']
	c.password = ENV['OSC_PASSWORD']
	c.interface = ENV['OSC_SITE']	
end

q = OSCRuby::QueryResults.new

query = "select * from answers where ID = 1557"

results = q.query(rn_client,query) # => will return an array of results

puts results[0] # => "{'id':1557,'name':...}"

```











## OSCRuby::QueryResultsSet example

This is for running multiple queries and assigning the results of each query to a key for further manipulation.

OSCRuby::QueryResultsSet only has one function: 'query_set', which takes an OSCRuby::Client object and multiple query hashes (example below).

```ruby
# NOTE: Make sure to put your queries WRAPPED in doublequotes("")
# Pass in each query into a hash
	# set query: to the query you want to execute
	# set key: to the value you want the results to of the query to be referenced to

require 'osc_ruby'

rn_client = OSCRuby::Client.new do |c|
	c.username = ENV['OSC_ADMIN']
	c.password = ENV['OSC_PASSWORD']
	c.interface = ENV['OSC_SITE']	
end

mq = OSCRuby::QueryResultsSet.new
r = mq.query_set(rn_client,
		 	{query:"DESCRIBE ANSWERS", key: "answerSchema"},
		 	{query:"SELECT * FROM ANSWERS LIMIT 1", key: "answers"},
		 	{query:"DESCRIBE SERVICECATEGORIES", key: "categoriesSchema"},
		 	{query:"SELECT * FROM SERVICECATEGORIES", key:"categories"},
		 	{query:"DESCRIBE SERVICEPRODUCTS", key: "productsSchema"},
		 	{query:"SELECT * FROM SERVICEPRODUCTS", key:"products"})

puts JSON.pretty_generate(r.answerSchema)

# Results for "DESCRIBE ANSWERS"
#
# [
#  {
#    "Name": "id",
#    "Type": "Integer",
#    "Path": ""
#  },
#  {
#    "Name": "lookupName",
#    "Type": "String",
#    "Path": ""
#  },
#  {
#    "Name": "createdTime",
#    "Type": "String",
#    "Path": ""
#  }
#  ... everything else including customfields and objects...
#]

puts JSON.pretty_generate(r.answers)

# Results for "SELECT * FROM ANSWERS LIMIT 1"
#
# [
#  {
#    "id": 1,
#    "lookupName": 1,
#    "createdTime": "2016-03-04T18:25:50Z",
#    "updatedTime": "2016-09-12T17:12:14Z",
#    "accessLevels": 1,
#    "adminLastAccessTime": "2016-03-04T18:25:50Z",
#    "answerType": 1,
#    "expiresDate": null,
#    "guidedAssistance": null,
#    "keywords": null,
#    "language": 1,
#    "lastAccessTime": "2016-03-04T18:25:50Z",
#    "lastNotificationTime": null,
#    "name": 1,
#    "nextNotificationTime": null,
#    "originalReferenceNumber": null,
#    "positionInList": 1,
#    "publishOnDate": null,
#    "question": null,
#    "solution": "<HTML SOLUTION WITH INLINE CSS>",
#    "summary": "SPRING IS ALMOST HERE!",
#    "updatedByAccount": 16,
#    "uRL": null
#  }
#]

puts JSON.pretty_generate(r.categoriesSchema)

# Results for "DESCRIBE SERVICECATEGORIES"
# 
#[
#... skipping the first few ... 
# {
#    "Name": "adminVisibleInterfaces",
#    "Type": "SubTable",
#    "Path": "serviceCategories.adminVisibleInterfaces"
#  },
#  {
#    "Name": "descriptions",
#    "Type": "SubTable",
#    "Path": "serviceCategories.descriptions"
#  },
#  {
#    "Name": "displayOrder",
#    "Type": "Integer",
#    "Path": ""
#  },
#  {
#    "Name": "endUserVisibleInterfaces",
#    "Type": "SubTable",
#    "Path": "serviceCategories.endUserVisibleInterfaces"
#  },
#  ... everything else include parents and children ...
#]

puts JSON.pretty_generate(r.categories)

# Results for "SELECT * FROM SERVICECATEGORIES"
#
# [
#  {
#    "id": 3,
#    "lookupName": "Manuals",
#    "createdTime": null,
#    "updatedTime": null,
#    "displayOrder": 3,
#    "name": "Manuals",
#    "parent": 60
#  },
#  {
#    "id": 4,
#    "lookupName": "Installations",
#    "createdTime": null,
#    "updatedTime": null,
#    "displayOrder": 4,
#    "name": "Installations",
#    "parent": 60
#  },
#  {
#    "id": 5,
#    "lookupName": "Downloads",
#    "createdTime": null,
#    "updatedTime": null,
#    "displayOrder": 2,
#    "name": "Downloads",
#    "parent": 60
#  },
#  ... you should get the idea by now ...
#]

### Both of these are similar to the above
puts JSON.pretty_generate(r.productsSchema) # => Results for "DESCRIBE SERVICEPRODUCTS"
puts JSON.pretty_generate(r.products) # => Results for "SELECT * FROM SERVICEPRODUCTS"

```







## OSCRuby::AnalyticsReportsResults

You can create a new instance either by the report 'id' or 'lookupName'.

OSCRuby::AnalyticsReportsResults only has one function: 'run', which takes an OSCRuby::Client object.

OSCRuby::AnalyticsReportsResults have the following properties: 'id', 'lookupName', and 'filters'. More on filters and supported datetime methods are below this OSCRuby::AnalyticsReportsResults example script.

```ruby
require 'osc_ruby'

rn_client = OSCRuby::Client.new do |c|
	c.username = ENV['OSC_ADMIN']
	c.password = ENV['OSC_PASSWORD']
	c.interface = ENV['OSC_SITE']	
end

last_updated = OSCRuby::AnalyticsReportResults.new(lookupName: "Last Updated By Status")

puts last_updated.run(rn_client)

#{"Status"=>"Unresolved", "Incidents"=>704, "Average Time Since Last Response"=>"39029690.149123"}
#{"Status"=>"Updated", "Incidents"=>461, "Average Time Since Last Response"=>"39267070.331683"}

```










## Convenience Methods

### 'arrf' => analytics report results filter

'arrf' lets you set filters for an OSCRuby::AnalyticsReportsResults Object.

You can set the following keys:
1. name => The filter name
2. prompt => The prompt for this filter

These are under development, but these should work if you treat them like the the data-type they are as mentioned in the REST API:

3. [attributes](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-definitions-namedIDs-analyticsReports-filters-attributes)
4. [dataType](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-definitions-namedIDs-analyticsReports-filters-dataType)
5. [operator](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-definitions-namedIDs-analyticsReports-filters-operator)
6. [values](https://docs.oracle.com/cloud/latest/servicecs_gs/CXSVC/op-services-rest-connect-v1.4-analyticsReportResults-post.html#request-namedIDs-definitions-analyticsReports-filters-values)

```ruby
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

```ruby

dti("January 1st, 2014") # => 2014-01-01T00:00:00-08:00  # => 1200 AM, January First of 2014

dti("January 1st, 2014 11:59PM MDT") # => 2014-01-01T23:59:00-06:00 # => 11:59 PM Mountain Time, January First of 2014

dti("January 1st, 2014 23:59 PDT") # => 2014-01-01T23:59:00-07:00 # => 11:59 PM Pacific Time, January First of 2014

dti("January 1st") # => 2017-01-01T00:00:00-08:00 # => 12:00 AM, January First of this Year

```


Be careful! Sometimes the dates will not be what you expect; try to write dates as explicitly/predictably when possible.


```ruby

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

# Here's how you could create a new ServiceProduct object
# using PHP variables and arrays to set field information

$rn_client = new OSvCPHP\Client(array(
    "username" => getenv("OSC_ADMIN"),		# => These are interface credentials
    "password" => getenv("OSC_PASSWORD"),	# => store these in environmental
    "interface" => getenv("OSC_SITE")		# => variables in your .bash_profile
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
    "username" => getenv("OSC_ADMIN"),		# => These are interface credentials
    "password" => getenv("OSC_PASSWORD"),	# => store these in environmental
    "interface" => getenv("OSC_SITE"),		# => variables in your .bash_profile
    "demo_site" => true
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




<!--

### DELETE
```php
#### OSCRuby::Connect.delete( <client>, <url> )
#### returns a NetHTTPRequest object
# Here's how you could delete the previously updated ServiceProduct object
# using the OSCRuby::QueryResults 
# and OSCRuby::Connect classes

require 'osc_ruby'

rn_client = OSCRuby::Client.new do |c|
	c.username = ENV['OSC_ADMIN']
	c.password = ENV['OSC_PASSWORD']
	c.interface = ENV['OSC_SITE']	
end

q = OSCRuby::QueryResults.new
query = "select id from serviceproducts where lookupname = 'PRODUCT-TEST-updated';"

product_test_updated = q.query(rn_client,resource) # => returns array of results

test = OSCRuby::Connect.delete(rn_client,"serviceProducts/#{product_test_updated[0]['id']}")

puts updated_product.code # => "200"

puts updated_product.body # => "" if successful...


``` -->