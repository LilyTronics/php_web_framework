<?php

/*
 * Class for the HTML page views.
 */


class HtmlPageView
{
    protected $pageFile;
    protected $pageTitle;
    protected $pageData;
    protected $metaTags = array();
    protected $favIcon = array();
    protected $styleSheets = array();
    protected $javascriptPreVariables = array();
    protected $javascriptPostVariables = array();
    protected $javascriptFiles = array();
    protected $headerSections = array();


    protected function getContentFromPageFile($filename, $path="", $variables=[])
    {
        if ($path == "")
        {
            $path = APP_VIEWS_PATH;
        }
        ob_start();
        extract($variables);
        include DOC_ROOT . $path . $filename;
        return ob_get_clean();
    }


    protected function insertBody()
    {
        $output = "<body>\n";
        $output .= "<!-- Insert contents of {$this->pageFile} -->\n";
        $output .= $this->getContentFromPageFile($this->pageFile);
        $output .= "<!-- End of {$this->pageFile} -->\n\n";
        $output .= "</body>\n";
        return $output;
    }


    public function setView($viewName)
    {
        $this->pageFile = $viewName . ".php";
    }


    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }


    public function getPageData()
    {
        return $this->pageData;
    }


    public function setPageData($pageData)
    {
        $this->pageData = $pageData;
    }


    public function addMetaTag($metaTag)
    {
        $this->metaTags[] = $metaTag;
    }


    public function addFavIcon($href, $type)
    {
        $this->favIcon[] = $href;
        $this->favIcon[] = $type;
    }

    public function addStyleSheet($styleSheet, $isExternal=false, $attributes="")
    {
        if ($isExternal)
        {
            $uri = $styleSheet;
        }
        else
        {
            $uri = ModelHelper::createResourceUri($styleSheet, APP_STYLES_PATH);
        }
        if (strlen($attributes) > 0)
        {
            $attributes .= " ";
        }
        $this->styleSheets[] = array($uri, $attributes);
    }


    public function addJavascriptPreVariable($variableName, $value)
    {
        $this->javascriptPreVariables[$variableName] = $value;
    }


    public function addJavascriptPostVariable($variableName, $value)
    {
        $this->javascriptPostVariables[$variableName] = $value;
    }


    public function addJavaScriptFile($javascriptFile, $isExternal=false, $attributes="")
    {
        if ($isExternal)
        {
            $uri = $javascriptFile;
        }
        else
        {
            $uri = ModelHelper::createResourceUri($javascriptFile, APP_JS_PATH);
        }
        $this->javascriptFiles[] = [$uri, $attributes];
    }


    public function addHeaderSection($sectionData)
    {
        $this->headerSections[] = $sectionData;
    }


    public function output()
    {
        $output = "<!DOCTYPE html>\n";
        $output .= "<html>\n";
        $output .= "<head>\n";
        $output .= "<meta charset=\"UTF-8\" />\n";
        foreach ($this->metaTags as $metaTag)
        {
            $output .= "<meta $metaTag />\n";
        }
        if (count($this->favIcon) > 1)
        {
            $output .= "<link rel=\"icon\" href=\"{$this->favIcon[0]}\" type=\"{$this->favIcon[1]}\" />\n";
        }
        foreach ($this->styleSheets as $styleSheet)
        {
            $output .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"{$styleSheet[0]}\"";
            if ($styleSheet[1] != "") {
                $output .= " {$styleSheet[1]}";
            }
            $output .= " />\n";
        }
        if (count($this->javascriptPreVariables) > 0)
        {
            $output .= "<script>\n";
            $output .= "// Values transferred from PHP to JS before JS scripts are loaded\n\n";
            $output .= "'use strict'\n\n";
            foreach ($this->javascriptPreVariables as $name => $value) {
                $output .= "var $name = $value;\n";
            }
            $output .= "\n</script>\n";
        }
        foreach ($this->javascriptFiles as $javascriptFile)
        {
            $output .= "<script src=\"{$javascriptFile[0]}\"";
            if ($javascriptFile[1] != "") {
                $output .= " {$javascriptFile[1]}";
            }
            $output .= "></script>\n";
        }
        if (count($this->javascriptPostVariables) > 0)
        {
            $output .= "<script>\n";
            $output .= "// Values transferred from PHP to JS after JS scripts are loaded\n\n";
            $output .= "'use strict'\n\n";
            foreach ($this->javascriptPostVariables as $name => $value)
            {
                $output .= "var $name = $value;\n";
            }
            $output .= "\n</script>\n";
        }
        $output .= "<title>" . $this->pageTitle . "</title>\n";
        foreach ($this->headerSections as $headerSection)
        {
            $output .= $headerSection;
        }
        $output .= "</head>\n";
        $output .= $this->insertBody();
        $output .= "</html>\n";
        // If template values are defined, replace them in the output
        // E.g.: define("TEMPLATE_VALUES", [ "{MY_VAR}" => "my value"]);
        if (defined("TEMPLATE_VALUES"))
        {
            $output = strtr($output, TEMPLATE_VALUES);
        }
        return $output;
    }

}
