<?php

namespace Qemy\Core\Router;

class Route extends AbstractRoute {

    function __construct() {
        parent::__construct();
    }

    public function getControllerName() {
        $broken_uri = explode('/', $this->clear_request_uri);
        $controller_name = null;
        if (isset($broken_uri[1]) && !empty($broken_uri[1])) {
            $controller_name = $this->findController($broken_uri[1]);
            if (!$controller_name) {
                $controller_name = Route::CONTROLLER_NOT_FOUND;
            }
        } else {
            $controller_name = Route::DEFAULT_CONTROLLER;
        }
        $this->module_name = $controller_name;
        return $controller_name;
    }

    public function getActionName() {
        $broken_uri = explode('/', $this->clear_request_uri);
        $action_name = null;
        if (isset($broken_uri[2]) && !empty($broken_uri[2])) {
            $action_name = $this->findAction($broken_uri[2]);
            if (!$action_name) {
                $action_name = Route::DEFAULT_ACTION;
            }
        } else {
            $action_name = Route::DEFAULT_ACTION;
        }
        return $action_name;
    }

    public function getParams() {
        return array(
            'post' => $this->getPostData(),
            'get' => $this->getGetData()
        );
    }

    public function toRoute($route) {
        header('Location: '. $route);
        exit();
    }
}