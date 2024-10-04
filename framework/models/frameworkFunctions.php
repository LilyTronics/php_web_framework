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
        while (substr(REQUEST_URI, 0, 1) == "/")
        {
            $currentUri = ltrim(REQUEST_URI, "/");
        }
        header("Location: " . createLinkTo($currentUri, WEB_ROOT_FORCE_SSL));
    }
}
