<?php

namespace Index\Controller;

use Qemy\Core\Application;
use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        if (isset(Application::$request_variables['get']['nope'])) {
            if (!Application::$request_variables['cookie']['nope']) {
                Application::$router->setCookie('nope', 1, time() + 60 * 60 * 5);
            }
            Application::$router->toRoute("http://twosphere.ru");
            Application::stop();
        }
        if (Application::$router->isMobileBrowser(Application::$router->getTypePlatform())
            && !Application::$request_variables['cookie']['nope']) {
            Application::$router->toRoute("http://mini.twosphere.ru");
            Application::stop();
        }
        $view = new View('Index');
        $view->setContent('content');
        $view->generate();
    }
}