<?php

class ViewDemo extends HtmlPageView
{

    protected function insertBody()
    {
        $output = "<body>\n\n";
        $output .= $this->getContentFromPageFile($this->pageFile, DEMO_APP_VIEWS);
        $output .= "</body>\n";
        return $output;
    }

}
