<?php
/*
 * Log times of request to check where your request takes the most time.
 *
 * Just put this in your code at the place you want to log time:
 *      // Place this where you want to start the time logging.
 *      ModelTimeLogger.start();
 *
 *      // Place this somewhere at a point where you want to have the time measured.
 *      ModelTimeLogger.checkPoint("Check point Name");
 *
 *      // Place this where you want to end the time logging.
 *      ModelTimeLogger.end()
 *
 * The times are stored in a session to prevent being time consuming.
 * At the end a file is created with all the times.
 */


class ModelTimeLogger
{

    static private function sessionKey()
    {
        # Variable names must start with a letter or an underscore
        return "_" . strval($_SERVER["REQUEST_TIME_FLOAT"]);
    }


    static public function start()
    {
        $key = self::sessionKey();
        $_SESSION[$key] = array();
        $_SESSION[$key]["uri"] = REQUEST_URI;
        $_SESSION[$key]["start"] = microtime(true);
    }


    static public function checkPoint($label)
    {
        $key = self::sessionKey();
        if (isset($_SESSION[$key]))
        {
            $_SESSION[$key][$label] = microtime(true);
        }
    }


    static public function end()
    {
        $key = self::sessionKey();
        if (isset($_SESSION[$key]))
        {
            $times = $_SESSION[$key];
            $times["end"] = microtime(true);
            $uri = $times["uri"];
            unset($times["uri"]);
            unset($_SESSION[$key]);
            // Calculate time differences
            $keys = array_keys($times);
            for ($i = 0; $i < count($keys) - 1; $i++)
            {
                for ($j = $i + 1; $j < count($keys); $j++)
                {
                    $key = "{$keys[$i]} to {$keys[$j]}";
                    $times[$key] = round($times[$keys[$j]] - $times[$keys[$i]], 3);
                }
            }
            $log = new ModelSystemLogger("timeLogger");
            $log->writeMessage("Times logged for URI: $uri");
            $log->writeDataArray($times);
        }
    }

}
