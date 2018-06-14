<?php
use PHPUnit\Framework\TestCase;
// Config.optionsFinalize
//     √ should take an HTTP verb and set the method property to match that verb
//     √ should take a client object and return a hash of options settings; the "suppress_rules" setting should return headers["OSvC-CREST-Suppress-All"] as true
//     √ should take a boolean param for making PATCH requests; headers["X-HTTP-Method-Override"] as "PATCH"
//     √ should have headers set to undefined as a default
//     √ should always use "https" and "/services/rest/connect" in the url
//     √ should take a client object and change the url to include "rightnowdemo" if the "demo_site" setting is set to true
//     √ should take a username,password,and interface and change the url to include the interface
//     √ should take a username,password and return an authorization header for basic auth
//     √ should take a client object and change the url to include a different verions if the "version" setting is changed
//     √ should take a resource URL and change the url
//     √ should equal "https://interface789.rightnowdemo.com/services/rest/connect/v1.4/incidents"
//     √ should take a not match incidents if the resource URL is not specified
//     √ "version" should be "v1.3/" if not specified
//     √ "custhelp" domain will be used if not specified
//     √ should equal "https://interface789.custhelp.com/services/rest/connect/v1.3/"
//     √ should be able to set a session id and it should work if retrieved
//     √ should be able to set an oauth token for authentication
//     √ should be able to set an access token for authentication
//     √ should be able to set optional headers
//     √ should throw an error if version is set to "v1.4" and no annotation is present
//     √ should throw an error if annotation is present but blank
//     √ should throw an error if annotation is greater than 40 characters