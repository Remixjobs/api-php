<?php

// Load the library

require_once dirname(__FILE__) . '/../src/Remixjobs/Public/Autoloader.php';
Remixjobs_Public_Autoloader::register();

// Instanciate an API client

$api = new Remixjobs_Public_Api();

// Search for PHP jobs

list ($success, $results, $response) = $api->get('/api/jobs', array('q' => 'php'));

print_r($results);

