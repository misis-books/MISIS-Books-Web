<?php

namespace Dev\Controller;

use Dev\Model\IndexModel;
use Qemy\Core\Controller\AbstractController;
use Qemy\Core\View\View;

class Controller extends AbstractController {

    function __construct() {}

    public function indexAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'index',
            'content' => 'description',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function apiusageAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'apiusage',
            'content' => 'apiusage',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function apiRequestsAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'api_requests',
            'content' => 'api_requests',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function authRegisterAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'auth.register',
            'content' => 'auth.register',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function methodsListAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'methods_list',
            'content' => 'methods_list',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function searchAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'materials.search',
            'content' => 'materials.search',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function getPopularAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'materials.getpopular',
            'content' => 'materials.getpopular',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function getCategoriesAction() {
        $model = new IndexModel();
        $data = $model->main()->getData();

        $view = new View('Dev');
        $view->setData(array(
            'action_name' => 'materials.getCategories',
            'content' => 'materials.getCategories',
            'user' => $data['user'],
            'head_title' => 'Описание API'
        ));
        $view->setContent('dev_wrapper');
        $view->generate();
    }

    public function getContentAction() {
        $view = new View('Dev');
        $data = $this->getParams();
        echo $data['get']['hash'].'!hash!';
        $resource = $data['get']['resource'];
        $view->includeModuleView($resource);
    }
}