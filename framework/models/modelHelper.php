<?php

class ModelHelper
{

    // Adds the web root to the link for creating proper links
    static public function createLinkTo($link, $webRootOverride="")
    {
        if ($webRootOverride == "")
        {
            $webRootOverride = WEB_ROOT;
        }
        return $webRootOverride . $link;
    }


    // Generates an URI for a resource that prevents browser caching the resource
    // See the information page about preventing browser caching for more details.
    static public function createResourceUri($resourceName, $resourcePath="")
    {
        $mtime = filemtime(DOC_ROOT . "$resourcePath$resourceName");
        return self::createLinkTo("$resourcePath$resourceName?$mtime");
    }


    // Generates a copy right string with the years.
    // This will automatically be updated to the current year.
    static public function insertCopyright($startYear="")
    {
        if ($startYear == "")
        {
            $startYear = date("Y");
        }
        $output = "&copy; $startYear";
        if ($startYear != date("Y"))
        {
            $output .= " - " . date("Y");
        }
        return $output;
    }


    // Parse a file size to B, kB, MB ... etc.
    static public function humanFilesize($bytes)
    {
        $size = array("B", "kB", "MB", "GB", "TB");
        $factor = floor((strlen($bytes) - 1) / 3);
        if ($factor >= count($size))
        {
            $factor = count($size) - 1;
        }
        return sprintf("%.1f ", $bytes / pow(1024, $factor)) . @$size[$factor];
    }


    // Returns the max upload file size from the ini file.
    static public function fileUploadMaxSize($outputHumanReadable=true)
    {
        $postMax = parseSize(ini_get("post_max_size"));
        $uploadMax = parseSize(ini_get("upload_max_filesize"));
        // Use the smallest one
        $maxSize = $postMax;
        if ($uploadMax > 0 && $uploadMax < $postMax)
        {
            $maxSize = $uploadMax;
        }
        if ($outputHumanReadable) {
            $maxSize = self::humanFilesize($maxSize);
        }

        return $maxSize;
    }


    // Parse the upload size from the ini file
    static public function parseSize($size)
    {
        // Remove the non-unit characters from the size.
        $unit = preg_replace("/[^bkmgtpezy]/i", "", $size);
        // Remove the non-numeric characters from the size.
        $size = preg_replace("/[^0-9\.]/", "", $size);
        if ($unit)
        {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply.
            return round($size * pow(1024, stripos("bkmgtpezy", $unit[0])));
        }
        else {
            return round($size);
        }
    }


    // Returns posted data
    static public function getPostedData($jsonOnly=false)
    {
        if (!$jsonOnly && count($_POST) > 0)
        {
            // Regular HTML form data
            return $_POST;
        }
        $body = file_get_contents("php://input");
        $jsonData = json_decode($body, true);
        if ($jsonOnly || $jsonData != null)
        {
            // Body contains valid JSON formatted data
            return $jsonData;
        }
        // Just return the body (could be a format we do not know, or incorrectly formatted data)
        return $body;
    }


    // Checks if a string starts with another string
    static public function startsWith ($haystack, $needle)
    {
        $len = strlen($needle);
        return (substr($haystack, 0, $len) === $needle);
    }


    // Checks if a string ends with another string
    static public function endsWith($haystack, $needle)
    {
        $len = strlen($needle);
        if ($len == 0)
        {
            return true;
        }
        return (substr($haystack, -$len) === $needle);
    }

}
