<?php

define('QUERY_RESULTS_BAD_OPTIONS_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\")
));

\$options = \033[32marray(\033[0m
	'query' => \"select * from answers where ID = 1557\"
\033[32m)\033[0m;

\$q = new OSvCPHP\QueryResults;

\$results = \$q->query(\$rn_client, \$options);

echo json_encode(\$results,  JSON_PRETTY_PRINT);");





define('QUERY_RESULTS_NO_QUERY_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\")
));

\$options = array(
	'query' => \"select * from answers where ID = 1557\"
);

\$q = new OSvCPHP\QueryResults;

\$results = \$q->query(\$rn_client, \033[32m\$options\033[0m);

echo json_encode(\$results,  JSON_PRETTY_PRINT);");