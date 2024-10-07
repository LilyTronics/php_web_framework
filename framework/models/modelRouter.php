<?php

class ModelRouter
{
    private $routes = array();
    private $controller;
    private $action;
    private $level;
    private $parameters = array();


    public function addRoutesFromFile($configurationFile)
    {
        $lines = file($configurationFile, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line)
        {
            if (substr(trim($line), 0, 1) != "#")
            {
                $parts = explode(",", $line);
                if (count($parts) >= 3)
                {
                    $level = null;
                    if (count($parts) == 4)
                    {
                        $level = trim($parts[3]);
                    }
                    $this->routes[] = array("Uri" => trim($parts[0]),
                                            "Controller" => trim($parts[1]),
                                            "Action" => trim($parts[2]),
                                            "Level" => $level);
                }
            }
        }
    }


    public function resolve($requestUri)
    {
        $isMatch = false;

        // Get the default route, in case the URI is not found
        foreach($this->routes as $route)
        {
            if ($route["Uri"] == "DEFAULT_ACTION")
            {
                $this->controller = $route["Controller"];
                $this->action = $route["Action"];
                $this->level = $route["Level"];
                break;
            }
        }

        // URI can have URI parameters E.G: /my/uri?param=value
        $uriParts = explode("?", $requestUri);
        $requestUri = $uriParts[0];

        $splittedRequestUri = explode("/", trim($requestUri, "/"));
        foreach ($this->routes as $route)
        {
            $this->parameters = array();
            $copyRequestUri = $splittedRequestUri;
            $splittedRouteUri = explode("/", trim($route["Uri"], "/"));
            if (count($copyRequestUri) == count($splittedRouteUri))
            {
                # Could be a match
                $isMatch = true;
                foreach ($splittedRouteUri as $key => $part)
                {
                    if (substr($part, 0, 1) == "{" && substr($part, -1) == "}")
                    {
                        $this->parameters[rawurldecode(substr($part, 1, -1))] = rawurldecode($copyRequestUri[$key]);
                        unset($copyRequestUri[$key]);
                        unset($splittedRouteUri[$key]);
                    }
                    elseif ($part != $copyRequestUri[$key])
                    {
                        $isMatch = false;
                        break;
                    }
                }
                if ($isMatch)
                {
                    $this->controller = $route["Controller"];
                    $this->action = $route["Action"];
                    $this->level = $route["Level"];
                    // Add remaining URI parameters to the parameters, if there were any
                    if (count($uriParts) > 1)
                    {
                        parse_str($uriParts[1], $temp);
                        $this->parameters = array_merge($this->parameters, $temp);
                    }
                    break;
                }
            }
        }
    }


    public function getResponse()
    {
        $controller = new $this->controller();
        return $controller->executeAction($this->action, $this->level, $this->parameters);
    }

}