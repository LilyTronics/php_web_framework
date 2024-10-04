<?php

class ControllerBase
{

    public function executeAction($action, $level, $parameters)
    {
        return $this->$action($parameters);
    }


    protected function gotoLocation($location, $webRootOverride="")
    {
        header("Location: " . ModelHelper::createLinkTo($location, $webRootOverride));
        exit();
    }


    protected function createView($viewName)
    {
        $view = new HtmlPageView();
        $view->setPageTitle(APPLICATION_TITLE);
        $view->setView($viewName);
        return $view;
    }

}
