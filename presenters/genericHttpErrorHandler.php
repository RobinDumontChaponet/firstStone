<?php

file_put_contents(LOG.'httpError.log', '- genericHttpErrorHandler : code '.http_response_code().', URLRequest : "'.@$_GET['request'].'", internalRequest : '.$query.' -'.PHP_EOL, FILE_APPEND);