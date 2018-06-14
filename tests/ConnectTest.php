<?php
use PHPUnit\Framework\TestCase;
// connect.get
//     √ should take a url as a param and make a HTTP GET Request with a response code of 200 and a body of JSON

//   connect.get download functionality
//     √ should download a file if there is a "?download" query parameter
//     √ should create a tgz file if there is a "?download" query parameter and multiple files

//   connect.post
//     √ should take a url and debug parameters and make a HTTP POST Request with a response code of 201 and a body of JSON object
//     √ should take a url as a param and make a HTTP POST Request and return a JSON object

//   connect.post upload functionality
//     √ should upload one file
//     √ should upload multiple files
//     √ should return an error if a file does not exist in the specified file location

//   connect.patch
//     √ should take a url as a param and make a HTTP PATCH Request with a response code of 201 and an empty body

//   connect.delete
//     √ should take a url as a param and make a HTTP DELETE Request with a response code of 404 because the incident with ID of 0 does not exist

//   connect.options
//     √ should be able to make an OPTIONS request and send back the headers