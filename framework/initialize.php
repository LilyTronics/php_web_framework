<?php

require_once("models/frameworkFunctions.php");


// Set if we are on localhost
define("IS_LOCALHOST", $_SERVER["SERVER_NAME"] == "localhost");

// Set the time zone
date_default_timezone_set("UTC");

// Set our own error handler to log errors to the system logger
set_error_handler("errorHandler");

// Register the module auto loader
spl_autoload_register("SystemAutoloader");

// Initialize the session
session_start();

// Get protocol for the WEB_ROOT define
$protocol = GetProtocol();

// Get the web page folder from the script name
$webPageFolder = rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . "/";

// Remove preceding 'WEB_PAGE_FOLDER' in the URI
$requestUri = preg_replace("|/" . trim($webPageFolder, "/") . "|", "", $_SERVER["REQUEST_URI"], 1);

// Framework constants
define("REQUEST_URI", $requestUri);
define("WEB_PAGE_FOLDER", $webPageFolder);
define("WEB_ROOT", $protocol . "://" . $_SERVER["HTTP_HOST"] . WEB_PAGE_FOLDER);
define("WEB_ROOT_FORCE_SSL", "https://" . $_SERVER["HTTP_HOST"] . WEB_PAGE_FOLDER);
define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"] . WEB_PAGE_FOLDER);
define("SYS_LOG_PATH", DOC_ROOT . ".logs/" );
define("LOG_TIME_FORMAT", "Y-m-d H:i:s");
define("MAX_LOG_LINES", 500);
if (defined("SUBMODULE_PATH") && SUBMODULE_PATH != "")
{
    define("FRAMEWORK_PATH", SUBMODULE_PATH . "framework");
}
else
{
    define("FRAMEWORK_PATH", "framework");
}

// Application constants
define("APP_PATH", "application/");
define("APP_ROUTER_FILE", DOC_ROOT . APP_PATH . "routes.conf");
define("APP_CONTROLLERS_PATH", APP_PATH . "controllers/");
define("APP_MODELS_PATH", APP_PATH . "models/");
define("APP_VIEWS_PATH", APP_PATH . "views/");
define("APP_JS_PATH", APP_PATH . "js/");
define("APP_STYLES_PATH", APP_PATH . "styles/");
define("APP_IMAGES_PATH", APP_PATH . "images/");

// Search paths for the autoloader
$_AUTOLOADER_SEARCH_PATHS = array(
    FRAMEWORK_PATH,
    APP_CONTROLLERS_PATH,
    APP_MODELS_PATH,
    APP_VIEWS_PATH
);

if (defined("SHOW_DEBUG") && SHOW_DEBUG)
{
    echo "<pre>";

    echo "Framework constants:\n";
    echo "REQUEST_URI          : " . var_export(REQUEST_URI, true) . "\n";
    echo "WEB_PAGE_FOLDER      : " . var_export(WEB_PAGE_FOLDER, true) . "\n";
    echo "WEB_ROOT             : " . var_export(WEB_ROOT, true) . "\n";
    echo "WEB_ROOT_FORCE_SSL   : " . var_export(WEB_ROOT_FORCE_SSL, true) . "\n";
    echo "DOC_ROOT             : " . var_export(DOC_ROOT, true) . "\n";
    echo "SYS_LOG_PATH         : " . var_export(SYS_LOG_PATH, true) . "\n";
    echo "LOG_TIME_FORMAT      : " . var_export(SYS_LOG_PATH, true) . "\n";
    echo "MAX_LOG_LINES        : " . var_export(MAX_LOG_LINES, true) . "\n";
    if (defined("SUBMODULE_PATH"))
    {
        echo "SUBMODULE_PATH       : " . var_export(SUBMODULE_PATH, true) . "\n";
    }
    echo "FRAMEWORK_PATH       : " . var_export(FRAMEWORK_PATH, true) . "\n";

    echo "\nApplication constants:\n";
    echo "APP_PATH             : " . var_export(APP_PATH, true) . "\n";
    echo "APP_ROUTER_FILE      : " . var_export(APP_ROUTER_FILE, true) . "\n";
    echo "APP_CONTROLLERS_PATH : " . var_export(APP_CONTROLLERS_PATH, true) . "\n";
    echo "APP_MODELS_PATH      : " . var_export(APP_MODELS_PATH, true) . "\n";
    echo "APP_VIEWS_PATH       : " . var_export(APP_VIEWS_PATH, true) . "\n";
    echo "APP_JS_PATH          : " . var_export(APP_JS_PATH, true) . "\n";
    echo "APP_STYLES_PATH      : " . var_export(APP_STYLES_PATH, true) . "\n";
    echo "APP_IMAGES_PATH      : " . var_export(APP_IMAGES_PATH, true) . "\n";

    echo "\nAutoloader search paths:\n";
    echo var_export($_AUTOLOADER_SEARCH_PATHS, true) . "\n";

    echo "</pre>";
}
