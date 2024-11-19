<?php

class ControllerDemo extends ControllerBase
{

    public function executeAction($action, $level, $parameters)
    {
        // Prevent showing ths application when the framework is used as submodule
        $chunks = array_filter(explode("/", WEB_PAGE_FOLDER));
        if (count($chunks) > 1) {
            http_response_code(404);
            die();
        }
        return $this->$action($parameters);
    }

    protected function showIntroduction($parameters)
    {
        $view = $this->createView("viewIntroduction");
        return $view->output();
    }

    protected function createView($viewName)
    {
        $view = new ViewDemo();
        $view->setPageTitle(APPLICATION_TITLE . " Demo");
        $view->addMetaTag("name=\"viewport\" content=\"width=device-width, initial-scale=1\"");
        $view->setView($viewName);
        return $view;
    }

}
