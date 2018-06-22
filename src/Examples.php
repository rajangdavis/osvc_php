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





define('QUERY_RESULTS_SET_BAD_OPTIONS_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\")
));


\$queries = array(
    array(
        \"query\" => \"DESCRIBE INCIDENTS\",
        \"key\" => \"incidents\"
    ),
    array(
        \"query\" => \"DESCRIBE SERVICEPRODUCTS\",
        \"key\" => \"serviceProducts\"
    ),
);


\$options = \033[32marray(\033[0m
    \"client\" => \$rn_client,
    \"queries\" => \$queries
\033[32m)\033[0m;

\$mq = new OSvCPHP\QueryResultsSet;

\$results = \$mq->query_set(\$options);


echo json_encode(\$results->incidents, JSON_PRETTY_PRINT);
echo json_encode(\$results->serviceProducts, JSON_PRETTY_PRINT);

");





define('QUERY_RESULTS_SET_NO_QUERIES_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\")
));

\033[32m\$queries = array(
    array(
        \"query\" => \"DESCRIBE INCIDENTS\",
        \"key\" => \"incidents\"
    ),
    array(
        \"query\" => \"DESCRIBE SERVICEPRODUCTS\",
        \"key\" => \"serviceProducts\"
    ),
);\033[0m


\$options = array(
    \"client\" => \$rn_client,
    \033[32m\"queries\" => \$queries\033[0m
)

\$mq = new OSvCPHP\QueryResultsSet;

\$results = \$mq->query_set(\$options);


echo json_encode(\$results->incidents, JSON_PRETTY_PRINT);
echo json_encode(\$results->serviceProducts, JSON_PRETTY_PRINT);");



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


define('CLIENT_NO_USERNAME_SET_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \033[32m\"username\" => getenv(\"OSC_ADMIN\")\033[0m,
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\")
));
");

define('CLIENT_NO_PASSWORD_SET_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \033[32m\"password\" => getenv(\"OSC_PASSWORD\")\033[0m,
    \"interface\" => getenv(\"OSC_SITE\")
));
");

define('CLIENT_NO_INTERFACE_SET_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \033[32m\"interface\" => getenv(\"OSC_SITE\")\033[0m
));
");


define('BAD_FILE_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\"),
    \"demo_site\" => true
));

\$options = array(
    \"client\" => \$rn_client,
    \"url\" => \"incidents\",
    \"json\" =>  array(
        \"primaryContact\"=>  array(
            \"id\"=>  2
        ),
        \"subject\"=>  \"FishPhone not working\"
    ),
    \033[32m\"files\"\033[0m => array(
        \033[32m\"./haQE7EIDQVUyzoLDha2SRVsP415IYK8_ocmxgMfyZaw.png\"\033[0m,
        \"./License.txt\",
    )
);

\$post_response = OSvCPHP\Connect::post(\$options);
echo json_encode(\$post_response,JSON_PRETTY_PRINT);
");

define('NO_ANNOTATION_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\"),
    \"version\" => \"latest\"
));



\$options = array(
    \"client\" => \$rn_client,
    \"url\" => \"incidents\",
    \"json\" =>  array(
        \"primaryContact\"=>  array(
            \"id\"=>  2
        ),
    \"subject\"=>  \"FishPhone not working\"
    ),
    \033[32m\"annotation\" => \"This is an annotation\"\033[0m,
        
);

\$post_response = OSvCPHP\Connect::post(\$options);
echo json_encode(\$post_response,JSON_PRETTY_PRINT);
");

define('ANNOTATION_MUST_BE_FORTY_CHARACTERS_EXAMPLE', 
"

<?php

require __DIR__ . '/vendor/autoload.php';

\$rn_client = new OSvCPHP\Client(array(
    \"username\" => getenv(\"OSC_ADMIN\"),
    \"password\" => getenv(\"OSC_PASSWORD\"),
    \"interface\" => getenv(\"OSC_SITE\"),
    \"version\" => \"latest\"
));



\$options = array(
    \"client\" => \$rn_client,
    \"url\" => \"incidents\",
    \"json\" =>  array(
        \"primaryContact\"=>  array(
            \"id\"=>  2
        ),
    \"subject\"=>  \"FishPhone not working\"
    ),
    \033[32m\"annotation\" => \"Short message\"\033[0m,
        
);

\$post_response = OSvCPHP\Connect::post(\$options);
echo json_encode(\$post_response,JSON_PRETTY_PRINT);
");