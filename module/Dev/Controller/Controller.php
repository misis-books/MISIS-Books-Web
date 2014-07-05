<?php

namespace Dev\Controller;

use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'index',
            'content' => 'description'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function apiusageAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'apiusage',
            'content' => 'apiusage'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function apiRequestsAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'api_requests',
            'content' => 'api_requests'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function authRegisterAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'auth.register',
            'content' => 'auth.register'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function methodsListAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'methods_list',
            'content' => 'methods_list'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function searchAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'materials.search',
            'content' => 'materials.search'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function getPopularAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'materials.getpopular',
            'content' => 'materials.getpopular'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function getCategoriesAction() {
        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'materials.getCategories',
            'content' => 'materials.getCategories'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function getContentAction() {
        $view = new View('Dev');
        echo $this->getParams()['get']['hash'].'!hash!';
        $resource = $this->getParams()['get']['resource'];
        $view->includeModuleView($resource);
    }
}