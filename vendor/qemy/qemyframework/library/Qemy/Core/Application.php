<?php

namespace Qemy\Core;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\Router\Route;

class Application {

    public static $config = array();
    public static $request_variables = array();

    /** @var $router Route */
    public static $router;

    public static function init($configuration = array()) {
        self::$config = $configuration;
        self::$request_variables = array(
            'request' => $_REQUEST,
            'get' => $_GET,
            'post' => $_POST,
            'cookie' => $_COOKIE,
            'session' => $_SESSION
        );
        return new self();
    }

    function __construct() {}

    public function run() {
        $route = new Route();
        self::$router = $route;

        $controller_class = $route->getControllerName().'\\Controller\\Controller';
        $action_function = $route->getActionName().'Action';
        $params = $route->getParams();

        /** @var $controller AbstractController*/
        $controller = new $controller_class;
        $controller->setParams($params);
        $controller->$action_function();
    }

    public static function stop($param = null) {
        if ($param) {
            exit($param);
        } else {
            exit();
        }
    }
}