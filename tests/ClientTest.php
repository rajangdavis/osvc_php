<?php

use PHPUnit\Framework\TestCase;

// client module
//     √ should take "username","password",and "interface" values from and object and match them
//     √ should take a "demo_site" from an object and match it
//     √ should take "no_ssl_verify","version", and "suppress_rules" values from an object and match them
//     √ should raise an error if the object username is blank
//     √ should raise an error if the object password is blank
//     √ should raise an error if the object interface is blank
//     √ should should have version set to "v1.3" if unspecified
//     √ should take an access token