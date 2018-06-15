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
	'client' => \$rn_client,
	'query' => \"select * from answers where ID = 1557\"
\033[32m)\033[0m;

\$q = new OSvCPHP\QueryResults;

\$results = \$q->query(\$options);

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
	'client' =>\$rn_client,
	\033[32m'query' => \"select * from answers where ID = 1557\"\033[0m
);

\$q = new OSvCPHP\QueryResults;

\$results = \$q->query(\033[32m\$options\033[0m);

echo json_encode(\$results,  JSON_PRETTY_PRINT);");





define('ANALYTICS_REPORT_RESULTS_NO_ID_OR_LOOKUPNAME_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\")
));

\$options = array(
	\"client\" => \$rn_client,
	\"json\" => array(
		\"filters\" => array(
			array(
				\"name\" => \"search_ex\",
				\"values\" => array(\"returns\")
			)
    	),
    	\"limit\" => 2,
    	\033[32m\"id\" => 176\033[0m
	)
);

\$arr = new OSvCPHP\AnalyticsReportResults;

\$arrResults = \$arr->run(\$options);

echo json_encode(\$arrResults,  JSON_PRETTY_PRINT);");