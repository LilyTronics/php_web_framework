<?php

class ModelSystemLogger
{
    private $logFilename;
    private $callerName = "";


    public function __construct($filename="", $callerName="")
    {
        if ($filename == "DUMMY")
        {
            $this->logFilename = null;
        }
        else
        {
            if ($filename == "")
            {
                $trace = debug_backtrace();
                $caller = $trace[1];
                $filename = "general";
                if (isset($caller["class"]))
                {
                    $filename = lcfirst($caller["class"]);
                }
            }
            $this->logFilename = SYS_LOG_PATH . "$filename.log";
        }
        $this->callerName = $callerName;
    }


    public function writeMessage($message)
    {
        $lines = explode("\n", $message);
        $trace = debug_backtrace();
        $this->writeLines($trace, $lines);
    }


    public function writeDataArray($dataArray)
    {
        $dataString = var_export($dataArray, true);
        $lines = array_filter(explode("\n", $dataString));
        $trace = debug_backtrace();
        $this->writeLines($trace, $lines);
    }


    private function writeLines($caller, $arrayWithLines)
    {
        if ($this->logFilename)
        {
            $callerName = $this->callerName;
            if (isset($caller[1]["function"]))
            {
                $callerName = $caller[1]["function"];
            }
            $date = new DateTime();
            $timeStamp = $date->format(LOG_TIME_FORMAT);
            $lines = array();
            if (file_exists($this->logFilename))
            {
                $lines = array_filter(explode("\n", file_get_contents($this->logFilename)));
            }
            foreach($arrayWithLines as $line)
            {
                if ($line != "")
                {
                    $lines[] = "$timeStamp - $callerName - $line";
                }
            }
            if (count($lines) > MAX_LOG_LINES)
            {
                $lines = array_slice($lines, -MAX_LOG_LINES);
            }
            file_put_contents($this->logFilename, implode("\n", $lines));
        }
    }

}
