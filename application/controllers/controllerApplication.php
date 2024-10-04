<?php

class ControllerApplication extends ControllerBase
{

    public function executeAction($action, $level, $parameters)
    {
        return $this->$action($parameters);
    }

    protected function createView($viewName)
    {
        $view = new ViewApplication();
        $view->setPageTitle(APPLICATION_TITLE);
        $view->addMetaTag("name=\"viewport\" content=\"width=device-width, initial-scale=1\"");
        $view->setView($viewName);
        return $view;
    }

}
