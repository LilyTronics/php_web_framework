<?php

// Load initalization scripts for the framework and the application
require_once("framework/initialize.php");
require_once(DOC_ROOT . APP_PATH . "initialize.php");

// Start logging the time
ModelTimeLogger::start();

// Force SSL if enabled in the application/initialize.php
forceSSL();

// Resolve the requested URI
$router = new ModelRouter();
$router->addRoutesFromFile(APP_ROUTER_FILE);
$router->resolve(REQUEST_URI);
echo $router->getResponse();

// Stop logging the time
ModelTimeLogger::end();
