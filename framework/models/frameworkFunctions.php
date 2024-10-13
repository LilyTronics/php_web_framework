<?php

// Log all warnings and errors to the system logger.
// The messages are logged to the system logger
function errorHandler($errno, $errstr, $errfile, $errline)
{
    $log = new ModelSystemLogger("errorHandler");
    $log->writeMessage("Errno $errno: $errstr");
    $log->writeMessage("$errfile line $errline");
    // If on localhost, show warnings and errors in the browser.
    if (IS_LOCALHOST)
    {
        // On local host always use the internal PHP handler
        return false;
    }
    // Other hosts, disable the internal PHP handler
    return true;
}


// Auto loader for automatically including source files
function SystemAutoloader($className)
{
    global $_AUTOLOADER_SEARCH_PATHS;

    // Search for source file
    foreach ($_AUTOLOADER_SEARCH_PATHS as $folder)
    {
        if (is_dir(DOC_ROOT . $folder))
        {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(DOC_ROOT . $folder)) as $file)
            {
                if (strtolower(substr($file, -(strlen($className) + 5))) == strtolower(DIRECTORY_SEPARATOR . "$className.php"))
                {
                    require_once("$file");
                    break;
                }
            }
        }
    }
}


// Gets the protocol
function GetProtocol()
{
    $protocol = "http";
    if ( (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") || (isset($_SERVER["REDIRECT_HTTPS"]) && $_SERVER["REDIRECT_HTTPS"] == "on") )
    {
        $protocol = "https";
    }

    return $protocol;
}


// Force SSL if a domain is setup and force SLL is set in the application initialization script
// Set in the application initialize script:
// define("DOMAIN", "<domain_name>");
// define("FORCE_SSL", true);
function forceSSL()
{
    if (defined("DOMAIN") && $_SERVER["SERVER_NAME"] == DOMAIN && defined("FORCE_SSL") && FORCE_SSL && WEB_ROOT != WEB_ROOT_FORCE_SSL)
    {
        FRAMEWORK_DEBUG_LOG->writeMessage("Forcing SSL for URI: " . REQUEST_URI);
        while (substr(REQUEST_URI, 0, 1) == "/")
        {
            $currentUri = ltrim(REQUEST_URI, "/");
        }
        $log->writeMessage("Redirect to $currentUri with SSL");
        header("Location: " . createLinkTo($currentUri, WEB_ROOT_FORCE_SSL));
    }
    else
    {
        FRAMEWORK_DEBUG_LOG->writeMessage("No force SSL enabled");
    }
}


// Log all the framework constants for debugging purpose
function logFrameworkConstants()
{
    global $_AUTOLOADER_SEARCH_PATHS;

    FRAMEWORK_DEBUG_LOG->writeMessage("Framework constants:");
    FRAMEWORK_DEBUG_LOG->writeMessage("IS_LOCALHOST         : " . var_export(IS_LOCALHOST, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("REQUEST_URI          : " . var_export(REQUEST_URI, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("WEB_PAGE_FOLDER      : " . var_export(WEB_PAGE_FOLDER, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("WEB_ROOT             : " . var_export(WEB_ROOT, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("WEB_ROOT_FORCE_SSL   : " . var_export(WEB_ROOT_FORCE_SSL, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("DOC_ROOT             : " . var_export(DOC_ROOT, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("SYS_LOG_PATH         : " . var_export(SYS_LOG_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("LOG_TIME_FORMAT      : " . var_export(SYS_LOG_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("MAX_LOG_LINES        : " . var_export(MAX_LOG_LINES, true));
    if (defined("SUBMODULE_PATH"))
    {
        FRAMEWORK_DEBUG_LOG->writeMessage("SUBMODULE_PATH       : " . var_export(SUBMODULE_PATH, true));
    }
    FRAMEWORK_DEBUG_LOG->writeMessage("FRAMEWORK_PATH       : " . var_export(FRAMEWORK_PATH, true));

    FRAMEWORK_DEBUG_LOG->writeMessage("Application constants:");
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_PATH             : " . var_export(APP_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_ROUTER_FILE      : " . var_export(APP_ROUTER_FILE, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_CONTROLLERS_PATH : " . var_export(APP_CONTROLLERS_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_MODELS_PATH      : " . var_export(APP_MODELS_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_VIEWS_PATH       : " . var_export(APP_VIEWS_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_JS_PATH          : " . var_export(APP_JS_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_STYLES_PATH      : " . var_export(APP_STYLES_PATH, true));
    FRAMEWORK_DEBUG_LOG->writeMessage("APP_IMAGES_PATH      : " . var_export(APP_IMAGES_PATH, true));

    FRAMEWORK_DEBUG_LOG->writeMessage("Autoloader search paths:");
    FRAMEWORK_DEBUG_LOG->writeDataArray($_AUTOLOADER_SEARCH_PATHS);
}

